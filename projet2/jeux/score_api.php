<?php
session_start();

try {
    $bdd = new PDO('mysql:host=www-ens.iro.umontreal.ca;dbname=boudehaw_api_db;charset=utf8', 'boudehaw','hawca95B');
} catch (PDOException $e) {
    echo json_encode(array("error" => "Erreur de connexion à la base de données: " . $e->getMessage()));
    exit();
}

function loadFacts($bdd) {
    $resultData = array("records" => array());

    $query = $bdd->query("SELECT * FROM relations");

    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $resultData["records"][] = $row;
        }
    }
    $query->closeCursor();
    return json_encode($resultData);
}

echo loadFacts($bdd);
?>
