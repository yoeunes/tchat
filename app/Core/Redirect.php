<?php

namespace App\Core;

class Redirect
{
    public static function to($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        exit();
                        break;
                    case 301:
                        header('HTTP/1.1 301 Moved Permanently');
                        exit();
                        break;
                }
            }
            header('Location:'.$location);
            exit();
        }
    }
}
