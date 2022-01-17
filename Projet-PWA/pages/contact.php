<?php
session_start();
require_once '../inc/head.php';
include_once '../back-end/classes/User.php';
include_once '../back-end/classes/Enterprise.php';

if (isset($_SESSION['user']))
    $user = unserialize($_SESSION['user']);
else
    $user = null;
?>

<body>

    <button id="add-button"><i class="fas fa-download"></i></button>

<?php
include_once '../inc/header.php';
?>

<section id="landing">
    <div class="container">
        <h1><b>Contact</b></h1>
    </div><!-- .container -->
</section><!-- #landing -->


<section id="formulaire">
    <div class="container">
        <?php
        if (isset($_GET['success'])) {
            echo '<div id="message" class="alert success">'.$_GET['message'].'</div><br>';
        }
        if (isset($_GET['error'])) {
            echo '<div id="message" class="alert error">'.$_GET['message'].'</div><br>';
        }
        ?>
        <div class="div-btn-info">
            <button id="btn-info" class="btn-info">Voir mes informations personnels</button>
        </div><!-- .div-btn-info -->
        <form action="../back-end/mails/mail-treatment.php" method="POST">
            <div id="div-info" hidden>
            <label for="lastname">
                <p>Votre nom*</p>
                <input type="text" id="lastname" name="lastname" placeholder="Votre nom" value="<?= !empty($user) ? $user->getLastName() : ""?>" maxlength="100" required <?= !empty($user) ? "disabled" : "" ?>>
                <a class='btn-action' href="../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>"><i class='fas fa-edit'></i></a>
            </label>
            <label for="firstname">
                <p>Votre prénom*</p>
                <input type="text" id="firstname" name="firstname" placeholder="Votre prénom" value="<?= !empty($user) ? $user->getFirstName() : ""?>" maxlength="100" required <?= !empty($user) ? "disabled" : "" ?>>
                <a class='btn-action' href="../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>"><i class='fas fa-edit'></i></a>
            </label>
            <label for="mail">
                <p>Email*</p>
                <input type="email" id="mail" name="mail" placeholder="Votre email" value="<?=!empty($user) ? $user->getEmail() : ""?>" maxlength="255" required <?= !empty($user) ? "disabled" : "" ?>>
                <a class='btn-action' href="../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>"><i class='fas fa-edit'></i></a>
            </label>
            <label for="phone">
                <p>Téléphone*</p>
                <input type="tel" id="phone" name="phone" maxlength="20"
                       pattern="\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}"
                       placeholder="Téléphone"
                       value="<?= !empty($user) ? $user->getPhone() : ""?>"
                       <?= !empty($user) ? "disabled" : "" ?>
                       required>
                <a class='btn-action' href="../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>"><i class='fas fa-edit'></i></a>
            </label>
            </div><!-- .div-info -->
            <br>
            <br>
            <label for="monselect-site">
                <p>Site web concerné*</p>
                <select id="monselect-site" name="monselect-site" required>
                    <option value="" disabled selected>Site concerné par votre demande...</option>
                    <?php
                        $websites = $user->getEnterprise()->getWebsiteArray();
                        foreach ($websites as $website) {
                            if (!empty(trim($website)))
                                echo "<option value='".trim($website)."'>".trim($website)."</option>";
                        }
                    ?>
                </select>
                <a class='btn-action' href="../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>"><i class='fas fa-edit'></i></a>
            </label>
            <br>
            <br>
            <label for="monselect-object">
                <p>Selectionnez l'objet de votre demande*</p>
                <select id="monselect-object" name="monselect-object" required>
                    <option value="" disabled selected>Objet de votre demande...</option>
                    <option value="Modification du site">Modification du site</option>
                    <option value="Facturation">Facturation</option>
                    <option value="Dysfonctionnement">Dysfonctionnement</option>
                    <option value="Besoin d'assistance">Besoin d'assistance</option>
                    <option value="Autre">Autre</option>
                </select>
            </label>
            <br>
            <br>
            <label for="message-user">
                <p>Décrivez-moi votre demande*</p>
                <textarea id="message-user" name="message" cols="30" rows="10" placeholder="Votre message ici..." required></textarea>
            </label>
            <br>
            <br>
            <label for="speedcall">
                <input type="hidden" id="h-speedcall" name="speedcall" value="false">
                <div id="time-choice" hidden>
                    <div id="croix-rouge">
                        <p><i class="far fa-times-circle">Annuler la demande de rappel</i></p><br>
                    </div><!-- #croix-rouge -->
                    <span>Du lundi au vendredi :*</span>
                    <span id="para"></span>
                    <br>
                    <input type="date" id="monselect-date" name="monselect-date" min="<?= date("Y-m-d"); ?>">
                    <br>
                    <br>
                    <span>De 14h30 à 17h30 :*</span>
                    <br>
                    <select id="monselect-hours" name="monselect-hours">
                        <option value="" disabled selected>Sélectionnez un créneau...</option>
                        <option value="14h-14h30">14h à 14h30</option>
                        <option value="14h30-15h">14h30 à 15h</option>
                        <option value="15h-15h30">15h à 15h30</option>
                        <option value="15h30-16h">15h30 à 16h</option>
                        <option value="16h-16h30">16h à 16h30</option>
                        <option value="16h30-17h">16h30 à 17h</option>
                    </select>
                </div><!-- #time-choice -->
                <div id="buttons">
                    <div id="speedcall" class="btn">
                        <p title="Programmer un rappel">&#10149; Être rappelé &#9990; </p>
                    </div><!-- #speedcall  .btn -->
                    <span id="span-ou"> OU </span>
                    <button id="submit" class="btn" type="submit">Envoyer</button>
                </div><!-- #buttons -->
            </label>
        </form>
    </div><!-- .container -->
</section><!-- #formulaire -->
<script src="../js/app.js"></script>
</body>
</html>