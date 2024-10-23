<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<p>Vous devez vous connecter à <a href='#/login'>login</a>.</p>";
    exit;
}
$user = 'boudehaw';
$password = 'hawca95B';
$db = 'boudehaw_api_db';
$host = 'www-ens.iro.umontreal.ca';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Statistique 1: Nombre de concepts différents
$sql_concepts = "SELECT COUNT(DISTINCT start ) AS nb_concepts FROM relations";
$result_concepts = $conn->query($sql_concepts);
$row_concepts = $result_concepts->fetch_assoc();
$nb_concepts = $row_concepts["nb_concepts"];

// Statistique 2: Nombre de relations différentes
$sql_relations = "SELECT COUNT(DISTINCT relation) AS nb_relations FROM relations";
$result_relations = $conn->query($sql_relations);
$row_relations = $result_relations->fetch_assoc();
$nb_relations = $row_relations["nb_relations"];

// Statistique 3: Nombre de faits dans la base
$sql_faits = "SELECT COUNT(*) AS nb_faits FROM relations";
$result_faits = $conn->query($sql_faits);
$row_faits = $result_faits->fetch_assoc();
$nb_faits = $row_faits["nb_faits"];

// Statistique 4: Nombre d'utilisateurs
$sql_users = "SELECT COUNT(*) AS nb_utilisateurs FROM users";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$nb_utilisateurs = $row_users["nb_utilisateurs"];

// Récupération du score du jeu1 pour l'utilisateur connecté
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql_score1 = "SELECT score1 FROM users WHERE username='$username'";
    $result_score1 = $conn->query($sql_score1);
    if ($result_score1->num_rows > 0) {
        $row_score1 = $result_score1->fetch_assoc();
        $score1 = $row_score1["score1"];
    } else {
        $score1 = "N/A";
    }
} else {
    $score1 = "N/A";
}
    // Récupération du score du jeu2 pour l'utilisateur connecté
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql_score2 = "SELECT score2 FROM users WHERE username='$username'";
    $result_score2 = $conn->query($sql_score2);
    if ($result_score2->num_rows > 0) {
        $row_score2 = $result_score2->fetch_assoc();
        $score2 = $row_score2["score2"];
    } else {
        $score2 = "N/A"; 
    }
} else {
    $score2 = "N/A";
}


$conn->close();
?>


<div class="container">
    <h2>Statistiques</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Statistique</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nombre de concepts différents</td>
                <td><?php echo $nb_concepts; ?></td>
            </tr>
            <tr>
                <td>Nombre de relations différentes</td>
                <td><?php echo $nb_relations; ?></td>
            </tr>
            <tr>
                <td>Nombre de faits dans la base</td>
                <td><?php echo $nb_faits; ?></td>
            </tr>
            <tr>
                <td>Nombre d'utilisateurs</td>
                <td><?php echo $nb_utilisateurs; ?></td>
            </tr>
            <tr>
                <td>Le score du jeu Qui suis je ? le plus récent</td>
                <td><?php echo $score1; ?></td>
            </tr>
            <tr>
                <td>Le score du jeu Related le plus récent</td>
                <td><?php echo $score2; ?></td>
            </tr>
        </tbody>
    </table>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>