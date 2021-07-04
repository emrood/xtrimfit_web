<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 10:39 AM
 */

require_once('Constants.php');
require_once('Database.php');


class User
{

    private $id;
    private $name;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;
    private $role;
    private $picture;
    private $active;
    private $phone;

    /**
     * User constructor.
     * @param $name
     * @param $email
     * @param $password
     * @param $created_at
     * @param $role
     */
    public function __construct($id, $name, $email, $phone, $password, $role, $picture, $active, $created_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $created_at;
        $this->role = $role;
        $this->picture = $picture;
        $this->active = $active;
        $this->phone = $phone;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }







    public static function getConnection(){
        return new Database(Constants::getUrl(),Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getAllUser(){
        $database = self::getConnection();
        $data = $database->executeQuery('SELECT * FROM users');
        return $data;
    }


    public static function getUsers($limit = null, $offset = null, $text = null){
        $database = self::getConnection();

        $query = 'SELECT * FROM users ORDER BY `name` ASC';
        $params =  null;

        if($limit !== null && $offset != null){
            $query = 'SELECT * FROM users ORDER BY `name` ASC LIMIT '.$limit.' OFFSET '.$offset;
        }elseif($text !== null && !empty($text)){
            $query = 'SELECT * FROM users WHERE `name` lIKE "%'.$text.'%" OR email LIKE "%'.$text.'%" OR phone LIKE "%'.$text.'%" ORDER BY last_name ASC LIMIT '.$limit.' OFFSET '.$offset;
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
        $data = $database->executeQuery("SELECT * FROM users WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }

    public static function login($email, $password){
        $database = self::getConnection();
        $user = $database->executeQuery("SELECT * FROM users WHERE email = '?'", [$email]);

        $row = mysqli_fetch_array($user,MYSQLI_ASSOC);
//        var_dump($row);

        if(password_verify($password, $row['password'])){
            return $row;
        }else{
            return false;
        }

//        return $users;
    }


    public static function count($column = null, $value = null){
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if(!empty($column)){
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM users WHERE ".$column." = '".$value."'");
        }else{
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM users");
        }

        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        return $row;
    }


    public static function convertRowToObject($row){
        return new User(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['password'],
            $row['role'],
            $row['picture'],
            $row['active'],
            $row['created_at']
        );
    }


    public static function updateAll(User $user){

        try{
            $database = self::getConnection();
            $database->executeQuery('UPDATE users SET picture = "?", `name` = "?", email = "?", role = "?", phone = "?",  password = "?" WHERE id = "?"',
                array($user->picture, $user->name, $user->email, $user->role, $user->phone, $user->password, $user->id));
//            var_dump("AFTER_INSERT </br>");
        }catch (Exception $e){
            var_dump($e->getMessage());
        }

    }


    public static function insert (User $user){
        self::getConnection()->executeQuery("INSERT INTO users (`name`, email, phone, password, role, active, picture, created_at, updated_at) VALUES ('?', '?', '?', '?', '?', '?', '?', '?', '?')",
            array($user->name, $user->email, $user->phone, $user->password, $user->role, $user->active, $user->picture, $user->created_at, $user->updated_at));
    }


    public static function update( $Id, $olumn, $value){
        self::getConnection()->executeQuery("UPDATE users SET ".$olumn." = ? WHERE id = ?",
            array($value, $Id));
    }
}