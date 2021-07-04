<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/6/2021
 * Time: 11:40 PM
 */

require_once('Constants.php');
require_once('Database.php');

class Device
{

    private $id;
    private $device_name;
    private $fingerprint;
    private $browser_info;
    private $system_info;
    private $created_at;
    private $updated_at;

    /**
     * Device constructor.
     * @param $id
     * @param $fingerprint
     * @param $browser_info
     * @param $system_info
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $device_name, $fingerprint, $browser_info, $system_info, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->fingerprint = $fingerprint;
        $this->device_name = $device_name;
        $this->browser_info = $browser_info;
        $this->system_info = $system_info;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    public static function getConnection()
    {
        return new Database(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }

    public static function getById($id)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM devices WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getByFingerPrint($fringerprint)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM devices WHERE fingerprint = '?'", array($fringerprint));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getDevices($limit = null, $offset = null, $text = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM devices ORDER BY id ASC';
        $params = null;

        $l = 30;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }

        if ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM devices WHERE `fingerprint` lIKE "%' . $text . '%" ORDER BY id ASC  LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM devices ORDER BY id ASC LIMIT ' . $l . ' OFFSET ' . $o;
        }


        $data = $database->executeQuery($query);


        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public static function convertRowToObject($row)
    {
        return new Device(
            $row['id'],
            $row['device_name'],
            $row['browser_fingerprint'],
            $row['user_browser'],
            $row['user_system_info_full'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public static function insert(Device $device)
    {
        $result = null;

        try {
            $database = self::getConnection();

            $result = $database->executeQuery("INSERT INTO devices (`device_name`, `fingerprint`, created_at) VALUES ('?', '?', '?')",
                array($device->device_name, $device->fingerprint, $device->created_at));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

//        var_dump('<br/>'.$result.' <br/>');
        return $result;

    }


    public static function updateAll(Device $device){

        try{
            $database = self::getConnection();

         $result =   $database->executeQuery("UPDATE devices SET browser_info = '?', system_info = '?', updated_at = '?' WHERE fingerprint = '?'",
                array($device->browser_info, $device->system_info, date("Y-m-d H:i:s"), $device->fingerprint));


        }catch (Exception $e){
            var_dump($e->getMessage());
        }

    }


    public static function update($Id, $olumn, $value)
    {
        if(is_string($value)){
            self::getConnection()->executeQuery("UPDATE devices SET " . $olumn . " = '?' , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }else{
            self::getConnection()->executeQuery("UPDATE devices SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }
    }


}