<?php

namespace App\Http\Controllers;

use App\Gestionnaire;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Cooperative;
use JWTAuth;
use JWTFactory;
use Illuminate\Support\Facades\Hash;

class GestionnaireController extends Controller
{

    public function __construct(){

    }

    public function authenticate(Request $request)
    {
        $data = $request->only('email','password');

        if(!Gestionnaire::authenticate($data)){
            abort(403, 'Nothing was found');
        }

        //Everything is fine, we generate the token
        $payload = JWTFactory::make($data);
        $token = JWTAuth::encode($payload)->get();

        // if no errors are encountered we can return a JWT(token)
        return response()->json(["token"=>$token,'isEtudiant'=>false]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $message = "";

        // Takes the request data and form an array
        $gestionnaireData = $request->only('email', 'password');
        $coopData = $request->only('address', 'name');

        // Hash the password
        $gestionnaireData['password'] = Hash::make($gestionnaireData['password']);

        //Create a new gestionnaire from the data (mass assignment)
        $gestionnaire = new Gestionnaire($gestionnaireData);

        $coop = new Cooperative($coopData);
        $coop->save();

        //Create association between a gestionnaire and a cooperative
        $gestionnaire->cooperative()->associate($coop);

        //Save it to the database
        $gestionnaire->save();
        $message = "success";


        return response()->json(['status' => 200, 'message' => $message]);

    }

    /**
     * Check if the email is already taken.
     *
     * @param  Request  $request
     * @return Response
     */
    public function exists(Request $request)
    {
        $data = $request->only("email");
        $email = $data['email'];
        $gestionnaire = Gestionnaire::where("email",$email)->first();
        if(empty($gestionnaire)){
            return response()->json(['status' => 200, 'message' => "success"]);
        }
        else{
            abort(403, "Not allowed");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $gestionnaire = Gestionnaire::find($id);
        return response()->json(['status' => 200, 'gestionnaire' => $gestionnaire->toArray()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
