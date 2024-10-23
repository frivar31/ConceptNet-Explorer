<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

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

$sql = "UPDATE users SET score1 = ? WHERE username = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "is", $score_actuel, $username);


if ($stmt->execute()) {
    echo "Score mis à jour avec succès !";
} else {
    echo "Erreur lors de la mise à jour du score : (update)" . mysqli_error($link);
}

mysqli_close($link);

?>
