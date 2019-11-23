<?php 

class UserHook {
    
    /**
     * 
     * @var mixed
     */
    static $ADMIN_PWD = "dev1234";
    /**
     * 
     * @var mixed
     */
    static $ADMIN_LOGIN = "devadmin";
    /**
     * 
     * @var mixed
     */
    static $USER_LOGIN = "dev@ketmie.com";
    /**
     * 
     * @var mixed
     */
    static $USER_PWD = "dev1234";
    /**
     * 
     * @return Administrateur
     */
    static function getDevAdmin() {
        return self::getAdmin(self::$ADMIN_LOGIN, self::$ADMIN_PWD);    
    }
    /**
     * 
     * @param mixed $login
     * @param mixed $password
     * @return Administrateur
     */
    static function getAdmin($login, $password) {
        $admin = new Administrateur();
        $admin->charger($login, $password);
        return $admin;
    }
    /**
     * 
     * @return Administrateur
     */
    static function getDevUser() {
        return self::getUser(self::$USER_LOGIN, self::$USER_PWD);
    }
    
    /**
     * 
     * @param mixed $login
     * @param mixed $password
     * @return Client
     */
    static function getUser($login, $password) {
        $user = new Client();
        $user->charger($login, $password);
        return $user;
    }
}

?>