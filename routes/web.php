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
Route::get('/qrcode', function() {
    return  QRCode::url('werneckbh.github.io/qr-code/')
                  ->setSize(8)
                  ->setMargin(2)
                  ->png();
});    

// Check In
Route::get('/checkin', 'CheckinController@'); // -> view(shared.checkin)

/*
 *
 * Dashboard Route
 * 
 */
Route::get('/dashboard', 'DashboardController@index')->name('dash.index'); // -> view(dashboard.main)
Route::get('/dashboard/history/{date?}', 'DashboardController@history')->name('dash.history'); // -> view(dashboard.main)
Route::get('/dashboard/preview/{paper}', 'DashboardController@preview')->name('dash.preview'); // -> view(paper.preview)
Route::get('/dashboard/detail/{paper}', 'DashboardController@detail')->name('dash.detail'); // -> view(dashboard.detail)
    Route::get('/dashboard/permit/{paper}', 'DashboardController@permit')->name('dash.permit'); // -> 


/*
 *
 * Papers Route
 * 
 */
Route::get('/papers/{paper_uuid?}', function($paper_uuid = null) {
    switch ($paper_uuid)
    {
        case null:
        case 'student':
        case 'assistant':
        case 'upload_and_permit':
        case 'teacher':
            return "无效访问，缺少UUID";
            break;
        
        default:
            return view('papers.index', ['paper_uuid' => $paper_uuid]);
            break;

    }
})->name('papers.index'); // -> view(papers.index)

Route::get('/papers/student/{paper_uuid}', 'PapersController@student')->name('papers.student'); // -> view(papers.upload_img) / view(papers.self_correct)
    Route::post('/papers/submit_self_correction/{paper_uuid}', 'PapersController@submit_self_correction')->name('papers.sumbit');

Route::get('/papers/assistant/{paper_uuid}', 'PapersController@assistant')->name('papers.assistant');
    Route::post('/papers/upload_and_permit/{paper_uuid}', 'PapersController@upload_and_permit')->name('papers.upload_and_permit');

Route::get('/papers/teacher/{paper_uuid}', function($paper_uuid = null) {
    return Redirect::route('dash.detail', $paper_uuid);
})->name('papers.teacher'); // -> redirect()->route('/dashboard/detail/{paper}')


/*
 *
 * Demo Route
 * 
 */
Route::get('/demo', 'DemoController@index')->name('demo.index');
    Route::get('/demo/check_word_dt', 'DemoController@check_word_dt')->name('demo.check_word_dt');
    Route::get('/demo/refill_words', 'DemoController@refill_words')->name('demo.refill_words');
    Route::get('/demo/gen_character', 'DemoController@gen_character')->name('demo.gen_character');
    Route::get('/demo/gen_paper', 'DemoController@gen_paper')->name('demo.new_paper');
    Route::get('/demo/reset_all/{psk?}', 'DemoController@reset_all')->name('demo.reset_all');