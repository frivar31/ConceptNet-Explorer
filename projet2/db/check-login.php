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

    $stmt = $link->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        echo 'success';
    } else {
        echo 'failure';
    }

    $stmt->close();
}

mysqli_close($link);
?>
