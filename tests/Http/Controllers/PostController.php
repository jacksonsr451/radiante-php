<?php

namespace Tests\Http\Controllers;

use Jacksonsr45\RadiantPHP\Http\Controllers;

class PostController extends Controllers
{
    public function show($id, $slug)
    {
        print_r($id);
        print_r($slug);
        $this->response->setBody($id, $slug);
    }
}
