<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Nwidart\Modules\Facades\Module;


Route::get('/', function () {
    return Inertia::render('Index');
});
// Module::load('User');
Route::middleware('auth.user')->group(function () {
    Route::get('/dashboard',function(){
        return 'dashboard setting';
    } 
    // [DashboardController::class, 'index']
    );
});

