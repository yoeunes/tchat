<?php

namespace App\Core;

class Config
{
    public static function get($path = null)
    {
        if (empty($path)) {
            return false;
        }
        
        $config = $GLOBALS['config'];
        
        $path = explode('/', $path);
        
        foreach ($path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            }
        }
        
        return $config;
    }
}
