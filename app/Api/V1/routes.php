<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('/subscribe', 'Api\V1\Controllers\SubscriptionController@subscribe');
    $router->post('/subscribe/channel', 'Api\V1\Controllers\ChannelSubscriptionController@subscribe');
    $router->post('/message', 'Api\V1\Controllers\MessageController@send');
});
