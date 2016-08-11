<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Etudiant;

Route::get('/', function () {
    return view('welcome');
});

//Get tous les étudiants
Route::get('etudiants/{page}', function ($page) {
    return Response::json(['status' => 200,'etudiants' => Etudiant::skip(($page - 1) * 5)
        ->take(5)
        ->get(['email'])->toArray()
    ]);
});

Route::get('etudiant/show/{id}', 'EtudiantController@show');
Route::post('etudiant/authenticate', 'EtudiantController@authenticate');
Route::post('etudiant/store', 'EtudiantController@store');


Route::post('gestionnaire/exists','GestionnaireController@exists');
Route::post('gestionnaire/store','GestionnaireController@store');
Route::post('gestionnaire/authenticate','GestionnaireController@authenticate');

Route::get('livre/show','LivreController@show');
Route::post('livre/store','LivreController@store');

Route::post('livre/remise','LivreController@getBooksToConfirm');
Route::post('livre/remise/confirm','LivreController@confirmBookReception');

Route::get('livre/reservation','LivreController@getBooks');
Route::post('livre/reservation/pay','LivreController@buyBook');

Route::post('livre/reservation/notification','LivreController@ajouterNotification');

Route::get('livre/reservation/get-confirm','LivreController@getConfirmReservation');
Route::post('livre/reservation/confirm','LivreController@confirmReservation');

Route::get('livre/export','LivreController@getConfirmExportBook');
Route::post('livre/export/confirm','LivreController@confirmExportBook');

Route::get('livre/import','LivreController@getConfirmImportBook');
Route::post('livre/import/confirm','LivreController@confirmImportBook');

//For the external API
Route::group(['prefix' => 'api'], function() {
    Route::get('book/description/{isbn?}','LivreController@getDescription');
    Route::get('book/{data?}','LivreController@getBooks');
    Route::post('book/export','LivreController@exportBook');
    Route::post('book/import','LivreController@importBook');
});
