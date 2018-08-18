<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/15/18
 * Time: 1:33 PM
 */
use App\Util\ResponseMessages;
use App\Util\ResponseCodes;

$container = $app->getContainer();

$container["phpErrorHandler"] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $response->withStatus(500)->withJson(['status' => 'error', 'message' => ResponseMessages::INTERNAL_SERVER_ERROR, 'code' => ResponseCodes::INTERNAL_SERVER_ERROR, 'trace' => $exception->getMessage()]);
    };
};

$container["notFoundHandler"] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $response->withStatus(404)->withJson(['status' => 'error', 'message' => sprintf(ResponseMessages::NOT_FOUND, 'the route'), 'code' => ResponseCodes::NOT_FOUND]);
    };
};