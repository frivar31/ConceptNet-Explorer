<?php
session_start();

$user = 'boudehaw';
$password = 'hawca95B';
$db = 'boudehaw_api_db';
$host = 'www-ens.iro.umontreal.ca';


$link = mysqli_connect($host, $user, $password, $db);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$score_actuel = intval($_GET['score']); 
$username = $_SESSION['username']; 

$sql = "UPDATE users SET score2 = ? WHERE username = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("is", $score_actuel, $username);
$stmt->execute();

mysqli_close($link);
?>
