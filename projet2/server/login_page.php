<?php
session_start();
echo "<h2>Login Page</h2>";
echo "<p>Veuillez vous connecter</p>";
?>

<div class='boite'>
    <form id="loginForm" method="post">
          <label class="titre-style" for="username">Username:</label>
          <input type="text" id="username" name="username"><br>
          <label class="titre-style" for="password">Password:</label>
          <input type="password" id="password" name="password"><br>
          <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="./style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200;400;600;700&display=swap" rel="stylesheet">