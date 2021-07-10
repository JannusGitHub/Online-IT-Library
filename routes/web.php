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
    return view('user');
})->name('user');

// USER CONTROLLER
Route::post('/sign_in', 'UserController@sign_in')->name('sign_in');
Route::post('/sign_out', 'UserController@sign_out')->name('sign_out');
Route::post('/add_user', 'UserController@add_user');
Route::post('/edit_user', 'UserController@edit_user');
Route::post('/change_pass', 'UserController@change_pass')->name('change_pass');
Route::post('/reset_password', 'UserController@reset_password');
Route::post('/change_user_stat', 'UserController@change_user_stat')->name('change_user_stat');
Route::get('/view_users', 'UserController@view_users');
Route::get('/get_user_by_id', 'UserController@get_user_by_id');
Route::get('/get_user_by_stat', 'UserController@get_user_by_stat');
// Route::get('/get_user_list', 'UserController@get_user_list');
// Route::get('/get_user_by_batch', 'UserController@get_user_by_batch');
// Route::get('/generate_user_qrcode', 'UserController@generate_user_qrcode');
// Route::post('/import_user', 'UserController@import_user');