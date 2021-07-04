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
$error = 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $temp = User::getById($_POST['id']);

//    var_dump($_POST);
//    die();

    $uploaddir = '../images/user/';
    $target_file = $uploaddir . basename($_FILES["picture"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $file_name = time().'.'.$imageFileType;
    $uploadfile = $uploaddir . $file_name;


    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile)) {
        $_POST['picture'] = $file_name;
    }ELSE{
        $_POST['picture'] = $temp['picture'];
    }

    if(isset($_POST['password']) && !empty($_POST['password'])){
        if($_POST['password'] === $_POST['password_verify']){
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $message = 'Utilisateur sauvegardé';
        }else{
            $message = 'Mot de passe éroné !';
            $error = true;
            header("location:../add-user.php?user_id=".$_POST['id']."&message='".$message."'&error=".$error);
            die();
        }
    }else{
        $_POST['password'] = $temp['password'];
    }




    $update_user = User::convertRowToObject($_POST);
    User::updateAll($update_user);

//    User::upda


//    var_dump($update_user);
//    die();
}

header("location:../list-user.php?message=".$message."&error=".$error);
die();
?>

