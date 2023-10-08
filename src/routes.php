<?php

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Response;
use Jacksonsr45\RadiantPHP\Http\Route;

class UserController
{
    public function __construct(
        private ResponseInterface $response
    ) {
    }

    public function index(): ResponseInterface
    {
        return $this->response->withStatus(200)->write('Hello World');
    }
}

Route::get('/user', [UserController::class, 'index']);
