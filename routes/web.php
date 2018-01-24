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

/* Home (main) page. */
Route::get('/{name}', 'HomeController@index')->where('name', 'home|index|');

/* Language operations. */
Route::get('/switchLanguage/{lang}', 'HomeController@switchLanguage');
Route::post('/getLanguage', 'HomeController@getLanguage');

/* Login and logout. */
Route::get('/loginAction', 'LoginController@loginAction');
Route::get('/logoutAction', 'LoginController@logoutAction');

/* Different HTML parts without extended layout. */
Route::get('/getPartMediaNavbar', 'HomeController@partMediaNavbar');
Route::get('/getPartLoginBox', 'HomeController@partLoginBox');
Route::get('/getPartControlPanel', 'HomeController@partControlPanel');
Route::get('/getPartNewsPostForm', 'HomeController@partNewsPostForm');

/* News */
Route::post('/addNews', 'NewsController@add');

/* Photos */
Route::resource('/pages/photos', 'PhotosController');

/* Videos */
Route::resource('/pages/videos', 'VideosController');

/* Contacts */
Route::resource('/pages/contacts', 'ContactsController');

/* Calendars (coming soon) */
//Route::resource('/pages/calendars', 'CalendarsController');