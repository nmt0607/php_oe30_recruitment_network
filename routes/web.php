<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/employer/register', 'RegisterEmployerController@register')
->name('employer.register');
Route::resource('companies', CompanyController::class);

Route::resource('jobs', 'JobController');

Route::post('apply/{id}', 'JobController@apply')->name('apply');

Route::get('show-apply-list', 'JobController@showApplyList')->name('show_apply_list');

Route::get('history','JobController@showHistoryCreateJob')->name('history');

Route::get('list-candidate/{id}','JobController@showListCandidateApply')->name('list_candidate');

Route::get('accept-reject/{user_id}/{job_id}/{status}','JobController@acceptOrReject')->name('accept_reject');
