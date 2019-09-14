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

Route::get("/books/list","BookController@views");
Route::resource("books","BookController");
Route::post("/admin/books/{book}/upload","BookController@uploadImg")->name("books.upload");
