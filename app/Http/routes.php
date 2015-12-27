<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
    Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function() {
        // ユーザー登録
        Route::post('users/create', ['as' => 'api.v1.user.create', 'uses' => 'UserController@create']);
        // ログイン
        Route::post('users/login', ['as' => 'api.v1.user.login', 'uses' => 'UserController@login']);
        // ログアウト
        Route::delete('users/logout', ['as' => 'api.v1.user.logout', 'uses' => 'UserController@logout']);
        //ユーザー削除
        Route::delete('users/delete/{user}', ['as' => 'api.v1.user.delete', 'uses' => 'UserController@delete']);
        // 記事一覧
        Route::get('articles', ['as' => 'api.v1.article.create', 'uses' => 'ArticleController@index']);
        // 記事作成
        Route::post('articles/create', ['as' => 'api.v1.article.create', 'uses' => 'ArticleController@create']);
    });
});
