<?php
/*************************************************************************************/
/* */
/* Thelia */
/* */
/* Copyright (c) OpenStudio */
/* email : thelia@openstudio.fr */
/* web : http://www.openstudio.fr */
/* */
/* This program is free software; you can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License */
/* */
/* This program is distributed in the hope that it will be useful, */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the */
/* GNU General Public License for more details. */
/* */
/* You should have received a copy of the GNU General Public License */
/*    along with this program.  If not, see <http://www.gnu.org/licenses/>. */
/* */
/**
 * **********************************************************************************
 */
require_once (__DIR__ . "/../client/config_thelia.php");

class StaticConnection
{

    public static $db_handle = - 1;

    /**
     *
     * @var PDO
     */
    public static $pdo = null;
/**
 * 
 * @return PDO
 */
    public static function getHandle()
    {
        if (self::$db_handle == - 1) {
            try {
                $dsn = "mysql:host=" . THELIA_BD_HOST . "; dbname=" . THELIA_BD_NOM . ";";
                $pdo = new PDO($dsn, THELIA_BD_LOGIN, THELIA_BD_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->query("SET NAMES UTF8");
                self::$pdo = $pdo;
                self::$db_handle = 1;
            } catch (PDOException $e) {
                die("Connexion à la base de données impossible<br/><pre>" . $e->getTraceAsString() . "</pre>");
            }
        }
        return self::$db_handle;
    }
    
    public static function getPDO() {
        self::getHandle();
        return self::$pdo;
    }
}

class Cnx
{

    public $table = "";

    /**
     *
     * @var string
     */
    public $link = "";

    function __construct()
    {
        $this->link = StaticConnection::getHandle();
    }

    /**
     *
     * @return PDO
     */
    public function getPDO()
    {
        StaticConnection::getHandle();
        return StaticConnection::$pdo;
    }

    /**
     *
     * @param string $query
     * @param boolean $exception
     * @throws Exception
     * @return PDOStatement
     */
    public function query($query, $exception = false)
    {
        $pdo = $this->getPDO();
        try {
            $stmt = $pdo->query($query);
        } catch (PDOException $e) {
            if ($exception === true)
                throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
        return $stmt;
    }
    
    public function prepare($query) {
        $pdo = $this->getPDO();
        try {
            $stmt = $pdo->prepare($query);
        } catch (PDOException $e) {
            if ($exception === true)
                throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
        return $stmt;
    }

    public function query_liste($query, $classname = stdClass::class)
    {
        return $this->getPDO()->query($query)->fetchAll(PDO::FETCH_CLASS, $classname);
    }

    /**
     *
     * @param PDOStatement $dbhandle
     * @param mixed $classname
     * @return string
     */
    public function fetch_object($dbhandle, $classname = stdClass::class, $params = null)
    {
        if (! $classname)
            $classname = stdClass::class;
        if(is_array($params) && count($params))
            $obj = $dbhandle->fetchObject($classname, $params);
        else 
            $obj = $dbhandle->fetchObject($classname);
        return $obj;
    }

    /**
     *
     * @param PDOStatement $dbhandle
     * @return int
     */
    public function num_rows($dbhandle)
    {
        return $dbhandle->rowCount();
    }

    /**
     *
     * @param PDOStatement $dbhandle
     * @param number $row
     * @param number $field
     * @return string
     */
    public function get_result($dbhandle, $row = 0, $field = 0)
    {
        $result = $dbhandle->fetchColumn(intval($field));
        if ($result === false) {
            $result = 0;
        }
        return $result;
    }

    public function escape_string($value)
    {
        if (get_magic_quotes_gpc())
            $value = stripslashes($value);
        return substr($this->getPDO()->quote($value), 1, - 1);
    }

    public function insert_id()
    {
        return $this->getPDO()->lastInsertId();
    }

    public function get_error()
    {
        $e = $this->getPDO()->errorInfo();
        if (isset($e[2]))
            return $e[2];
        return "";
    }
}
?>