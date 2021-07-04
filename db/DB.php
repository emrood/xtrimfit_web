<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 10:28 AM
 */

class DB
{

    public static function connect()
    {
        $con = mysqli_connect(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
        if (mysqli_connect_errno()) {
            return "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {
            return $con;
        }
    }

}

?>