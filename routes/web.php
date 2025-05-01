<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/average', function () {
    return view('average');
});

Route::get('/difficult', function () {
    return view('difficult');
});
