<?php
namespace Nayjest\Menu\Facades;

use Config;
use Nayjest\Menu\Exception\NoConfigurationException;
use Nayjest\Menu\MenuSet;

class Menu
{
    public static function make($config_key)
    {
        try {
            return new MenuSet($config_key);
        } catch(NoConfigurationException $e) {
            return null;
        }
    }

    public static function exists($config_key)
    {
        return Config::has($config_key);
    }
} 