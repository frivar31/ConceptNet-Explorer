<?php
session_start();
echo "<h2>Help Page</h2>";
if (!isset($_SESSION['username'])) {
    echo "<p>Vous devez vous connecter à <a href='#/login'>login</a>.</p>";
    exit;
}
echo "<p><b>Joueur, " . $_SESSION['username'] . "!</b></p>";
?>

    <ul class="list-group d-inline-block p-3 w-50">
        <li class="list-group-item list-group-item-action"><a href="#/">Home</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/help">Help</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/login">Login</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/logout">Logout</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/stats">Stats</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/dump/faits">Dump Facts</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/sign">Sign Up</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/concept/fr/ami">#/concept/ :langue/ :concept : fr/Ami</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/relation/RelatedTo/from/fr/mot">#/relation/ :relation/from/ :langue/ :concept : RelatedTo/fr/mot</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/relation/IsA">#/relation/ :relation : IsA</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/jeux/quisuisje/60/10">Jeu: Qui suis-je? (60s, Indice 10)</a></li>
        <li class="list-group-item list-group-item-action"><a href="#/jeux/related/60">Jeu: Related (60s)</a></li>
    </ul>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="./style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200;400;600;700&display=swap" rel="stylesheet">