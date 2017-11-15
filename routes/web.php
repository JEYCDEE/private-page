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

Route::get('/{name}', 'HomeController@index')->where('name', 'home|index|');
Route::get('/getPartMediaNavbar', 'HomeController@partMediaNavbar');

Route::resource('/pages/photos', 'PhotosController');
Route::resource('/pages/videos', 'VideosController');
Route::resource('/pages/contacts', 'ContactsController');
Route::resource('/pages/calendars', 'CalendarsController');