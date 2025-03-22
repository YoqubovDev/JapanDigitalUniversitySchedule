<?php

use Illuminate\Support\Facades\Route;
use Sentry\State\HubInterface;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/sentry-test', function (HubInterface $sentry) {
    $sentry->captureException(new Exception('Bu Laravel uchun test xatosi!'));
    return 'Xato Sentry-ga yuborildi!';
});
