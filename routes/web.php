<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form/{id}', [PublicFormController::class, 'show'])->name('form.show');

