<?php

namespace Tests\Http\Controllers;

use Jacksonsr45\RadiantPHP\Http\Controllers;

class UserController extends Controllers
{
    public function show($id)
    {
        $this->response->setBody($id);
    }

    public function view($id)
    {
        print_r($id);
    }
}
