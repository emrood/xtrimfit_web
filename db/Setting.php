<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/4/2021
 * Time: 12:54 AM
 */

require_once('Constants.php');
require_once('Database.php');

class Setting
{

    private $id;
    private $setting;
    private $value;
    private $created_at;
    private $updated_at;

    /**
     * Setting constructor.
     * @param $id
     * @param $setting
     * @param $value
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $setting, $value, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->setting = $setting;
        $this->value = $value;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    public static function getConnection()
    {
        return new Database(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getSettings($limit = null, $offset = null, $text = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM settings ORDER BY id ASC';
        $params = null;

        $l = 20;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }

        if ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM settings WHERE `setting` lIKE "%' . $text . '%" ORDER BY id ASC  LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM settings ORDER BY id ASC LIMIT ' . $l . ' OFFSET ' . $o;
        }

//        var_dump($query.' </br>');

        $data = $database->executeQuery($query);

//        var_dump($data);

        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public static function getById($id)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM settings WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function count($column = null, $value = null)
    {
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if (!empty($column)) {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM settings WHERE " . $column . " = '" . $value . "'");
        } else {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM settings");
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }


    public static function convertRowToObject($row)
    {
        return new Setting(
            $row['id'],
            $row['setting'],
            $row['value'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public static function insert(Setting $setting)
    {

        try {
            $database = self::getConnection();

//            var_dump("IN_INSERT </br>");
            $database->executeQuery('INSERT INTO settings (`setting`, `value`, created_at, updated_at) VALUES ("?", "?", "?", "?")',
                array($setting->name, $setting->price, $setting->created_at, $setting->updated_at));

//            var_dump("AFTER_INSERT </br>");
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

    }


    public static function updateAll(Setting $setting)
    {

        try {
            $database = self::getConnection();

            $database->executeQuery('UPDATE settings SET `setting` = "?", `value` = "?", updated_at = "?" WHERE id = "?"',
                array($setting->setting, $setting->value, date("Y-m-d H:i:s"), $setting->id));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

    }


    public static function update($Id, $olumn, $value)
    {

        if(is_string($value)){
            self::getConnection()->executeQuery("UPDATE settings SET " . $olumn . " = '?' , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }else{
            self::getConnection()->executeQuery("UPDATE settings SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }
//        self::getConnection()->executeQuery("UPDATE settings SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?",
//            array($value, date("Y-m-d H:i:s"), $Id));
    }

}