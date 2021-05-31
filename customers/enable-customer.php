<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 5:18 PM
 */

require_once('../db/Customer.php');

$message = 'Compte client mis a jour !';
$error = false;
if (isset($_GET['customer_id'])) {
    Customer::update($_GET['customer_id'], 'active', '1');
}

header("location:../list-customer.php?message=".$message.'&error='.$error);
die();
?>