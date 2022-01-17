<?php
require_once '../inc/head.php';
?>
<body>
<section>
    <div id="register-body">
        <h1>S'inscrire</h1>
        <?php
            // Display register error messages if there are any
            if(isset($_GET['error'])){
                if(isset($_GET['message'])) {
                    // Display of the error message linked to the connection request
                    echo'<div class="alert error">'.nl2br($_GET['message'], false).'</div>';
                }
            // Display of the recording validation message if there is no error
            } else if(isset($_GET['success'])) {
                echo '<div class="alert success">Vous êtes désormais inscrit. <a href="../index.php">Connectez-vous</a>.</div>';
            }
        ?>
        <?php if
        // Display of the registration form if registration error
        (!isset($_GET['success'])) { ?>
        <form method="post" action="../back-end/register/register-treatment.php">
            <label for="lastname">
                <input type="text" id="lastname" name="lastname" maxlength="100" placeholder="Votre nom" required />
            </label>
            <label for="firstname">
                <input type="text" id="firstname" name="firstname" maxlength="100" placeholder="Votre prénom" required />
            </label>
            <label for="name_enterprise">
                <input type="text" id="name_enterprise" name="name_enterprise" maxlength="150" placeholder="Le nom de votre société" required />
            </label>
            <label for="siret">
                <input type="text" id="siret" name="siret" maxlength="14" pattern="\d{14}" placeholder="Le SIRET de votre société" required />
            </label>
            <label for="website">
                <input type="text" id="website" name="website" placeholder="Votre site web" required />
            </label>
            <label for="address">
                <input type="text" id="address" name="address" placeholder="L'adresse de votre société" required />
            </label>
            <label for="email">
                <input type="email" id="email" name="email" maxlength="255" placeholder="Votre adresse email" required />
            </label>
            <label for="phone">
                <input type="text" id="phone" name="phone" maxlength="20" pattern="\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}" placeholder="Votre téléphone" required />
            </label>
            <label for="password">
                <input type="password" id="password" name="password" placeholder="Mot de passe" required />
            </label>
            <label for="password_two">
                <input type="password" id="password_two" name="password_two" placeholder="Retapez votre mot de passe"  required />
            </label>
                <button type="submit">S'inscrire</button>
        </form>
        <p class="grey">Déjà sur ABC Conception ? <a href="../">Connectez-vous</a>.</p>
        <?php } ?>
    </div>
</section>
</body>
</html>