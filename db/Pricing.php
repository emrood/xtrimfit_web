<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/30/2021
 * Time: 12:29 PM
 */

require_once('Constants.php');
require_once('Database.php');

class Pricing
{

    private $id;
    private $name;
    private $price;
    private $billing;
    private $created_at;
    private $updated_at;

    /**
     * Pricing constructor.
     * @param $id
     * @param $name
     * @param $price
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $name, $price, $billing, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->billing = $billing;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    public static function getConnection(){
        return new Database(Constants::getUrl(),Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getPricings($limit = null, $offset = null, $text = null){
        $database = self::getConnection();

        $query = 'SELECT * FROM pricings ORDER BY id ASC';
        $params =  null;

        if($limit !== null && $offset != null){
            $query = 'SELECT * FROM pricings ORDER BY id ASC LIMIT '.$limit.' OFFSET '.$offset;
        }elseif($text !== null && !empty($text)){
            $query = 'SELECT * FROM pricings WHERE `name` lIKE "%'.$text.'%" ORDER BY id ASC  LIMIT '.$limit.' OFFSET '.$offset;
        }

//        var_dump($query.' </br>');

        $data = $database->executeQuery($query);

//        var_dump($data);

        $result = array();
        while ($row = mysqli_fetch_array($data,MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }


    public static function getByType($type){
        $database = self::getConnection();

        $query = 'SELECT * FROM pricings WHERE `billing` = "'.$type.'" ORDER BY id ASC';

        $data = $database->executeQuery($query);

        $result = array();
        while ($row = mysqli_fetch_array($data,MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }


    public static function getById($id){
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM pricings WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }


    public static function count($column = null, $value = null){
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if(!empty($column)){
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM pricings WHERE ".$column." = '".$value."'");
        }else{
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM pricings");
        }

        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }

    public static function convertRowToObject($row){
        return new Pricing(
            $row['id'],
            $row['name'],
            $row['price'],
            $row['billing'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public static function insert (Pricing $pricing){

        $result = null;

        try{
            $database = self::getConnection();

          $result =  $database->executeQuery('INSERT INTO pricings (`name`, price, billing,created_at, updated_at) VALUES ("?", "?", "?", "?", "?")',
                array($pricing->name, $pricing->price, $pricing->billing,$pricing->created_at, $pricing->updated_at));

        }catch (Exception $e){
            var_dump($e->getMessage());
        }

        return $result;

    }


    public static function updateAll(Pricing $pricing){

        try{
            $database = self::getConnection();

            $database->executeQuery('UPDATE pricings SET `name` = "?", price = "?", updated_at = "?" WHERE id = "?"',
                array($pricing->name, $pricing->price, date("Y-m-d H:i:s"), $pricing->id));

//            var_dump("AFTER_INSERT </br>");
        }catch (Exception $e){
            var_dump($e->getMessage());
        }

    }


    public static function update( $Id, $olumn, $value){

        if(is_string($value)){
            self::getConnection()->executeQuery("UPDATE pricings SET " . $olumn . " = '?' , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }else{
            self::getConnection()->executeQuery("UPDATE pricings SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }
//        self::getConnection()->executeQuery("UPDATE pricings SET ".$olumn." = ? , updated_at = '?' WHERE id = ?",
//            array($value, date("Y-m-d H:i:s"), $Id));
    }

    public static function delete($Id){
        self::getConnection()->executeQuery("DELETE FROM pricings WHERE id = ?", array($Id));
    }

}