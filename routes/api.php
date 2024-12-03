<?php

use Illuminate\Support\Facades\Route;

Route::prefix('news')->group(__DIR__ . '/api/news/index.php');