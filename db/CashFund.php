<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/5/2021
 * Time: 12:33 AM
 */

require_once('Constants.php');
require_once('Database.php');

class CashFund
{

    private $id;
    private $rate_id;
    private $amount;
    private $balance;
    private $comment;
    private $type;
    private $created_at;
    private $updated_at;

    /**
     * CashFund constructor.
     * @param $id
     * @param $rate_id
     * @param $amount
     * @param $balance
     * @param $comment
     * @param $type
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $rate_id, $amount, $balance, $comment, $type, $created_at, $updated_at)
    {
        $this->id = (int) $id;
        $this->rate_id = (int) $rate_id;
        $this->amount = (double) $amount;
        $this->balance = (double) $balance;
        $this->comment = $comment;
        $this->type = $type;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    public static function getConnection()
    {
        return new Database(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getFunds($limit = null, $offset = null, $text = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM cash_funds ORDER BY created_at ASC';
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
            $query = 'SELECT * FROM cash_funds WHERE `comment` lIKE "%' . $text . '%" ORDER BY created_at ASC  LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM cash_funds ORDER BY id ASC LIMIT ' . $l . ' OFFSET ' . $o;
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


    public static function filter($text = null, $rate_id = null, $limit = null, $offset = null, $from_date = null, $to_date = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM cash_funds ORDER BY created_at ASC';
        $params = null;

        $l = 20;
        $o = 0;

        $from = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
        $to = date('Y-m-d');

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }


        if($from_date !== null){
            $from = $from_date;
        }

        if($to_date !== null){
            $to = $to_date;
        }


        if ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM cash_funds WHERE invoice_number lIKE "%' . $text . '%" ORDER BY id ASC LIMIT ' . $limit . ' OFFSET ' . $offset;
        }elseif($rate_id !== null && !empty($rate_id)){
            $query = 'SELECT * FROM cash_funds WHERE rate_id = '.$rate_id.' AND DATE(created_at) >= DATE("'.$from.' 00:00:00") AND DATE(created_at) <= DATE("'.$to.' 23:59:59") ORDER BY id ASC LIMIT ' . $l . ' OFFSET '.$o;
        }else{
            $query = 'SELECT * FROM cash_funds WHERE DATE(created_at) >= DATE("'.$from.' 00:00:00") AND DATE(created_at) <= DATE("'.$to.' 23:59:59") ORDER BY id ASC LIMIT ' . $l . ' OFFSET '.$o;
        }

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
        $data = $database->executeQuery("SELECT * FROM cash_funds WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }


    public static function getLastTransaction($rate_id)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM cash_funds WHERE rate_id = ".$rate_id." ORDER BY id DESC LIMIT 1");
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }



    public static function count($column = null, $value = null)
    {
        $database = self::getConnection();


        if (!empty($column)) {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM cash_funds WHERE " . $column . " = '" . $value . "'");
        } else {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM cash_funds");
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }


    public static function convertRowToObject($row)
    {
        return new CashFund(
            $row['id'],
            $row['rate_id'],
            $row['amount'],
            $row['balance'],
            $row['comment'],
            $row['type'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public static function insert(CashFund $cashFund)
    {
        $result = null;

        try {
            $database = self::getConnection();

            $result = $database->executeQuery("INSERT INTO cash_funds (`rate_id`, amount, balance, `comment`, `type`, created_at, updated_at) VALUES (?, ?, ?,'?', '?', '?', '?')",
                array($cashFund->rate_id, $cashFund->amount, $cashFund->balance, $cashFund->comment, $cashFund->type, $cashFund->created_at, $cashFund->updated_at));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        var_dump('<br/>'.$result.' <br/>');
        return $result;

    }


    public static function updateAll(CashFund $cashFund)
    {

//        try {
//            $database = self::getConnection();
//
//            $database->executeQuery('UPDATE cash_funds SET `setting` = "?", `value` = "?", updated_at = "?" WHERE id = "?"',
//                array($setting->setting, $setting->value, date("Y-m-d H:i:s"), $setting->id));
//
//        } catch (Exception $e) {
//            var_dump($e->getMessage());
//        }

    }


    public static function update($Id, $olumn, $value)
    {
        if(is_string($value)){
            self::getConnection()->executeQuery("UPDATE cash_funds SET " . $olumn . " = '?' , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }else{
            self::getConnection()->executeQuery("UPDATE cash_funds SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }
    }

}