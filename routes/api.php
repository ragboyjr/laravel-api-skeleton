<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$router->get('/_status', function(Request $req, Illuminate\Foundation\Application $app) {
    return Http\response(200, [
        'server_status' => 'OK',
        'date' => date('r'),
        'env' => getenv('APP_ENV'),
        'debug' => (bool) getenv('APP_DEBUG'),
        'githead' => @file_get_contents($app->basePath() . '/public/GITHEAD'),
    ]);
});
