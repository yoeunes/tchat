<?php

namespace App\Core;

class Hash
{
    public static function salt($length = null)
    {
        return md5(uniqid());
    }
    
    public static function unique()
    {
        return self::make(uniqid());
    }
    
    public static function make($string, $salt = '')
    {
        return hash('sha256', $string.$salt);
    }
}
