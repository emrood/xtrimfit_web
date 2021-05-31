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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $temp = Customer::getById($_POST['id']);
    $uploaddir = '../images/customers/';
    $target_file = $uploaddir . basename($_FILES["picture"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $file_name = time().'.'.$imageFileType;
    $uploadfile = $uploaddir . $file_name;


    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile)) {
        $_POST['picture'] = $file_name;
    }ELSE{
        $_POST['picture'] = $temp['picture'];
    }

//    var_dump($_POST['picture']);
//    die();

    $update_customer = Customer::convertRowToObject($_POST);

    Customer::updateAll($update_customer);


//    var_dump($update_customer);
//    die();
}

header("location:../list-customer.php?message='Informations mises à jour'");
die();
?>

