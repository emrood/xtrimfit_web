<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 6/10/2021
 * Time: 11:24 AM
 */


require_once('Constants.php');
require_once('Database.php');


class Reservation


{

    private $id;
    private $fullname;
    private $phone;
    private $comment;
    private $reservation_date;
    private $from_time;
    private $to_time;
    private $room_id;
    private $invoice_id;
    private $customer_id;
    private $created_at;
    private $updated_at;

    /**
     * Reservation constructor.
     * @param $id
     * @param $fullname
     * @param $phone
     * @param $comment
     * @param $reservation_date
     * @param $from_time
     * @param $to_time
     * @param $room_id
     * @param $invoice_id
     * @param $customer_id
     * @param $created_at
     * @param $updated_at
     */
    public function __construct($id, $fullname, $phone, $comment, $reservation_date, $from_time, $to_time, $room_id, $invoice_id, $customer_id, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->fullname = $fullname;
        $this->phone = $phone;
        $this->comment = $comment;
        $this->reservation_date = $reservation_date;
        $this->from_time = $from_time;
        $this->to_time = $to_time;
        $this->room_id = $room_id;
        $this->invoice_id = $invoice_id;
        $this->customer_id = $customer_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    public static function getConnection()
    {
        return new Database(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getReservations($limit = null, $offset = null, $text = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM reservations ORDER BY id DESC';
        $params = null;


        $l = 20;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }

        if ($limit !== null && $offset != null) {
            $query = 'SELECT * FROM reservations ORDER BY id ASC LIMIT ' . $l . ' OFFSET ' . $o;
        } elseif ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM reservations WHERE `phone` lIKE "%' . $text . '%" ORDER BY id DESC  LIMIT ' . $l . ' OFFSET ' . $o;
        }


        $data = $database->executeQuery($query);


        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }


    public static function getByDate($from = null, $to = null)
    {
        $database = self::getConnection();

        $params = null;

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($from !== null) {
            $from_date = $from;
        }

        if ($to !== null) {
            $to_date = $to;
        }


        $query = 'SELECT * FROM reservations WHERE DATE(reservation_date) >= DATE("'.$from_date.'") AND DATE(reservation_date) <= DATE("'.$to_date.'") ORDER BY reservation_date ASC, from_time ASC';

        $data = $database->executeQuery($query);

        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public static function getById($id)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM pricings WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function convertRowToObject($row){
        return new Reservation(
            $row['id'],
            $row['fullname'],
            $row['phone'],
            $row['comment'],
            $row['reservation_date'],
            $row['from_time'],
            $row['to_time'],
            $row['room_id'],
            $row['invoice_id'],
            $row['customer_id'],
            $row['created_at'],
            $row['updated_at']
        );
    }


    public static function insert(Reservation $reservation)
    {

        $result = null;

        try {
            $database = self::getConnection();

            $result = $database->executeQuery("INSERT INTO reservations (`fullname`, `phone`, `comment`, `reservation_date`, `from_time`, `to_time`, `room_id`, `invoice_id`, `customer_id`, `created_at`, `updated_at`) VALUES ('?', '?', '?', '?', '?', '?', ?, ?, ?,'?', '?')",
                array($reservation->fullname, $reservation->phone, $reservation->comment, $reservation->reservation_date, $reservation->from_time, $reservation->to_time, $reservation->room_id, $reservation->invoice_id, $reservation->customer_id, $reservation->created_at, $reservation->updated_at));
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $result;
    }


    public static function updateAll(Reservation $reservation)
    {

        try {
            $database = self::getConnection();

            $database->executeQuery("UPDATE reservations SET `fullname` = '?', phone = '?', comment = '?', reservation_date = '?', from_time = '?', to_time = '?',  room_id = ?, updated_at = '?' WHERE id = ?",
                array($reservation->fullname, $reservation->phone, $reservation->comment, $reservation->reservation_date, $reservation->from_time, $reservation->to_time, $reservation->room_id, date("Y-m-d H:i:s"), $reservation->id));

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }



    }


    public static function update($Id, $olumn, $value)
    {

        if (is_string($value)) {
            self::getConnection()->executeQuery("UPDATE reservations SET " . $olumn . " = '?' , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        } else {
            self::getConnection()->executeQuery("UPDATE reservations SET " . $olumn . " = ? , updated_at = '?' WHERE id = ?", array($value, date("Y-m-d H:i:s"), $Id));
        }
//        self::getConnection()->executeQuery("UPDATE pricings SET ".$olumn." = ? , updated_at = '?' WHERE id = ?",
//            array($value, date("Y-m-d H:i:s"), $Id));
    }

    public static function delete($Id)
    {
        $reservation = Reservation::getById($Id);
        $invoice = Invoice::getById($reservation['invoice_id']);

        if($invoice !== null){
            if($invoice['status'] !== 'Paid'){
                self::getConnection()->executeQuery("DELETE FROM reservations WHERE id = ?", array($Id));
                self::getConnection()->executeQuery("DELETE FROM invoices WHERE id = ?", array($reservation['invoice_id']));
            }
        }

    }


}