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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); 

    $score1 = 0;
    $score2 = 0;

    $stmt = $link->prepare("INSERT INTO users (username, password, score1, score2) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $username, $password, $score1, $score2);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; 

        exit;
    } else {
        echo 'Registration failed.';
    }

    $stmt->close();
}

mysqli_close($link);
?>
