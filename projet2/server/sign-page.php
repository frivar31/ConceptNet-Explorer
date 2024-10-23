<?php
session_start();
echo "<h2>Sign Page</h2>";
echo "<p>Veuillez vous inscrire</p>";
?>

<div class='boite'>
    <form id="registrationForm" method="post" class="mt-3">
        <div class="form-group">
            <label class="titre-style" for="username">Username:</label>
            <input type="text" name="username" id="username" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label class="titre-style" for="password">Password:</label>
            <input type="password" name="password" id="password" autocomplete="off" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
<div id="message"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="./style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200;400;600;700&display=swap" rel="stylesheet">
