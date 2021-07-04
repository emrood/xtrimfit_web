<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 6:27 PM
 */
require_once('../db/Customer.php');
?>


<?php

$error = 1;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploaddir = '../images/customers/';
    $target_file = $uploaddir . basename($_FILES["picture"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $file_name = time().'.'.$imageFileType;
    $uploadfile = $uploaddir . $file_name;


    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile)) {
        $_POST['picture'] = $file_name;
    }ELSE{
        $_POST['picture'] = "";
    }

    $_POST['id'] = 0;
    $_POST['active'] = '1';
    $_POST['created_at'] = date("Y-m-d H:i:s");

    $new_customer = Customer::convertRowToObject($_POST);
    $result = Customer::insert($new_customer);

    if((int) $result === 1){
        $error = 1;
        $message = "Client sauvegardé";
    }else{
        $error = 0;
        $message = "Impossible d'enregistre le client";

    }


}

header("location:../list-customer.php?message=".$message."&error=".$error);
die();
?>
