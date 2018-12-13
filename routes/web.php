<?php

use Illuminate\Support\Facades\Route;

Route::get('/page', 'DigitalCloud\PageTool\Http\Controllers\PageController@index');
Route::get('/page/{id}', 'DigitalCloud\PageTool\Http\Controllers\PageController@detail');
Route::get('page-builder', 'DigitalCloud\PageTool\Http\Controllers\PageBuilderController@index');
Route::get('page-builder/page/{id}', 'DigitalCloud\PageTool\Http\Controllers\PageBuilderController@page');
