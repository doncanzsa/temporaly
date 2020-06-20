<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('books/{dt?}', 'BookController@index');
Route::group(['prefix' => 'book'], function () {
    Route::post('add', 'BookController@add');
    Route::get('edit/{id}', 'BookController@edit');
    Route::post('update/{id}', 'BookController@update');
    Route::delete('delete/{id}', 'BookController@delete');
});

Route::group(['prefix' => 'subjects'], function () {
    Route::get('/',                 'SubjectController@index');
    Route::post('save',             'SubjectController@save');
    Route::delete('delete/{id}',    'SubjectController@delete');
});

Route::group(['prefix' => 'lessons'], function () {
    Route::get('',                  'LessonController@index');
    Route::get('subject/{id}',      'LessonController@getBySubjectId');
    Route::get('show/{id}',         'LessonController@show');
    Route::post('save',             'LessonController@save');
    Route::delete('delete/{id}',    'LessonController@delete');
});