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

Route::get('/', [
    /*'as' => 'reports.create',
    'uses' => 'ReportController@index'*/
]);



Route::group(['middleware' => 'auth'], function (){

    Route::resource('template', 'TemplateController');

    Route::get('/', function () {
        return redirect()->route('projects.index');
    });

    Route::get('report/{project}', [
        'as' => 'report.setup',
        'uses' => 'ReportController@setup'
    ]);

    Route::get('projects/metrics', [
        'as' => 'metrics',
        'uses' => 'ProjectsController@metricsList'
    ]);

    Route::resource('projects', 'ProjectsController');

    /*Route::post('create', [
        'as' => 'report_create',
        'uses' => 'ReportController@create'
    ]);*/

    Route::post('download', [
        'as' => 'report_download',
        'uses' => 'ReportController@download'
    ]);

    Route::post('upload', [
        'as' => 'report_upload',
        'uses' => 'ReportController@upload'
    ]);

    Route::post('get_auto_text', [
        'as' => 'get_auto_text',
        'uses' => 'ReportController@getAutoText'
    ]);

    Route::post('generate_preview', [
        'as' => 'generate_preview',
        'uses' => 'ReportController@generatePreview'
    ]);

    Route::get('oauth', [
        'as' => 'oauth',
        'uses' => 'OAuth@makeToken'
    ]);

    Route::get('oauth/partner', [
        'as' => 'oauth.pertner',
        'uses' => 'OAuth@makePartnerToken'
    ]);

    Route::get('oauth/seranking', [
        'as' => 'oauth.seranking',
        'uses' => 'OAuth@mekeSERankingToken'
    ]);

    Route::get('chart', [
        'as' => 'chart',
        'uses' => 'ReportController@makeChart'
    ]);

});

Auth::routes();


//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/aktiv8me/verify/{token}', '\App\Http\Controllers\Auth\RegisterController@verify')->name('register.verify');
Route::get('/aktiv8me/resend', '\App\Http\Controllers\Auth\RegisterController@resend');
Route::post('/aktiv8me/resend', '\App\Http\Controllers\Auth\RegisterController@resend')->name('register.resend');
