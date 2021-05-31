<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 6:27 PM
 */
require_once('../db/User.php');
?>


<?php


$message = 'Utilisateur sauvegardé';
$error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploaddir = '../images/user/';
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

    if($_POST['password'] === $_POST['password_verify']){

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $new_user = User::convertRowToObject($_POST);
        User::insert($new_user);

        $message = 'Utilisateur sauvegardé';
//        var_dump($new_user);
//        die();
    }else{

        $error = true;
        $message = 'Mot de passe éroné !';
    }

}

header("location:../list-user.php?message=".$message."&error=".$error);
die();
?>
