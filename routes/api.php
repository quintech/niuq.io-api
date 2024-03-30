<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//API
Route::group(['namespace' => 'Api'], function () {

    //Eth Blockchain Transaction Area
    Route::group(['namespace' => 'Eth\Erc20'], function () {

        //First, record the token transaction in the database
        Route::post('/giveUserNiuq', ['uses' => 'Erc20TransactionController@giveUserNiuq']);

        // Get token sending records
        Route::get('/getUserTransaction',['uses' => 'Erc20TransactionController@getUserTrasaction']);


        //Erc20 transaction
//        Route::post('/transactionErc20Token', ['uses' => 'Erc20TransactionController@transactionErc20Token']);
    });

    // Get system information
    Route::group(['namespace' => 'System'], function () {

        // Get the current external IP location of the user
        Route::get('/getip',['uses' => 'getSystemInformationController@getIp']);

    });

    //Related to news
    Route::group(['namespace' => 'News'], function () {

        // Get HTML tags of a URL
        Route::get('/verifynews',['uses' => 'NewsController@getUrlData']);

        // Add news
        Route::post('/store',['uses' => 'NewsController@saveNews']);

        // Retrieve all news without sorting
        Route::get('/allnews',['uses' => 'GetNewsController@getAllNew']);

        // Retrieve popular news
        Route::get('/getMostPopularNews',['uses' => 'GetNewsController@getMostPopularNews']);

        // Retrieve perigon data
        Route::get('/getLatestNews',['uses' => 'GetNewsController@getLatestNews']);

        // Retrieve news with specified UUID
        Route::get('/new',['uses' => 'GetNewsController@getNewDetail']);

        // Get the default UUID for API NODATA
        Route::get('/getNoDataUUID',['uses' => 'GetNewsApiController@getNoDataUUID']);

        // Get data from API ad_Fontes_Media
        Route::get('/getAdFontesMedia',['uses' => 'GetNewsApiController@getAdFontesMedia']);

        // Get data from API MediaBiasFactCheck
        Route::get('/getMediaBiasFackCheck',['uses' => 'GetNewsApiController@getMediaBiasFactCheck']);

        // Get data from API FactMata
        Route::get('/getFactMata',['uses' => 'GetNewsApiController@getFactMata']);

        // Automatically create a UUID
        //    Route::get('/createUUID',['uses' => 'CreateSystemDataController@createUUID']);

    });

    // Related to account
    Route::group(['namespace' => 'Account'], function () {

        // Login
        Route::post('/login',['as' => 'login','uses'=>'AccountController@login']);
        // Register
        Route::post('/register',['uses'=>'AccountController@register']);
        // Get user information
        Route::get('/getUser',['as'=>'getUser','uses'=>'AccountController@getUser']);
        // Receive data from Google API and generate login token to return to the frontend
//        Route::get('/getGoogleData',['uses' => 'GetAccountDataController@getGoogleData']);

        // Add Google user
        Route::post('/signInWithGoogle',['uses' => 'AccountController@storeGoogleAccount']);

        // Check if the user has filled in the data and retrieve user information
        Route::get('/getGoogleUserRegistered',['uses' => 'AccountController@getGoogleAccountData']);

        // Edit user profile
        Route::post('/userEditProfile',['uses' => 'AccountController@userEditProfile']);

    });

});

