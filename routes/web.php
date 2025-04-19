<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpinDisplayController;
use App\Models\Form;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/spin-display/{form}', function (Form $form) {
    return view('spin-display', ['form' => $form]);
})->name('spin-display');



