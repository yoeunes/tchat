<?php

namespace App\Core;

use PDO;

class DB extends PDO
{
    private $error;
    
    private $sql;
    
    private $bind;
    
    private $errorCallbackFunction;
    
    private $errorMsgFormat;
    
    protected static $_instance;
    
    public function __construct($dsn, $user = "", $passwd = "")
    {
        $options = [
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        ];
        
        try {
            parent::__construct($dsn, $user, $passwd, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self('mysql:host=' . Config::get('database/host') . ';dbname=' . Config::get('database/database') . ';port=' . Config::get('database/port') . ';charset=' . Config::get('database/charset'), Config::get('database/username'), Config::get('database/password'));
            
            self::$_instance->setErrorCallbackFunction(function ($error) {
                echo $error;
            });
        }
        
        return self::$_instance;
    }
    
    public function delete($table, $where, $bind = "")
    {
        $sql = "DELETE FROM ".$table." WHERE ".$where.";";
        $this->run($sql, $bind);
    }
    
    public function run($sql, $bind = "")
    {
        $this->sql   = trim($sql);
        $this->bind  = $this->cleanup($bind);
        $this->error = "";
        
        try {
            $pdostmt = $this->prepare($this->sql);
            if ($pdostmt->execute($this->bind) !== false) {
                if (preg_match("/^(".implode("|", ["select", "describe", "pragma"]).") /i", $this->sql)) {
                    return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
                } elseif (preg_match("/^(".implode("|", ["delete", "insert", "update"]).") /i", $this->sql)) {
                    return $pdostmt->rowCount();
                }
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->debug();
            
            return false;
        }
    }
    
    private function cleanup($bind)
    {
        if (!is_array($bind)) {
            if (!empty($bind)) {
                $bind = [$bind];
            } else {
                $bind = [];
            }
        }
        
        return $bind;
    }
    
    private function debug()
    {
        if (!empty($this->errorCallbackFunction)) {
            $error = ["Error" => $this->error];
            if (!empty($this->sql)) {
                $error["SQL Statement"] = $this->sql;
            }
            if (!empty($this->bind)) {
                $error["Bind Parameters"] = trim(print_r($this->bind, true));
            }
            
            $backtrace = debug_backtrace();
            if (!empty($backtrace)) {
                foreach ($backtrace as $info) {
                    if ($info["file"] != __FILE__) {
                        $error["Backtrace"] = $info["file"]." at line ".$info["line"];
                    }
                }
            }
            
            $msg = "";
            if ($this->errorMsgFormat == "html") {
                if (!empty($error["Bind Parameters"])) {
                    $error["Bind Parameters"] = "<pre>".$error["Bind Parameters"]."</pre>";
                }
                $css = trim(file_get_contents(dirname(__FILE__)."/error.css"));
                $msg .= '<style type="text/css">'."\n".$css."\n</style>";
                $msg .= "\n".'<div class="db-error">'."\n\t<h3>SQL Error</h3>";
                foreach ($error as $key => $val) {
                    $msg .= "\n\t<label>".$key.":</label>".$val;
                }
                $msg .= "\n\t</div>\n</div>";
            } elseif ($this->errorMsgFormat == "text") {
                $msg .= "SQL Error\n".str_repeat("-", 50);
                foreach ($error as $key => $val) {
                    $msg .= "\n\n$key:\n$val";
                }
            }
            
            $func = $this->errorCallbackFunction;
            $func($msg);
        }
    }
    
    public function insert($table, $info)
    {
        $fields = $this->filter($table, $info);
        $sql    = "INSERT INTO ".$table." (".implode($fields, ", ").") VALUES (:".implode($fields, ", :").");";
        $bind   = [];
        foreach ($fields as $field) {
            $bind[":$field"] = $info[$field];
        }
        
        return $this->run($sql, $bind);
    }
    
    private function filter($table, $info)
    {
        $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver == 'sqlite') {
            $sql = "PRAGMA table_info('".$table."');";
            $key = "name";
        } elseif ($driver == 'mysql') {
            $sql = "DESCRIBE ".$table.";";
            $key = "Field";
        } else {
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '".$table."';";
            $key = "column_name";
        }
        
        if (false !== ($list = $this->run($sql))) {
            $fields = [];
            foreach ($list as $record) {
                $fields[] = $record[$key];
            }
            
            return array_values(array_intersect($fields, array_keys($info)));
        }
        
        return [];
    }
    
    public function first($table, $where = "", $bind = "", $fields = "*")
    {
        $result = $this->select($table, $where, $bind, $fields);
        
        return ($result !== null && is_array($result) && isset($result[0])) ? $result[0] : $result;
    }
    
    public function select($table, $where = "", $bind = "", $fields = "*")
    {
        $sql = "SELECT ".$fields." FROM ".$table;
        if (!empty($where)) {
            $sql .= " WHERE ".$where;
        }
        $sql .= ";";
        
        return $this->run($sql, $bind);
    }
    
    public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat = "html")
    {
        //Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
        if (is_string($errorCallbackFunction) && in_array(strtolower($errorCallbackFunction), ["echo", "print"])) {
            $errorCallbackFunction = "print_r";
        }
        
        if ((is_string($errorCallbackFunction) && function_exists($errorCallbackFunction)) || is_callable($errorCallbackFunction)) {
            $this->errorCallbackFunction = $errorCallbackFunction;
            if (!in_array(strtolower($errorMsgFormat), ["html", "text"])) {
                $errorMsgFormat = "html";
            }
            $this->errorMsgFormat = $errorMsgFormat;
        }
    }
    
    public function update($table, $info, $where, $bind = "")
    {
        $fields    = $this->filter($table, $info);
        $fieldSize = sizeof($fields);
        
        $sql = "UPDATE ".$table." SET ";
        for ($f = 0; $f < $fieldSize; ++$f) {
            if ($f > 0) {
                $sql .= ", ";
            }
            $sql .= $fields[$f]." = :update_".$fields[$f];
        }
        $sql .= " WHERE ".$where.";";
        
        $bind = $this->cleanup($bind);
        foreach ($fields as $field) {
            $bind[":update_$field"] = $info[$field];
        }
        
        return $this->run($sql, $bind);
    }
}

