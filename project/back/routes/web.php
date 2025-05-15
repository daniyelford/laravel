<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Nwidart\Modules\Facades\Module;


Route::get('/', function () {
    return Inertia::render('Index');
});
// Module::load('User');
