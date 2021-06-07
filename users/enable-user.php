<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 9:24 PM
 */

require_once('../db/User.php');


$message = 'Utilisateur mis a jour !';
$error = 1;

if (isset($_GET['user_id'])) {
    User::update($_GET['user_id'], 'active', '1');
}

header("location:../list-user.php?message=".$message.'&error='.$error);
die();
?>