<?php
session_start();

$bdd = new PDO('mysql:host=www-ens.iro.umontreal.ca;dbname=boudehaw_api_db;charset=utf8','boudehaw' , 'hawca95B');

if(isset($_POST['username'])) {
    $username = $_POST['username'];

    $checkUser = $bdd->prepare('SELECT id FROM users WHERE username = ?');
    $checkUser->execute([$username]);

    if ($checkUser->rowCount() > 0) {
        echo 'exists';
    } else {
        echo 'available';
    }
} else {
    echo 'error';
}
?>
