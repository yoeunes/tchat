<?php

namespace App\Core;

class Session
{
    public static function destroy()
    {
        static::start();
        
        if (isset($_SESSION)) {
            session_destroy();
            $_SESSION = [];
        }
    }
    
    public static function start()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
    
    public static function get($name)
    {
        static::start();
        
        return self::exists($name) ? $_SESSION[$name] : null;
    }
    
    public static function exists($name)
    {
        static::start();
        
        return isset($_SESSION[$name]);
    }
    
    public static function delete($name)
    {
        static::start();
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
    
    public static function put($name, $value)
    {
        static::start();
        
        return $_SESSION[$name] = $value;
    }
}
