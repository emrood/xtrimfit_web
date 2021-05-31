<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/30/2021
 * Time: 11:49 AM
 */

require_once('Constants.php');
require_once('Database.php');
//require_once('Pricing.php');
require_once('Customer.php');


class Invoice implements \JsonSerializable
{

    private $id;
    private $invoice_number;
    private $customer_id;
    private $pricing_id;
    private $price;
    private $discount_percentage;
    private $taxe_percentage;
    private $fees;
    private $total;
    private $status;
    private $from_date;
    private $paid_date;
    private $to_date;
    private $comment;
    private $created_at;
    private $updated_at;

    /**
     * Invoice constructor.
     * @param $id
     * @param $customer_id
     * @param $pricing_id
     * @param $price
     * @param $discount_percentage
     * @param $taxe_percentage
     * @param $fees
     * @param $total
     * @param $status
     * @param $from_date
     * @param $paid_date
     * @param $to_date
     * @param $comment
     * @param $created_at
     */
    public function __construct($id, $invoice_number, $customer_id, $pricing_id, $price, $discount_percentage, $taxe_percentage, $fees, $total, $status, $from_date, $paid_date, $to_date, $comment, $created_at)
    {
        $this->id = (int) $id;
        $this->invoice_number = $invoice_number;
        $this->customer_id = (int) $customer_id;
        $this->pricing_id = (int) $pricing_id;
        $this->price = (double) $price;
        $this->discount_percentage = (double) $discount_percentage;
        $this->taxe_percentage = (double) $taxe_percentage;
        $this->fees = (double) $fees;
        $this->total = (double) $total;
        $this->status = $status;
        $this->from_date = $from_date;
        $this->paid_date = $paid_date;
        $this->to_date = $to_date;
        $this->comment = $comment;
        $this->created_at = $created_at;
        $this->updated_at = $created_at;
    }


    public static function getConnection()
    {
        return new Database(Constants::getUrl(), Constants::getUserName(), Constants::getPassword(), Constants::getDbName());
    }


    public static function getInvoices($limit = null, $offset = null, $text = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM invoices ORDER BY from_date DESC';
        $params = null;

        if ($limit !== null && $offset != null) {
            $query = 'SELECT * FROM invoices ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        } elseif ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM invoices WHERE invoice_number lIKE "%' . $text . '%" ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
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


    public static function getLastPaidInvoices($limit = null, $offset = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM invoices ORDER BY from_date DESC';
        $params = null;

        if ($limit !== null && $offset != null) {
            $query = 'SELECT * FROM invoices WHERE status = "Paid" ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        } else {
            $query = 'SELECT * FROM invoices WHERE status = "Paid" ORDER BY from_date DESC LIMIT 5 OFFSET 0';
        }

        $data = $database->executeQuery($query);

        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public static function getLastPendingInvoices($limit = null, $offset = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM invoices ORDER BY from_date DESC';
        $params = null;

        if ($limit !== null && $offset != null) {
            $query = 'SELECT * FROM invoices WHERE status = "Pending" ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        } else {
            $query = 'SELECT * FROM invoices WHERE status = "Pending" ORDER BY from_date DESC LIMIT 5 OFFSET 0';
        }

        $data = $database->executeQuery($query);

        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }


    public static function getLastUnpaidInvoices($limit = null, $offset = null)
    {
        $database = self::getConnection();

        $query = 'SELECT * FROM invoices ORDER BY from_date DESC';
        $params = null;

        if ($limit !== null && $offset != null) {
            $query = 'SELECT * FROM invoices WHERE status = "Unpaid" ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        } else {
            $query = 'SELECT * FROM invoices WHERE status = "Unpaid" ORDER BY from_date DESC LIMIT 5 OFFSET 0';
        }

        $data = $database->executeQuery($query);

        $result = array();
        while ($row = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    public static function getCustomerInvoices($customer_id, $limit = null, $offset = null, $text = null)
    {

        $database = self::getConnection();

        $query = 'SELECT * FROM invoices ORDER BY from_date DESC';
        $params = null;

        if ($limit !== null && $offset != null) {
            $query = 'SELECT * FROM invoices WHERE customer_id = ' . $customer_id . ' ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        } elseif ($text !== null && !empty($text)) {
            $query = 'SELECT * FROM invoices WHERE customer_id = ' . $customer_id . ' AND invoice_number lIKE "%' . $text . '%" ORDER BY from_date DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
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
        $data = $database->executeQuery("SELECT * FROM invoices WHERE id = '?'", array($id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getTotalUnpaid($from_date = null, $to_date = null, $period = 'month')
    {
        $database = self::getConnection();

        if ($from_date !== null) {
            if ($period === 'month') {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE MONTH(from_date) = MONTH('?') AND status = '?'", array($from_date, 'Unpaid'));
            } elseif ($period == 'interval') {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE DATE(from_date) >= DATE('?') AND DATE(from_date) <= DATE('?')  AND status = '?'", array($from_date . ' 00:00:00', $to_date . '23:59:59', 'Unpaid'));
            } else {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE DATE(from_date) = DATE('?') AND status = '?'", array($from_date, 'Unpaid'));
            }
        } else {
            $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE status = '?'", array('Unpaid'));
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getTotalPaid($from_date = null, $to_date = null, $period = 'month')
    {
        $database = self::getConnection();

        if ($from_date !== null) {
            if ($period === 'month') {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE MONTH(from_date) = MONTH('?') AND status = '?'", array($from_date, 'Paid'));
            } elseif ($period == 'interval') {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE DATE(from_date) >= DATE('?') AND DATE(from_date) <= DATE('?')  AND status = '?'", array($from_date . ' 00:00:00', $to_date . '23:59:59', 'Paid'));
            } else {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE DATE(from_date) = DATE('?') AND status = '?'", array($from_date, 'Paid'));
            }
        } else {
            $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE status = '?'", array('Paid'));
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getTotalPending($from_date = null, $to_date = null, $period = 'month')
    {
        $database = self::getConnection();

        if ($from_date !== null) {
            if ($period === 'month') {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE MONTH(from_date) = MONTH('?') AND status = '?'", array($from_date, 'Pending'));
            } elseif ($period == 'interval') {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE DATE(from_date) >= DATE('?') AND DATE(from_date) <= DATE('?')  AND status = '?'", array($from_date . ' 00:00:00', $to_date . '23:59:59', 'Pending'));
            } else {
                $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE DATE(from_date) = DATE('?') AND status = '?'", array($from_date, 'Pending'));
            }
        } else {
            $data = $database->executeQuery("SELECT SUM(`total`) as total FROM invoices WHERE status = '?'", array('Pending'));
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getLastCustomerInvoice($customer_id)
    {
        $database = self::getConnection();
        $data = $database->executeQuery("SELECT * FROM invoices WHERE customer_id = '?' ORDER BY from_date DESC LIMIT 1 OFFSET 0", array($customer_id));
        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function count($column = null, $value = null)
    {
        $database = self::getConnection();

//        var_dump('column = '.$value);

        if (!empty($column)) {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM invoices WHERE " . $column . " = '" . $value . "'");
        } else {
            $data = $database->executeQuery("SELECT COUNT(*) as qty FROM invoices");
        }

        $row = mysqli_fetch_array($data, MYSQLI_ASSOC);
        return $row;
    }

    public static function getCustomerInvoicesByStatus($customer_id, $limit = null, $offset = null, $status = null)
    {

        $database = self::getConnection();

        $query = 'SELECT * FROM invoices ORDER BY from_date DESC';
        $params = null;

        $l = 5;
        $o = 0;

        if ($limit !== null) {
            $l = $limit;
        }

        if ($offset !== null) {
            $o = $offset;
        }


        if ($status !== null && !empty($status)) {
            $query = 'SELECT * FROM invoices WHERE customer_id = ' . $customer_id . ' AND status = "' . $status . '" ORDER BY from_date DESC LIMIT ' . $l . ' OFFSET ' . $o;
        } else {
            $query = 'SELECT * FROM invoices WHERE customer_id = ' . $customer_id . ' ORDER BY from_date DESC LIMIT ' . $l . ' OFFSET ' . $o;
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

    public static function convertRowToObject($row)
    {
        return new Invoice(
            $row['id'],
            $row['invoice_number'],
            $row['customer_id'],
            $row['pricing_id'],
            $row['price'],
            $row['discount_percentage'],
            $row['taxe_percentage'],
            $row['fees'],
            $row['total'],
            $row['status'],
            $row['from_date'],
            $row['paid_date'],
            $row['to_date'],
            $row['comment'],
            $row['created_at']
        );
    }

    public static function insert(Invoice $invoice)
    {

        try {
            $database = self::getConnection();
//            var_dump("IN_INSERT </br>");
            $database->executeQuery('INSERT INTO invoices (invoice_number, customer_id, pricing_id, price, discount_percentage, taxe_percentage, fees, total, status, from_date, paid_date, to_date, comment, created_at, updated_at) VALUES ("?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?")',
                array($invoice->invoice_number, $invoice->customer_id, $invoice->pricing_id, $invoice->price, $invoice->discount_percentage, $invoice->taxe_percentage, $invoice->fees, $invoice->total, $invoice->status, $invoice->from_date, $invoice->paid_date, $invoice->to_date, $invoice->comment, $invoice->created_at, $invoice->updated_at));

            if($invoice->status === 'Paid' && $invoice > 1){
                $temp_c = Customer::getById($invoice);
                $temp_p = Pricing::getById($temp_c['pricing_id']);

                if(!empty($temp_c) && !empty($temp_p)){
                    $invoice_number = Invoice::invoice_num($temp_c['id'], 7, 'XF'.$temp_c['id'].'-');
                    $database->executeQuery('INSERT INTO invoices (invoice_number, pricing_id, customer_id, price, total, from_date, to_date, created_at, updated_at) VALUES ("?", "?", "?", "?", "?", "?", "?", "?", "?")',
                        array($invoice_number, $temp_c['pricing_id'], $temp_c['id'], $temp_p['price'], $temp_p['price'], $invoice->to_date, date('Y-m-d', strtotime('+1 month', strtotime($invoice->to_date))), date('Y-m-d'), date('Y-m-d')));
                }
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

    }


    public static function updateAll(Invoice $invoice)
    {


//        var_dump($invoice);
//        die();
        try {
            $database = self::getConnection();

//            var_dump($invoice);
//            die();

            $database->executeQuery('UPDATE invoices SET pricing_id = "?", price = "?", discount_percentage = "?", taxe_percentage = "?", fees = "?", total = "?", status = "?", paid_date = "?", comment = "?" WHERE id = "?"',
                array($invoice->pricing_id, $invoice->price, $invoice->discount_percentage, $invoice->taxe_percentage, $invoice->fees, $invoice->total, $invoice->status, $invoice->paid_date, $invoice->comment,  $invoice->id));


            if($invoice->status === 'Paid' && $invoice->customer_id > 1){
                $temp_c = Customer::getById($invoice->customer_id);
                $temp_p = Pricing::getById($temp_c['pricing_id']);

                if(!empty($temp_c) && !empty($temp_p)){
                    $invoice_number = Invoice::invoice_num($temp_c['id'], 7, 'XF'.$temp_c['id'].'-');
                    $database->executeQuery('INSERT INTO invoices (invoice_number, pricing_id, customer_id, price, total, from_date, to_date, created_at, updated_at) VALUES ("?", "?", "?", "?", "?", "?", "?", "?", "?")',
                        array($invoice_number, $temp_c['pricing_id'], $temp_c['id'], $temp_p['price'], $temp_p['price'], $invoice->to_date, date('Y-m-d', strtotime('+1 month', strtotime($invoice->to_date))), date('Y-m-d'), date('Y-m-d')));
                }
            }

        } catch (Exception $e) {
            var_dump($e->getMessage());
//            die();
        }

    }


    public static function update($Id, $olumn, $value)
    {
        self::getConnection()->executeQuery("UPDATE invoices SET " . $olumn . " = ? WHERE id = ?", array($value, $Id));


    }

    public static function invoice_num($customer_id, $pad_len = 7, $prefix = null)
    {
        $input = 1;
        $qty = (int) self::count('customer_id', $customer_id)['qty'];
        $input = $qty + 1;
        if ($pad_len <= strlen($input))
            trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

        if (is_string($prefix))
            return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

        return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getInvoiceNumber()
    {
        return $this->invoice_number;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @return mixed
     */
    public function getPricingId()
    {
        return $this->pricing_id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getDiscountPercentage()
    {
        return $this->discount_percentage;
    }

    /**
     * @return mixed
     */
    public function getTaxePercentage()
    {
        return $this->taxe_percentage;
    }

    /**
     * @return mixed
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getFromDate()
    {
        return $this->from_date;
    }

    /**
     * @return mixed
     */
    public function getPaidDate()
    {
        return $this->paid_date;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->to_date;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }



}