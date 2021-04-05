<?php

//use App\Http\Controllers\TransactionController;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('refund',[ 
	'uses' => 'TransactionController@refund'
]);

$router->post('transact',[ 
	'uses' => 'TransactionController@transact'
]);

$router->post('cancel/{id}',[ 
	'uses' => 'TransactionController@cancel'
]);

