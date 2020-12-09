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


Route::resource('jobs', 'JobController');

Route::get('apply-job/{id}','JobController@userApply')->name('apply_job');

Route::get('show-apply-list','JobController@showApplyList')->name('show_apply_list');

Route::get('history','JobController@showHistoryCreateJob')->name('history');

Route::get('list-candidate/{id}','JobController@showListCandidateApply')->name('list_candidate');

Route::get('accept/{user_id}/{job_id}','JobController@accept')->name('accept');

Route::get('reject/{user_id}/{job_id}','JobController@reject')->name('reject');

Route::get('list-user','JobController@viewListUser')->name('list_user');

Route::get('list-job','JobController@viewListJob')->name('list_job');

Route::get('approve-job/{id}','JobController@approveJob')->name('approve_job');
