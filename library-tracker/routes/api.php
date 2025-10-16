<?php

use App\Support\AdvancedRoute;

Route::group([
    'as'     => '/v1',
    'prefix' => '/v1',
], function () {
    AdvancedRoute::controllers([
        'authors' => \App\Http\Controllers\API\AuthorAPIController::class,
        'books'   => \App\Http\Controllers\API\BookAPIController::class,
        'loans'   => \App\Http\Controllers\API\LoanAPIController::class,
        'users'   => \App\Http\Controllers\API\UserAPIController::class,
    ]);
});
