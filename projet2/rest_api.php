<?php
$conn = new mysqli('localhost', 'root', 'root', 'mysql');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function returnJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Endpoint pour la liste des concepts différents
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/\/concepts\/?/', $_SERVER['REQUEST_URI'])) {
    $sql = "SELECT DISTINCT start, end FROM relations";
    $result = $conn->query($sql);
    $concepts = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $concepts[] = $row['start'];
            $concepts[] = $row['end'];
        }
    }
    returnJsonResponse(array_unique($concepts));
}

// Endpoint pour la liste des relations différentes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/\/relations\/?/', $_SERVER['REQUEST_URI'])) {
    $sql = "SELECT DISTINCT relation FROM relations";
    $result = $conn->query($sql);
    $relations = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $relations[] = $row['relation'];
        }
    }
    returnJsonResponse($relations);
}

// Endpoint pour la liste des utilisateurs avec les scores des jeux 1 et 2
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/\/users\/?/', $_SERVER['REQUEST_URI'])) {
    $sql = "SELECT username, score1, score2 FROM users";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    returnJsonResponse($users);
}


// Endpoint pour la création d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('/\/users\/?/', $_SERVER['REQUEST_URI'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, score1, score2) VALUES ('$username', '$hashed_password', 0, 0)";
    if ($conn->query($sql) === TRUE) {
        returnJsonResponse("Utilisateur créé avec succès");
    } else {
        returnJsonResponse("Erreur lors de la création de l'utilisateur: " . $conn->error);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/\/help\/?/', $_SERVER['REQUEST_URI'])) {
    $html = "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>API Documentation</title>
    </head>
    <body>
        <h1>Documentation de l\'API</h1>
        <h2>Exemples de requêtes :</h2>
        <ul>
            <li>GET /concepts - Liste des concepts différents</li>
            <li>GET /relations - Liste des relations différentes</li>
            <li>GET /users - Liste des utilisateurs avec les scores des jeux 1 et 2</li>
            <li>POST /users - Création d'un utilisateur</li>
        </ul>
    </body>
    </html>";

    echo $html;
}


$conn->close();
?>