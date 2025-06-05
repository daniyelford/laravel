<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PythonExecController;

Route::post('/run-telegram', [PythonExecController::class, 'run_telegram']);

