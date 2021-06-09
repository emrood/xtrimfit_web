<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 10:35 AM
 */

class Constants
{

    private static $url = 'localhost';
    private static $user_name = 'app';
    private static $password = 'P@$$W0rd'; // Local
//    private static $password = 'Xtrim1Fit2$_'; // Prod
    private static  $db_name = 'xtrim';

    private static $roles = ['Administrateur', 'Agent'];
    private static $id_types = ['CIN', 'NIF', 'Passeport'];
    private static $invoice_status = ['Pending', 'Paid', 'Unpaid'];
    private static $transaction_types = ['Retrait', 'Depot'];


    /**
     * @return string
     */
    public static function getUrl()
    {
        return self::$url;
    }

    /**
     * @return string
     */
    public static function getUserName()
    {
        return self::$user_name;
    }

    /**
     * @return string
     */
    public static function getPassword()
    {
        return self::$password;
    }

    /**
     * @return string
     */
    public static function getDbName()
    {
        return self::$db_name;
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return self::$roles;
    }

    /**
     * @return array
     */
    public static function getIdTypes()
    {
        return self::$id_types;
    }

    /**
     * @return array
     */
    public static function getInvoiceStatus()
    {
        return self::$invoice_status;
    }







}