<?php

class PDOHook
{
    /**
     * 
     * @var PDO
     */
    private static $_pdo;
    /**
     * 
     * @var Cnx
     */
    private static $_cnx;
    
    /**
     * 
     * @return PDO
     */
    static function getPDO(){
        self::_init();
        return self::$_pdo;
    }
    
    static function getCnx(){
        self::_init();
        return self::$_cnx;
    }
    
    
    private static function _init() {
        if(! self::$_pdo) {
            self::$_cnx = new Cnx();
            self::$_pdo = self::$_cnx->getPDO();
        }
    }
    
    
}

