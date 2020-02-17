<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/cek', 'HomeController@lihatUser')->name('lihat');

Route::get('/jsAllContacts', function () {return view('jsAllContacts');});
Route::get('/allcontactsjs','ContactsController@getAlljson');

Route::get('/allcontacts','ContactsController@getAll');
Route::get('/chatwith/{id}','ContactsController@contactbyId');

Route::get('/contacts','ContactsController@get');
Route::get('/contact/{nama}','ContactsController@getbyName');
Route::get('/conversation/{id}','ContactsController@getMessagesFor');
Route::post('/conversation/send', 'ContactsController@send');

Route::resource('/posts', 'PostController');