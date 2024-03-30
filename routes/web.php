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

// Import section
Route::group(['prefix' => 'import', 'as' => 'import.', 'namespace' => 'Import'], function () {

    // Get import view
    Route::get('/',['as' => 'view','uses' => 'ImportExcelController@view']);

    //Submit Data MediaBiasFactCheckImport
    Route::post('/ADFontesMedia', ['as' => 'ADFontesMedia', 'uses' => 'ImportExcelController@importApiData']);

    //Submit Data MediaBiasFactCheckImport
    Route::post('/MediaBiasFactCheck', ['as' => 'MediaBiasFactCheck', 'uses' => 'ImportExcelController@importApiData']);
    Route::post('/MediaBiasFactCheckJson', ['as' => 'MediaBiasFactCheckJson', 'uses' => 'ImportExcelController@importJson']);

});
