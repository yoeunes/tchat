<?php

namespace App\Core;

class Input
{
    public static function exists($type = 'post')
    {
        switch (strtolower(strval($type))) {
            case 'post' :
                return !empty($_POST);
                break;
            case 'get'  :
                return !empty($_GET);
                break;
            case 'file':
                return !empty($_FILES);
                break;
            case 'session':
                return !empty($_SESSION);
                break;
            case 'cookie':
                return !empty($_COOKIE);
                break;
            default:
                return false;
                break;
        }
    }
    
    public static function get($item, $type = null, $default = null, $arraytype = null)
    {
        return self::get_param('GET', $item, $type, $default, $arraytype);
    }
    
    private static function get_param($methode = 'GET', $item, $type = null, $default = null, $arraytype = null)
    {
        $output  = null;
        $methode = strtolower(strval($methode));
        if ($methode == 'post' && isset($_POST[$item])) {
            $output = $_POST[$item];
        } else {
            if ($methode == 'get' && isset($_GET[$item])) {
                $output = $_GET[$item];
            } else {
                if ($methode == 'session' && isset($_SESSION[$item])) {
                    $output = $_SESSION[$item];
                } else {
                    if ($methode == 'cookie' && isset($_COOKIE[$item])) {
                        $output = $_COOKIE[$item];
                    } else {
                        if ($methode == 'files' && isset($_FILES[$item])) {
                            return $_FILES[$item];
                        } else {
                            $output = $default;
                        }
                    }
                }
            }
        }
        
        if ($output === null) {
            return null;
        }
        
        switch (strtolower(strval($type))) {
            case 'int'    :
                $output = intval($output);
                break;
            case 'uint'   :
                $output = intval($output);
                $output = ($output >= 0) ? $output : 0;
                break;
            case 'float'  :
                $output = floatval($output);
                break;
            case 'double' :
                $output = doubleval($output);
                break;
            case 'boolean':
                $output = ($output === true or strtolower($output) == 'true') ? true : false;
                break;
            case 'char':
                $output = strval($output);
                $output = substr($output, 0, 1);
                break;
            case 'none' :
                return $output;
                break;
            case 'string' :
                $output = static::escape($output);
                break;
            case 'array'  :
                if ($output && is_array($output)) {
                    foreach ($output as $key => $value) {
                        switch (strtolower(strval($arraytype))) {
                            case 'int'    :
                                $output[$key] = intval($value);
                                break;
                            case 'uint':
                                $output[$key] = intval($value);
                                $output[$key] = ($output[$key] >= 0) ? $output[$key] : 0;
                                break;
                            case 'float'  :
                                $output[$key] = floatval($value);
                                break;
                            case 'double' :
                                $output[$key] = doubleval($value);
                                break;
                            case 'boolean':
                                $output[$key] = ($output[$key] === true or strtolower($output[$key]) == 'true') ? true : false;
                                break;
                            case 'char':
                                $output[$key] = strval($output[$key]);
                                $output[$key] = substr($output[$key], 0, 1);
                                break;
                            case 'string'  :
                                $output[$key] = static::escape($value);
                                break;
                            case 'none':
                                $output[$key] = $value;
                                break;
                            default       :
                                $output[$key] = static::escape($value);
                                break;
                        }
                    }
                }
                break;
            default       :
                $output = static::escape($output);
                break;
        }
        
        return $output;
    }
    
    public static function escape($item)
    {
        $item = strval($item);
        $item = trim($item);
//        $item = urlencode($item);
        $item = htmlspecialchars($item);
        
        return $item;
    }
    
    public static function post($item, $type = null, $default = null, $arraytype = null)
    {
        return self::get_param('POST', $item, $type, $default, $arraytype);
    }
    
    public static function session($item, $type = null, $default = null, $arraytype = null)
    {
        return self::get_param('SESSION', $item, $type, $default, $arraytype);
    }
    
    public static function cookie($item, $type = null, $default = null, $arraytype = null)
    {
        return self::get_param('COOKIE', $item, $type, $default, $arraytype);
    }
    
    public static function files($item)
    {
        return self::get_param('FILES', $item);
    }
    
    public static function uri()
    {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
    }
    
    public static function host()
    {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
    }
    
    public static function script()
    {
        return isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : null;
    }
    
    public static function query()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
    }
    
    public static function methode()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
    }
    
    public static function ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                if (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                } else {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    } else {
                        if (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        } else {
                            if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            } else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }
        
        return $ipaddress;
    }
    
    public static function is_secure()
    {
        return (static::protocol() === 'https') ? true : false;
    }
    
    public static function protocol()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) {
            return 'https';
        } else {
            if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
                return 'https';
            } else {
                if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == "https") {
                    return 'https';
                } else {
                    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                        return 'https';
                    } else {
                        return 'http';
                    }
                }
            }
        }
    }
    
    public static function is_ajax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true : false;
    }
    
    public static function user_agent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }
    
    public static function file_name()
    {
        $filename = '';
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $filename = $_SERVER['SCRIPT_FILENAME'];
        } else {
            if (isset($_SERVER['SCRIPT_NAME'])) {
                $filename = $_SERVER['SCRIPT_NAME'];
            } else {
                if (isset($_SERVER['PHP_SELF'])) {
                    $filename = $_SERVER['PHP_SELF'];
                }
            }
        }
        
        return basename($filename, ".php");
    }
}
