<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 10:31 AM
 */

require_once('Constants.php');

class Database
{

    protected $url;
    protected $user;
    protected $passw;
    protected $db;
    protected $connection = null;

    public function __construct($url, $user, $passw, $db)
    {
        $this->url = $url;
        $this->user = $user;
        $this->passw = $passw;
        $this->db = $db;
    }

    public function __destruct()
    {
        if ($this->connection != null) {
            $this->closeConnection();
        }
    }

    protected function makeConnection()
    {
        //Make a connection
        $this->connection = new mysqli($this->url, $this->user, $this->passw, $this->db);
        if ($this->connection->connect_error) {
            echo "FAIL:" . $this->connection->connect_error;
        }
    }

    protected function closeConnection()
    {
        //Close the DB connection
        if ($this->connection != null) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    protected function cleanParameters($p)
    {
        //prevent SQL injection
        $result = $this->connection->real_escape_string($p);
        return $result;
    }

    public function executeQuery($q, $params = null)
    {

        $this->makeConnection();
        if ($params != null) {
            $queryParts = preg_split("/\?/", $q);
            if (count($queryParts) != count($params) + 1) {
                var_dump("PARAMETER COUNT DOESNT MATCH");
                return false;
            }
            $finalQuery = $queryParts[0];
            for ($i = 0; $i < count($params); $i++) {
                $finalQuery = $finalQuery . $this->cleanParameters($params[$i]) . $queryParts[$i + 1];
            }
            $q = $finalQuery;
        }


        $results = $this->connection->query($q);


        if (!mysqli_commit($this->connection)) {
            var_dump("Commit transaction failed");
        }

//        if (!mysqli_query($this->connection, $q)) {
//            var_dump("Error: " . mysqli_error($this->connection));
//            $_SESSION['db_error'] = mysqli_error($this->connection);
//        }



        return $results;
    }
} ?>
