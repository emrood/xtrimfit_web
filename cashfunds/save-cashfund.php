<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/30/2021
 * Time: 11:50 PM
 */
require_once('../db/Invoice.php');
require_once('../db/Pricing.php');

$error = 1;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//    var_dump($_POST);
//    die();

    $temp_total = (double) $_POST['price'] + (double) $_POST['fees'];

    if((double) $_POST['discount_percentage'] > 0){
        $temp_total = $temp_total - ($temp_total * ((double) $_POST['discount_percentage']) / 100);
    }

    if((double) $_POST['taxe_percentage'] > 0){
        $temp_total = $temp_total + ($temp_total * ((double) $_POST['taxe_percentage']) / 100);
    }

    $_POST['total'] = (double) $temp_total;

    if($_POST['status'] === 'Paid'){
        $_POST['paid_date'] = date('Y-m-d');
    }else{
        $_POST['paid_date'] = null;
    }

    if((int) $_POST['id'] === 0){
        $_POST['status'] = 'Paid';
        $_POST['from_date'] = date('Y-m-d');
        $_POST['to_date'] = date('Y-m-d');
        $_POST['pricing_id'] = 1;
        $_POST['price'] = Pricing::getById(1)['price'];



        $new_invoice = Invoice::convertRowToObject($_POST);
        Invoice::insert($new_invoice);
        $message = "Facture enregistre";
    }else{


        $new_invoice = Invoice::convertRowToObject($_POST);

        foreach ($_POST as $key => $value){
            if($key !== 'id'){
                Invoice::update($new_invoice->getId(), $key, $value);
                var_dump($key.' => '.$value.'<br/>');
            }
        }
        Invoice::updateAll($new_invoice);

//        var_dump($new_invoice);
//        die();
        $message = "Facture mis a jour";
        header("location:../view-invoice.php?invoice_id=".$new_invoice->getId()."&message=".$message."&error=".$error);
        die();
    }

//    var_dump($new_invoice);
//    die();
}

header("location:../list-invoice.php?message=".$message."&error=".$error);
die();

?>