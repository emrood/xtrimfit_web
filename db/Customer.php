<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 1:56 PM
 */

require_once('Constants.php');
require_once('Database.php');
require_once('Pricing.php');
require_once('Invoice.php');



class Customer
{

    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $address;
    private $phone;
    private $phone_alternative;
    private $fingerprint_uid;
    private $active;
    private $created_at;
    private $updated_at;
    private $picture;
    private $personal_id;
    private $id_type;
    private $pricing_id;

    /**
     * Customer constructor.
     * @param $id
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $phone
     * @param $fingerprint_uid
     * @param $active
     * @param $created_at
     */
    public function __construct($id, $picture, $first_name, $last_name, $email, $address, $phone, $phone_alternative, $personal_id, $id_type, $fingerprint_uid, $pricing_id, $active, $created_at)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->fingerprint_uid = $fingerprint_uid;
        $this->active = $active;
        $this->created_at = $created_at;
        $this->updated_at = $created_at;
        $this->picture = $picture;
        $this->personal_id = $personal_id;
        $this->id_type = $id_type;
        $this->phone_alternative = $phone_alternative;
        $this->address = $address;
        $this->pricing_id = $pricing_id;
    }


    public static function getConnection(){
        return new Database(Constants::getUrl(),Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getCustomers($limit = null, $offset = null, $text = null){
        $database = self::getConnection();

        $query = 'SELECT * FROM customers ORDER BY last_name ASC';
        $params =  null;

        if($limit !== null && $offset != null){
            $query = 'SELECT * FROM customers ORDER BY last_name ASC LIMIT '.$limit.' OFFSET '.$offset;
        }elseif($text !== null && !empty($text)){
            $query = 'SELECT * FROM customers WHERE first_name lIKE "%'.$text.'%" OR last_name LIKE "%'.$text.'%" OR phone LIKE "%'.$text.'%" ORDER BY last_name ASC LIMIT '.$limit.' OFFSET '.$offset;
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

    public static function getById($id){
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM customers WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }


    public static function getByPhone($phone_number){
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM customers WHERE phone = '?'", array($phone_number));
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }

    public static function count($column = null, $value = null){
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if(!empty($column)){
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM customers WHERE ".$column." = '".$value."'");
        }else{
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM customers");
        }

        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }

    public static function convertRowToObject($row){
        return new Customer(
            $row['id'],
            $row['picture'],
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['address'],
            $row['phone'],
            $row['phone_alternative'],
            $row['personal_id'],
            $row['id_type'],
            $row['fingerprint_uid'],
            $row['pricing_id'],
            $row['active'],
            $row['created_at']
        );
    }

    public static function insert (Customer $customer){

        $result = false;
        try{
            $database = self::getConnection();

//            var_dump("IN_INSERT </br>");
           $result =  $database->executeQuery('INSERT INTO customers (picture, address, first_name, last_name, email, phone, phone_alternative, personal_id, id_type, active, pricing_id, fingerprint_uid, created_at, updated_at) VALUES ("?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?")',
                array($customer->picture, $customer->address, $customer->first_name, $customer->last_name, $customer->email, $customer->phone, $customer->phone_alternative, $customer->personal_id, $customer->id_type, $customer->active, $customer->pricing_id,$customer->fingerprint_uid, $customer->created_at, $customer->updated_at));


           $temp_c = self::getByPhone($customer->phone);
           $temp_p = Pricing::getById($customer->pricing_id);

           if(!empty($temp_c) && !empty($temp_p)){
               $invoice_number = Invoice::invoice_num($temp_c['id'], 7, 'XF'.$temp_c['id'].'-');
               $database->executeQuery('INSERT INTO invoices (invoice_number, pricing_id, customer_id, price, total, from_date, to_date, created_at, updated_at) VALUES ("?", "?", "?", "?", "?", "?", "?", "?", "?")',
                   array($invoice_number, $customer->pricing_id, $temp_c['id'], $temp_p['price'], $temp_p['price'], date('Y-m-d'), date('Y-m-d', strtotime('+1 month')), date('Y-m-d'), date('Y-m-d')));
           }

//            var_dump("AFTER_INSERT </br> ".$result);
//            die();
        }catch (Exception $e){
            var_dump($e->getMessage());
        }

        return $result;

    }


    public static function updateAll(Customer $customer){

        try{
            $database = self::getConnection();

            $database->executeQuery('UPDATE customers SET picture = "?", address = "?", first_name = "?", last_name = "?", email = "?", phone = "?", phone_alternative = "?", personal_id = "?", id_type = "?", fingerprint_uid = "?", pricing_id = "?" WHERE id = "?"',
                array($customer->picture, $customer->address, $customer->first_name, $customer->last_name, $customer->email, $customer->phone, $customer->phone_alternative, $customer->personal_id, $customer->id_type, $customer->fingerprint_uid, $customer->pricing_id, $customer->id));

//            var_dump("AFTER_INSERT </br>");
        }catch (Exception $e){
            var_dump($e->getMessage());
        }

    }


    public static function update( $Id, $olumn, $value){
        self::getConnection()->executeQuery("UPDATE customers SET ".$olumn." = ? WHERE id = ?",
            array($value, $Id));
    }



}