<?php

use App\Http\Controllers\BookController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('external-books', [BookController::class, 'fetchExternalBooks']);
Route::post('v1/books', [BookController::class, 'createBook']);
Route::get('v1/books', [BookController::class, 'listBooks']);
Route::patch('/v1/books/{id}', [BookController::class, 'updateBooks']);
Route::delete('/v1/books/{id}', [BookController::class, 'deleteBooks']);
Route::get('/v1/books/{id}', [BookController::class, 'showBook']);
