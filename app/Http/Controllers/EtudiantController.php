<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;
use App\Http\Requests;
use App\Etudiant;
use App\Cooperative;
use JWTAuth;
use JWTFactory;
use Illuminate\Support\Facades\Hash;

class EtudiantController extends Controller
{

    public function __construct(){
    }

    public function authenticate(Request $request)
    {
        $data = $request->only('email','password','phone');

        if(!Etudiant::authenticate($data)){
            abort(403, 'Nothing was found');
        }

        //Everything is fine, we generate the token
        $payload = JWTFactory::make($data);
        $token = JWTAuth::encode($payload)->get();

        // if no errors are encountered we can return a JWT(token)
        return response()->json(["token"=>$token,'isEtudiant'=>true]);
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
        $data = $request->only('email', 'password','phone','name');

        // Hash the password
        $data['password'] = Hash::make($data['password']);

        //Create a new student from the data (mass assignment)
        $etudiant = new Etudiant($data);

        //Select the first cooperative it finds;
        $coop = Cooperative::all()->first();

        //Create association between a student and a cooperative
        $etudiant->cooperative()->associate($coop);

        try{
            //Save it to the database
            $etudiant->save();

            $message = "success";
        }
        catch(QueryException $e){
            abort(403, 'Unauthorized action.');
        }

        return response()->json(['message' => $message]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $etudiant = Etudiant::find($id);
        return response()->json(['etudiant' => $etudiant->toArray()]);
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
