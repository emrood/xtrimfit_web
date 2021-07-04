<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/6/2021
 * Time: 11:58 PM
 */

class UserLog
{

    private $id;
    private $user_id;
    private $browser_fingerprint;
    private $browser;
    private $user_timezone;
    private $user_system;
    private $created_at;
    private $updated_at;

    /**
     * UserLog constructor.
     * @param $id
     * @param $user_id
     * @param $browser_fingerprint
     * @param $browser
     * @param $user_timezone
     * @param $user_system
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $user_id, $browser_fingerprint, $browser, $user_timezone, $user_system, $created_at, $updated_at)
    {
        $this->id = (int) $id;
        $this->user_id = $user_id;
        $this->browser_fingerprint = $browser_fingerprint;
        $this->browser = $browser;
        $this->user_timezone = $user_timezone;
        $this->user_system = $user_system;
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
        $data = $database->executeQuery("SELECT * FROM user_logs WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getByFingerPrint($fringerprint)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM user_logs WHERE browser_fingerprint = '?'", array($fringerprint));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getLogs($user_id = null, $fingerprint =  null, $limit = null, $offset = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM user_logs ORDER BY created_at DESC';
        $params = null;

        $l = 30;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }


        if($user_id !== null && !empty($user_id)){
            $query = 'SELECT * FROM user_logs WHERE user_id = '.$user_id.' ORDER BY id ASC LIMIT ' . $l . ' OFFSET ' . $o;
        }else if ($fingerprint !== null && !empty($fingerprint)) {
            $query = 'SELECT * FROM user_logs WHERE `fingerprint` lIKE "%' . $fingerprint . '%" ORDER BY created_at DESC  LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM user_logs ORDER BY created_at DESC LIMIT ' . $l . ' OFFSET ' . $o;
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
        return new UserLog(
            $row['id'],
            $row['user_id'],
            $row['browser_fingerprint'],
            $row['user_browser'],
            $row['user_timezone'],
            $row['user_system_info_full'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public static function insert(UserLog $userLog)
    {
        $result = null;

        try {
            $database = self::getConnection();

            $result = $database->executeQuery("INSERT INTO user_logs (user_id, browser_fingerprint, browser, user_timezone, user_system, created_at, updated_at) VALUES (?, '?', '?', '?', '?', '?', '?')",
                array($userLog->user_id, $userLog->browser_fingerprint, $userLog->browser, $userLog->user_timezone, $userLog->user_system, $userLog->created_at, $userLog->updated_at));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        var_dump('<br/>'.$result.' <br/>');
        return $result;

    }


}