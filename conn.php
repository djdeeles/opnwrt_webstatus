<?php
require_once 'config.php';
class DB {
    private static $link = null;
    public static function getConnectionResource() {
        //In that way we "cache" our $link variable so that creating new connection 
        //for each function call won't be necessary
        if (self::$link === null) {
            //Define your connection parameter here
			global $dbhost, $dbuser, $dbpassword, $database;
            self::$link = new mysqli($dbhost, $dbuser, $dbpassword, $database);
        }
        return self::$link;
    }
}
?>