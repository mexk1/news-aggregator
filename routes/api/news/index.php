<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NewsController::class, 'search']);
Route::get('{id}', [NewsController::class, 'details']);