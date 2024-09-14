<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);
Route::get('authors/{id}/books', [ BookController::class, 'getBookByAuthor' ])->name('books.by.author');

