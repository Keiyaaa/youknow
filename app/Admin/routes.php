<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('municipalities', MunicipalityController::class);
    $router->resource('members', MemberController::class);
    $router->resource('questions', QuestionController::class);
    $router->resource('tags', TagController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('search-tags', SearchTagController::class);

    $router->resource('congressional-minutes', CongressionalMinutesController::class);
});
