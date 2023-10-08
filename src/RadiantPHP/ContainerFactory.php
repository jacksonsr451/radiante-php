<?php

namespace Jacksonsr45\RadiantPHP;

use DI\Container;
use DI\ContainerBuilder;

class ContainerFactory
{
    public static function build(): Container
    {
        $container = new ContainerBuilder();


        return $container->build();
    }
}
