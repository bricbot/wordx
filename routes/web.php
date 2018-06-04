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

// Dashboard
Route::get('/dashboard', 'DashboardController@'); // -> view(dashboard.main)
Route::get('/dashboard/history/{date}', 'DashboardController@'); // -> view(dashboard.main)
Route::get('/dashboard/preview/{paper}', 'DashboardController@'); // -> view(paper.preview)
Route::get('/dashboard/detail/{paper}', 'DashboardController@'); // -> view(dashboard.detail)
    Route::get('/dashboard/permit/{paper}', 'DashboardController@'); // -> 

// Check In
Route::get('/checkin', 'CheckinController@'); // -> view(shared.checkin)

// Paper
Route::get('/paper/role_selector/{paper}', 'PaperController@'); // -> view(paper.role_selector)
Route::get('/paper/student/{paper}', 'PaperController@'); // -> view(paper.upload_img) / view(paper.self_correct)
    Route::post('/paper/upload_img/{paper}', 'PaperController@');

Route::get('/paper/teacher/{paper}', 'PaperController@'); // -> redirect()->route('/dashboard/detail/{paper}')

// Demo Controll
Route::get('/test_words', 'TestController@test_combind_words');
Route::get('/init_words', 'TestController@refill_words');
Route::get('/test', 'TestController@test');

Route::get('/demo', 'DemoController@index');
    Route::get('/demo/gen_word_paper', 'DemoController@gen_word');