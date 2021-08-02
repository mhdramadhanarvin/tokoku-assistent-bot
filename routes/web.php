<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'TelegramController@index');
Route::get('/local', function () {
    return response()
        ->view('local');
    // ->header('X-Frame-Options', 'sameorigin');
});

Route::post('42yUojv1YQPOssPEpn5i3q6vjdhh7hl7djVWDIAVhFDRMAwZ1tj0Og2v4PWyj4PZ/webhook', 'TelegramController@webhook');
