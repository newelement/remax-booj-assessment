<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReadingListController;

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

Route::middleware('auth:web')->get('/search-comics', [ReadingListController::class, 'search']);
Route::middleware('auth:web')->get('/comics', [ReadingListController::class, 'getReadingList']);
Route::middleware('auth:web')->post('/comics', [ReadingListController::class, 'addToReadingList']);
Route::middleware('auth:web')->delete('/comics', [ReadingListController::class, 'removeFromReadingList']);
Route::middleware('auth:web')->post('/comics/sort', [ReadingListController::class, 'orderReadingList']);
