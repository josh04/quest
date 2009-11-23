<?php
/**
 * Description of code_cookie
 *
 * @author josh04
 * @package code_common
 */
class code_cookie {
   /**
    * player object
    *
    * @var code_player
    */
    public static $id = 0;

   /**
    * sets the player id
    *
    * @param int $id player id
    */
    public static function set_id($id) {
        self::$id = $id;
    }

   /**
    * sets a uid-specific cookie
    *
    * @param string $name cookie name
    * @param string $value cookie value
    * @param int $expire when does the cookie expire
    * @param <type> $path
    * @param <type> $domain
    * @param <type> $secure
    * @param <type> $httponly
    * @return bool
    */
    public static function set($name, $value=null, $expire=null, $path=null, $domain=null, $secure=null, $httponly=null) {
        return setcookie($name."_".self::$id, $value, $expire, $path, $domain, $secure, $httponly);
    }

   /**
    * gets a uid-specific cookie
    *
    * @param string $name cookie name
    */
    public static function get($name) {
        if (isset($_COOKIE[$name."_".self::$id])){
            return $_COOKIE[$name."_".self::$id];
        } else {
            return false;
        }
    }

}
?>
