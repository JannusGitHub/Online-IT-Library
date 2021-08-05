<?php
use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => 'PreventBackHistory'], function(){
	Auth::routes();
	Route::get('/', function () {
        return view('index');
    })->name('login');
});

// ADMIN VIEWS
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/user_management', function () {
    return view('user_management');
})->name('user_management');

Route::get('/list_of_workloads', function () {
    return view('list_of_workloads');
})->name('list_of_workloads');

// USER VIEWS
Route::get('/user', function () {
    return view('user');
})->name('user');

Route::get('/change_pass_view', function () {
    return view('change_password');
});


// SIGN IN & SIGN-OUT
Route::post('/sign_in', 'UserController@sign_in')->name('sign_in');
Route::post('/sign_out', 'UserController@sign_out')->name('sign_out');


// USER CONTROLLER
Route::post('/add_user', 'UserController@add_user')->name('add_user');
Route::post('/edit_user', 'UserController@edit_user');
Route::post('/delete_user', 'UserController@delete_user');
Route::post('/restore_user', 'UserController@restore_user');
Route::post('/change_pass', 'UserController@change_pass')->name('change_pass');
Route::post('/change_user_stat', 'UserController@change_user_stat')->name('change_user_stat');
Route::post('/reset_password', 'UserController@reset_password');
Route::get('/view_users_archive', 'UserController@view_users_archive');
Route::get('/view_users', 'UserController@view_users');
Route::get('/get_user_by_id', 'UserController@get_user_by_id');
Route::get('/get_user_by_stat', 'UserController@get_user_by_stat');
Route::get('/get_total_users', 'UserController@get_total_users');


// USER LEVEL CONTROLLER
Route::get('/get_user_levels', 'UserLevelController@get_user_levels');


// WORKLOAD CONTROLLER
Route::post('/add_workload', 'WorkloadController@add_workload')->name('add_workload');
Route::post('/edit_workload', 'WorkloadController@edit_workload');
Route::post('/delete_workload', 'WorkloadController@delete_workload');
Route::post('/restore_workload', 'WorkloadController@restore_workload');
Route::get('/view_workloads', 'WorkloadController@view_workloads');
Route::get('/download_attached_document/{id}','WorkloadController@download_attached_document')->name('download_attached_document');
Route::get('/get_workload_by_id', 'WorkloadController@get_workload_by_id');
Route::get('/view_workloads_user_dashboard', 'WorkloadController@view_workloads_user_dashboard');
Route::get('/get_total_workloads', 'WorkloadController@get_total_workloads');
Route::get('/view_workloads_archive', 'WorkloadController@view_workloads_archive');
Route::get('/get_total_records', 'WorkloadController@get_total_records');