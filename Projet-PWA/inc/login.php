<h1>ABC Conception</h1>
<?php
    // Display connection error messages if there are any
    if(isset($_GET['error'])) {
        if(isset($_GET['message'])) {
        // Display of the error message linked to the connection request
        echo'<div class="alert error">'.utf8_decode(filter_var($_GET['message'], FILTER_SANITIZE_STRING)).'</div>';
    }
} ?>

<form method="post" action="back-end/login/login-treatment.php">
    <label for="email">
        <input type="email" name="email" placeholder="Votre adresse email" required/>
    </label>
    <label for="password">
        <input type="password" name="password" placeholder="Mot de passe" required/>
    </label>
    <button type="submit">S'identifier</button><br>
    <label id="remind-me"><input type="checkbox" id="remind-me" name="remind-me"/>Se souvenir de moi</label>
</form>

<p class="abc">Mot de passe oublié ? <a href="pages/reset-password.php">Réinitialisez-le</a>.</p>
<p class="abc">Première visite ? <a href="pages/register.php">Inscrivez-vous</a>.</p>
