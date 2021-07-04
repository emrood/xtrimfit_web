<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/3/2021
 * Time: 8:55 PM
 */

require_once('Constants.php');
require_once('Database.php');

class Rate
{

    private $id;
    private $name;
    private $value;
    private $created_at;
    private $updated_at;

    /**
     * Rate constructor.
     * @param $id
     * @param $name
     * @param $value
     * @param $creted_at
     * @param $updated_at
     */
    public function __construct($id, $name, $value, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    public static function getConnection()
    {
        return new Database(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getRates($limit = null, $offset = null, $text = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM rates ORDER BY id ASC';
        $params = null;

        $l = 10;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }

        if ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM rates WHERE `name` lIKE "%' . $text . '%" ORDER BY id ASC  LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM rates ORDER BY id ASC LIMIT ' . $l . ' OFFSET ' . $o;
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
        $data = $database->executeQuery("SELECT * FROM rates WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function count($column = null, $value = null)
    {
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if (!empty($column)) {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM rates WHERE " . $column . " = '" . $value . "'");
        } else {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM rates");
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }


    public static function convertRowToObject($row)
    {
        return new Rate(
            $row['id'],
            $row['name'],
            $row['value'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public static function insert(Rate $rate)
    {

        $result = null;

        try {
            $database = self::getConnection();

         $result = $database->executeQuery('INSERT INTO rates (`name`, `value`, created_at, updated_at) VALUES ("?", "?", "?", "?")',
                array($rate->name, $rate->value, $rate->created_at, $rate->updated_at));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $result;
    }


    public static function updateAll(Rate $rate)
    {

        try {
            $database = self::getConnection();

            $database->executeQuery('UPDATE rates SET `name` = "?", `value` = "?", updated_at = "?" WHERE id = "?"',
                array($rate->name, $rate->value, date("Y-m-d H:i:s"), $rate->id));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

    }


    public static function update($Id, $olumn, $value)
    {
        self::getConnection()->executeQuery("UPDATE rates SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?",
            array($value, date("Y-m-d H:i:s"), $Id));
    }

}