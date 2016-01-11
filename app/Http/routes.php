<?php

/**
 * フロントアプリ
 */

// ログイン画面
Route::get('/login', function () {
    // resources/views/front/login.phpを描画する
    return view('front.login');
});

// 一覧画面
Route::get('/', function () {
    return view('front.index');
});

/**
 * API
 */
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
    Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function() {
        // ユーザー登録
        Route::post('auth/register', ['as' => 'api.v1.auth.register', 'uses' => 'AuthController@register']);
        // ログイン
        Route::post('auth/login', ['as' => 'api.v1.auth.login', 'uses' => 'AuthController@login']);
        // ログイン済みかチェックする
        Route::get('auth/check', ['as' => 'api.v1.auth.check', 'uses' => 'AuthController@check']);
        // ログアウト
        Route::delete('auth/logout', ['as' => 'api.v1.auth.logout', 'uses' => 'AuthController@logout']);
        // ユーザー詳細
        Route::get('users/{user}', ['as' => 'api.v1.user.show', 'uses' => 'UserController@show']);
        // ユーザー削除
        Route::delete('users/{user}/delete', ['as' => 'api.v1.user.delete', 'uses' => 'UserController@delete']);
        // 記事一覧
        Route::get('articles', ['as' => 'api.v1.article.create', 'uses' => 'ArticleController@index']);
        // 記事検索
        Route::get('articles/search', ['as' => 'api.v1.article.search', 'uses' => 'ArticleController@search']);
        // 記事詳細
        Route::get('articles/{article}', ['as' => 'api.v1.article.show', 'uses' => 'ArticleController@show']);
        // 記事投稿
        Route::post('articles/create', ['as' => 'api.v1.article.create', 'uses' => 'ArticleController@create']);
        // 記事更新
        Route::put('articles/{article}/update', ['as' => 'api.v1.article.update', 'uses' => 'ArticleController@update']);
        // 記事削除
        Route::delete('articles/{article}/delete', ['as' => 'api.v1.article.delete', 'uses' => 'ArticleController@delete']);
        // 記事クリップ
        Route::post('articles/{article}/clip', ['as' => 'api.v1.article.clip', 'uses' => 'ArticleController@clip']);
        // 記事クリップを外す
        Route::delete('articles/{article}/unclip', ['as' => 'api.v1.article.unclip', 'uses' => 'ArticleController@unclip']);
        // コメント投稿
        Route::post('articles/{article}/comments/create', ['as' => 'api.v1.comments.create', 'uses' => 'CommentController@create']);
        // コメント更新
        Route::put('comments/{comment}/update', ['as' => 'api.v1.comments.update', 'uses' => 'CommentController@update']);
        // コメント削除
        Route::delete('comments/{comment}/delete', ['as' => 'api.v1.comments.delete', 'uses' => 'CommentController@delete']);
    });
});

Route::get('/test', function() {
    return view('test');
});
