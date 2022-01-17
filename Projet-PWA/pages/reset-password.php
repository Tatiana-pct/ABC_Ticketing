<?php
session_start();
require_once '../inc/head.php';
?>

<body>
    <section>
        <div id="login-body">
            <h1>ABC Conception</h1>
            <?php
                // Display connection error messages if there are any
                if(isset($_GET['error'])) {
                    if(isset($_GET['message'])) {
                        echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
                    }
                }
                // Display of the password reset form if the request has not yet been made
                if (!isset($_GET['codeRequest']) && !isset($_GET['resetPassword']) && !isset($_GET['success'])) {
                    echo '<form method="post" action="../back-end/login/reset-password-treatment.php">
                                    <label for="lastname">
                                        <input type="text" id="lastname" name="lastname" placeholder="Votre nom" required />
                                    </label>
                                    <label for="email">
                                        <input type="email" id="email" name="email" placeholder="Votre adresse email" required />
                                    </label>
                                    <button type="submit">Réinitialiser</button><br>
                               </form>';
                }

                // Display of the code entry form received by email if the request has been made
                if (isset($_GET['codeRequest']) && isset($_SESSION['userRequested']) && isset($_SESSION['idRequest'])) {
                    echo '<form method="post" action="../back-end/login/reset-password-treatment.php">
                                    <label for="code">Veuillez entrez le code reçu sur votre adresse mail : 
                                        <input type="text" id="code" name="code" placeholder="Votre code" required/>
                                    </label>
                                    <button type="submit">Envoyer le code</button><br>
                               </form>';
                }

                // Display of the new password entry form
                if (isset($_GET['resetPassword']) && isset($_SESSION['userRequested']) && isset($_SESSION['idRequest'])) {
                        echo '<form method="post" action="../back-end/login/reset-password-treatment.php">
                                   <label for="password">
                                        <input type="password" id="password" name="password" placeholder="Mot de passe" required />
                                    </label>
                                    <label for="password_two">
                                        <input type="password" id="password_two" name="password_two" placeholder="Retapez votre mot de passe"  required />
                                    </label>
                                    <button type="submit">Réinitialiser le mot de passe</button><br>
                              </form>';
                }

                // If password is updated
                if (isset($_GET['success'])) {
                    echo '<div class="alert success">Votre mot de passe a bien été modifié.</div>';
                }

            ?>

            <p class="abc">Retourner sur la page de connexion <a href="../">ici</a>.</p>
        </div>
    </section>
</body>
</html>