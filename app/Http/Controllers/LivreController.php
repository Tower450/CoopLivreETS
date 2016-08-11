<?php

namespace App\Http\Controllers;

use App\NotificationRemise;
use Illuminate\Http\Request;
use App\Http\Requests;
use Amazon;
use App\DescriptionLivre;
use App\TransactionLivre;
use App\CooperativeExterne;
use App\ReservationLivre;
use App\LivreAEnvoyer;
use App\LivreARecevoir;
use App\Livre;
use JWTAuth;
use JWTFactory;
use App\Etudiant;
use DB;
use Mail;

class LivreController extends Controller
{
    /**
     * LivreController constructor.
     */
    public function __construct()
    {
        $this->middleware('coop.authEtudiant', ['only' => ['store']]);
        $this->middleware('coop.authGestionnaire', ['only' => ['confirmBookReception','getBooksToConfirm']]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $dataLivre = array();
        $dataLivre['state'] = $request->input('state');
        $dataLivre['price'] = $request->input('prixDesirer');

        JWTAuth::setToken($request->input('token'));
        $token = JWTAuth::getToken();
        $credential = JWTAuth::decode($token);

        $dataStudent['email'] = $credential->get("email");
        $dataStudent['phone'] = $credential->get("phone");

        //Go find the student with email or phone
        if(empty($dataStudent['email'])){
            $student = Etudiant::where('phone',$dataStudent['phone'])->first();
        }
        else{
            $student = Etudiant::where('email',$dataStudent['email'])->first();
        }

        $descriptionLivre = DescriptionLivre::firstOrNew([
            "ISBN_10"=>$request->input('ISBN_10'),
            "ISBN_13"=>$request->input('ISBN_13')
        ]);

        $descriptionLivre->price = $request->input('price');
        $descriptionLivre->pages = $request->input('pages');
        $descriptionLivre->author = $request->input('author');
        $descriptionLivre->title = $request->input('title');
        $descriptionLivre->image_link = $request->input('image_link');

        $descriptionLivre->save();

        $livre = new Livre($dataLivre);

        //Create association between a book and a bookdescription
        $livre->descriptionLivre()->associate($descriptionLivre);

        $livre->etudiant()->associate($student);

        //Save it to the database
        $livre->save();

        //We search for it in the
        return response()->json(['status' => 200, 'message'=>'success']);
    }

    /**
     * Display a specified resource
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $isbn = $request->input('isbn');

        //We search for it in the database first
        if(empty(DescriptionLivre::where('ISBN_10',$isbn)->first()) && empty(DescriptionLivre::where('ISBN_13',$isbn)->first())){
            $bookDescription = Amazon::getBookFromIsbn($isbn);
        }
        else{
            $bookDescription =  array(DescriptionLivre::where('ISBN_10',$isbn)->orWhere('ISBN_13',$isbn)->first());
        }

        //We search for it in the
        return response()->json(['status' => 200, 'books' => $bookDescription]);
    }


    /**
     * Get the description from the database when a call made with the api
     * at the url /api/book/description/9781408834
     *
     * @param $isbn can be an ISBN10 and ISBN13 or UPC
     * @param $request for a get request
     * @return \Illuminate\Http\JsonResponse book description in json
     */
    public function getDescription(Request $request,$isbn=null){

        // if some one call the api with a get parameter /api/book/description/?isbn=9781408834
        if(!empty($request->input("isbn"))){
            $isbn = $request->input("isbn");
        }

        // /api/book/description/9781408834
        $bookDescription = DescriptionLivre::where('ISBN_10',$isbn)->first();

        if(empty($bookDescription)){
            $bookDescription = DescriptionLivre::where('ISBN_13',$isbn)->first();
        }
        if(empty($bookDescription)){
            $bookDescription = DescriptionLivre::where('UPC',$isbn)->first();
        }

        return response()->json($bookDescription->toArray());
    }


    public function getBooks(Request $request,$data=null){

        // if some one call the api with a get parameter /api/book/?data

        if(!empty($request->input("author"))){
            $data = $request->input("author");
        }
        if(!empty($request->input("isbn"))){
            $data = $request->input("isbn");
        }
        if(!empty($request->input("title"))){
            $data = $request->input("title");
        }

        if($data!=null){
            // /api/book/9781408834
            //Select très complexe avec du fulltext search
            $books = DB::table('livres')
                ->join('description_livres','description_livres_id','=','description_livres.id')
                ->where('livres.approved',1)
                ->where('livres.sold',0)
                ->where(function($query) use ($data){
                    $query->where('description_livres.ISBN_10',$data)
                        ->orWhere('description_livres.ISBN_13',$data)
                        ->orWhere('description_livres.author',$data)
                        ->orWhereRaw('MATCH(title) AGAINST("'.$data.'")')
                        ->orWhereRaw('MATCH(author) AGAINST("'.$data.'")');
                })
                ->select(
                    'livres.id',
                    'livres.state',
                    'livres.price',
                    'description_livres.ISBN_10',
                    'description_livres.ISBN_13',
                    'description_livres.title',
                    'description_livres.image_link',
                    'description_livres.author',
                    'description_livres.pages',
                    'description_livres.UPC'
                )
                ->get();

            return response()->json($books);
        }
        else{
            return response()->json(array());
        }
    }

    /**
     * To get all books that needs confirmations.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBooksToConfirm(Request $request){

        //TODO Add token validation for the gestionnaire
        $data = $request->input('request');
        //Select très complexe avec du fulltext search
        $books = DB::table('livres')
            ->join('etudiants','etudiant_id','=','etudiants.id')
            ->join('description_livres','description_livres_id','=','description_livres.id')
            ->where('livres.approved',0)
            ->where('livres.sold',0)
            ->where(function($query) use ($data){
                $query->where('etudiants.name',$data)
                    ->orWhere('description_livres.ISBN_10',$data)
                    ->orWhere('description_livres.ISBN_13',$data)
                    ->orWhere('description_livres.author',$data);
            })
            ->select(
                'livres.id',
                'livres.state',
                'livres.price',
                'etudiants.name as studentName',
                'etudiants.email as studentEmail',
                'description_livres.ISBN_10',
                'description_livres.ISBN_13',
                'description_livres.image_link as img',
                'description_livres.title',
                'description_livres.author',
                'description_livres.pages',
                'description_livres.price as originalPrice',
                'description_livres.UPC'
            )
            ->get();

        return response()->json($books);

    }

    /**
     * To confirm the book reception
     * @param Request $request
     */
    public function confirmBookReception(Request $request){

        $book = $request->input('data');
        $confirm = $request->input('confirm');

        $emailData = array(
            "title"=>$book['title'],
            "name"=>$book['studentName'],
            "email"=>$book['studentEmail']
        );

        if($confirm){
            $bookToUpdate = Livre::find($book['id']);
            $bookToUpdate->state = $book['state'];
            $bookToUpdate->approved = 1;
            $bookToUpdate->save();

            //Check if this book is waiting for a notification
            $studentNotification = NotificationRemise::join('etudiants','etudiants_id','=','etudiants.id')
                                        ->where("isbn",$book['ISBN_10'])
                                        ->select(
                                            'etudiants.email as email',
                                            'etudiants.name as name',
                                            'isbn'
                                            )
                                        ->get();

            //For each nofitication found, we send an email
            foreach($studentNotification as $student){
                $emailData['email'] = $student['email'];
                $emailData['isbn'] = $student['isbn'];
                //Send confirmation of reception
                Mail::send('emails.notification', $emailData, function($message) use ($emailData)
                {
                    $message->from('no-reply@ets.arid.me');
                    $message->to($emailData['email'], $emailData['name'])->subject('Notification reception livre');
                });
            }

            //Send confirmation of reception
            Mail::send('emails.remise', $emailData, function($message) use ($emailData)
            {
                $message->from('no-reply@ets.arid.me');
                $message->to($emailData['email'], $emailData['name'])->subject('Remise livre');
            });
        }
        else{
            $bookToUpdate = Livre::find($book['id']);
            $bookToUpdate->delete();
            Mail::send('emails.remise-cancel', $emailData, function($message) use ($emailData)
            {
                $message->from('no-reply@ets.arid.me');
                $message->to($emailData['email'], $emailData['name'])->subject('Remise livre annulé');
            });
        }

    }

    public function buyBook(Request $request){

        JWTAuth::setToken($request->input('token'));
        $token = JWTAuth::getToken();
        $credential = JWTAuth::decode($token);


        $studentEmail = $credential->get("email");
        $stripeToken = $request->input('stripeToken');
        $book = $request->input('book');

        \Stripe\Stripe::setApiKey("sk_test_zLXx7THzgI0DEOf47lCfiUMz");

        $payment = \Stripe\Charge::create(array(
            "amount" => strval($book['price']*100),
            "currency" => "cad",
            "source" => $stripeToken['id'], // obtained with Stripe.js
            "description" => "Charge to ".$studentEmail." for ".$book['title'],
            "receipt_email"=>$stripeToken['email']
        ));

        $student = Etudiant::where('email',$studentEmail)->first();

        $book = Livre::find($book['id']);

        //Now that it works we need to create a new transaction for the book. and a reservation
        $transaction = new TransactionLivre;
        $transaction->amount=$book['price'];
        $transaction->refNumber=$payment['id'];

        $transaction->save();

        $reservation = new ReservationLivre;

        //Create association between a book and a bookdescription
        $reservation->transaction_livres_id = $transaction->id;

        //Associate the book and change the sold value
        $reservation->livres_id = $book->id;

        //Associate the student
        $reservation->etudiants_id = $student->id;

        $book->sold = 1;
        $book->save();
        $reservation->save();



        return response()->json(['status' => 200, 'message'=>'success']);
    }


    public function getConfirmReservation(Request $request){

        //TODO Add token validation for the gestionnaire
        $data = $request->input('request');
        //Select très complexe avec du fulltext search
        $books = DB::table('reservation_livres')
            ->join('etudiants','etudiants_id','=','etudiants.id')
            ->join('livres','livres_id','=','livres.id')
            ->join('description_livres','livres.description_livres_id','=','description_livres.id')
            ->where('livres.approved',1)
            ->where('livres.sold',1)
            ->where('reservation_livres.isPicked',0)
            ->select(
                'reservation_livres.id as idReservation',
                'livres.id',
                'livres.state',
                'livres.price',
                'etudiants.name as studentName',
                'etudiants.email as studentEmail',
                'description_livres.ISBN_10',
                'description_livres.ISBN_13',
                'description_livres.image_link as img',
                'description_livres.title',
                'description_livres.author',
                'description_livres.pages',
                'description_livres.UPC'
            )
            ->get();

        return response()->json($books);

    }

    public function confirmReservation(Request $request){

        $idReservation = $request->input('idReservation');

        $reservation = ReservationLivre::find($idReservation);
        $reservation->isPicked = 1;

        $reservation->save();

        return response()->json(['status' => 200, 'message'=>'success']);
    }

    public function ajouterNotification(Request $request){

        $isbn = $request->input('isbn');

        JWTAuth::setToken($request->input('token'));
        $token = JWTAuth::getToken();
        $credential = JWTAuth::decode($token);

        $studentEmail = $credential->get("email");

        $student = Etudiant::where('email',$studentEmail)->first();
        $notificationRemise = new NotificationRemise;

        $notificationRemise->etudiants_id = $student->id;
        $notificationRemise->isbn = $isbn;

        $notificationRemise->save();

        return response()->json(['status' => 200, 'message'=>'success']);
    }

    public function getConfirmExportBook(){
        $books = DB::table('livres_a_envoyer')
            ->join('cooperatives_externes','cooperatives_externes_id','=','cooperatives_externes.id')
            ->join('description_livres','description_livres_id','=','description_livres.id')
            ->where('livres_a_envoyer.sended',0)
            ->select(
                'livres_a_envoyer.id',
                'cooperatives_externes.name as coopName',
                'cooperatives_externes.address as coopAddress',
                'description_livres.image_link as img',
                'description_livres.title'
            )
            ->get();

        return response()->json($books);
    }

    public function confirmExportBook(Request $request){
        $livres = $request->input('livres');

        foreach($livres as $livre){
            $livreAEnvoyer = LivreAEnvoyer::find($livre['id']);
            $livreAEnvoyer->sended=1;
            $livreAEnvoyer->save();
        }
    }

    public function getConfirmImportBook(){
        $books = DB::table('livres_a_recevoir')
            ->join('cooperatives_externes','cooperatives_externes_id','=','cooperatives_externes.id')
            ->join('description_livres','description_livres_id','=','description_livres.id')
            ->where('livres_a_recevoir.received',0)
            ->select(
                'livres_a_recevoir.id',
                'cooperatives_externes.name as coopName',
                'cooperatives_externes.address as coopAddress',
                'description_livres.image_link as img',
                'description_livres.title'
            )
            ->get();
        return response()->json($books);
    }

    public function confirmImportBook(Request $request){
        $livres = $request->input('livres');

        foreach($livres as $livre){
            $livreAEnvoyer = LivreARecevoir::find($livre['id']);
            $livreAEnvoyer->received=1;
            $livreAEnvoyer->save();

            $livreAAjouterBD = new Livre;
            $livreAAjouterBD->description_livres_id = $livreAEnvoyer->description_livres_id;
            $livreAAjouterBD->state = $livreAEnvoyer->state;
            $livreAAjouterBD->price = $livreAEnvoyer->price;
            $livreAAjouterBD->save();

        }
    }

    public function exportBook(Request $request){

        $idBook = $request->input('id');
        $coopName = $request->input('coop_name');
        $coopAddress = $request->input('coop_address');

        //Create the external cooperative
        $externalCoop = CooperativeExterne::firstOrNew(['name'=>$coopName,'address'=>$coopAddress]);
        $externalCoop->name = $coopName;
        $externalCoop->address = $coopAddress;

        $externalCoop->save();

        //Get info from the book
        $book = Livre::find($idBook);

        //Create the new book in exports
        $exportBook = new LivreAEnvoyer;
        $exportBook->cooperatives_externes_id = $externalCoop->id;
        $exportBook->state = $book->state;
        $exportBook->price = $book->price;
        $exportBook->description_livres_id = $book->description_livres_id;

        $exportBook->save();

        //Remove the book from our coop
        $book->delete();
    }

    public function importBook(Request $request){

        $coopName = $request->input('coop_name');
        $coopAddress = $request->input('coop_address');
        $UPC = $request->input('UPC');
        $ISBN_13 = $request->input('ISBN_13');
        $ISBN_10 = $request->input('ISBN_10');
        $state = $request->input('state');
        $price = $request->input('price');
        $author = $request->input('author');
        $pages = $request->input('pages');

        //Create the external cooperative
        $externalCoop = CooperativeExterne::firstOrNew(['name'=>$coopName,'address'=>$coopAddress]);
        $externalCoop->name = $coopName;
        $externalCoop->address = $coopAddress;

        $externalCoop->save();

        //Create the book description if it doesn't exists
        $descriptonLivre = DescriptionLivre::where('ISBN_13',$ISBN_13)->orWhere('ISBN_10',$ISBN_10)->first();

        //If the description doesn't exist we create a new one
        if(empty($descriptonLivre)){
            $descriptonLivre = new DescriptionLivre;
            $descriptonLivre->UPC = $UPC;
            $descriptonLivre->ISBN_13 = $ISBN_13;
            $descriptonLivre->ISBN_10 = $ISBN_10;
            $descriptonLivre->price = $price;
            $descriptonLivre->author = $author;
            $descriptonLivre->pages = $pages;

            $descriptonLivre->save();
        }

        //Create the new book in exports
        $importBook = new LivreARecevoir;
        $importBook->cooperatives_externes_id = $externalCoop->id;
        $importBook->description_livres_id = $descriptonLivre->id;
        $importBook->state = $state;
        $importBook->price = $price;

        $importBook->save();

    }
}
