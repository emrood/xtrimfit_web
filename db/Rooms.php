<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/8/2021
 * Time: 11:37 PM
 */

require_once('Constants.php');
require_once('Database.php');


class Rooms
{

    private $id;
    private $name;
    private $color;
    private $price;
    private $customer_discount;
    private $available;
    private $created_at;
    private $updated_at;

    /**
     * Rooms constructor.
     * @param $id
     * @param $name
     * @param $color
     * @param $price
     * @param $customer_discount
     * @param $available
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $name, $color, $price, $customer_discount, $available, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->price = $price;
        $this->customer_discount = $customer_discount;
        $this->available = $available;
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
        $data = $database->executeQuery("SELECT * FROM rooms WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }


    public static function getRooms($limit = null, $offset = null, $available = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM rooms ORDER BY `name` ASC';
        $params = null;

        $l = 100;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }

        if ($available !== null && !empty($available)) {
            $query = 'SELECT * FROM rooms WHERE `available` = '. $available .' ORDER BY `name` ASC  LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM rooms ORDER BY `name` ASC LIMIT ' . $l . ' OFFSET ' . $o;
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

    public static function count($column = null, $value = null)
    {
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if (!empty($column)) {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM rooms WHERE " . $column . " = '" . $value . "'");
        } else {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM rooms");
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function convertRowToObject($row)
    {
        return new Rooms(
            $row['id'],
            $row['name'],
            $row['color'],
            $row['price'],
            $row['customer_discount'],
            $row['available'],
            $row['created_at'],
            $row['updated_at']
        );
    }

//    public static function insert(Rate $rate)
//    {
//
//        $result = null;
//
//        try {
//            $database = self::getConnection();
//
//            $result = $database->executeQuery('INSERT INTO rates (`name`, `value`, created_at, updated_at) VALUES ("?", "?", "?", "?")',
//                array($rate->name, $rate->value, $rate->created_at, $rate->updated_at));
//
//        } catch (Exception $e) {
//            var_dump($e->getMessage());
//        }
//
//        return $result;
//    }
//
//
//    public static function updateAll(Rate $rate)
//    {
//
//        try {
//            $database = self::getConnection();
//
//            $database->executeQuery('UPDATE rates SET `name` = "?", `value` = "?", updated_at = "?" WHERE id = "?"',
//                array($rate->name, $rate->value, date("Y-m-d H:i:s"), $rate->id));
//
//        } catch (Exception $e) {
//            var_dump($e->getMessage());
//        }
//
//    }


    public static function update($Id, $olumn, $value)
    {
        self::getConnection()->executeQuery("UPDATE rooms SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?",
            array($value, date("Y-m-d H:i:s"), $Id));
    }


}