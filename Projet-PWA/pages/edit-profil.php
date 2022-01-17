<?php
session_start();
require_once '../back-end/classes/User.php';
require_once '../back-end/classes/Enterprise.php';
include_once '../inc/head.php';

if (isset($_SESSION['userModify']))
    $user = unserialize($_SESSION['userModify']);
else if (isset($_SESSION['userPendingEdit']))
    $user = unserialize($_SESSION['userPendingEdit']);
else
    $user = unserialize($_SESSION['user']);

if (intval(unserialize($_SESSION['user'])->getAdmin()) === 0
    && intval(unserialize($_SESSION['user'])->getId()) !== intval($_GET['id'])) {
    header('location: ../back-end/profil/profil-treatment.php?id='.unserialize($_SESSION['user'])->getId());
    exit();
}
?>

<body>
<?php
include_once '../inc/header.php';
?>
    <section id="landing">
        <div class="container">
            <h1>
                <b>
                    <?=
                    unserialize($_SESSION['user'])->getId() === $user->getId() ?
                        'Modification de mon profil' : 'Modification du profil <br>'.$user->getLastName().' '.$user->getFirstName()
                    ?>
                </b>
            </h1>
        </div>
    </section>

    <section id="formulaire">
        <div class="container">
            <form action="../back-end/profil/profil-treatment.php" method="POST">
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="alert error">'.utf8_decode($_GET['message']).'</div><br>';
                }
                ?>
                <input type="hidden" name="idProfil" value="<?=$_GET['id']?>">
                <label for="lastname">
                    <p>Nom*</p>
                    <input type="text" id="lastname" name="lastname" placeholder="Votre nom" value="<?= !empty($user) ? $user->getLastName() : ""?>" maxlength="100" required>
                </label>
                <label for="firstname">
                    <p>Prénom*</p>
                    <input type="text" id="firstname" name="firstname" placeholder="Votre prénom" value="<?= !empty($user) ? $user->getFirstName() : ""?>" maxlength="100" required>
                </label>
                <label for="mail">
                    <p>Email*</p>
                    <input type="email" id="mail" name="mail" placeholder="Votre email" value="<?=!empty($user) ? $user->getEmail() : ""?>" maxlength="255" required>
                </label>
                <label for="phone">
                    <p>Téléphone*</p>
                    <input type="tel" id="phone" name="phone" maxlength="20"
                           pattern="\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}"
                           placeholder="Téléphone"
                           value="<?= !empty($user) ? $user->getPhone() : ""?>"
                           required>
                </label>

                <?php
                    if (intval(unserialize($_SESSION['user'])->getId()) !== intval($user->getId())
                        && intval(unserialize($_SESSION['user'])->getAdmin()) !== 0) {
                ?>
                    <label for="role">
                        <p>Role*</p>
                        <select id="role" name="role" required>
                            <option value="0" <?=$user->getAdmin() === 0 ? 'selected' : ''?>>Utilisateur</option>
                            <option value="1" <?=$user->getAdmin() === 1 ? 'selected' : ''?>>Administrateur</option>
                            <?php
                                if (intval(unserialize($_SESSION['user'])->getAdmin()) === 2) {
                                    $is_selected = $user->getAdmin() === 2 ? 'selected' : '';
                                    echo "<option value='2'".$is_selected.">Administrateur ABC Conception</option>";
                                }
                            ?>
                        </select>
                    </label>
                <?php
                    }
                ?>

                <label for="enterpriseName">
                    <p>Nom de l'entreprise*</p>
                    <input type="text" id="enterpriseName" name="enterpriseName" placeholder="Votre entreprise" value="<?=!empty($user) ? $user->getEnterprise()->getName() : ""?>" required>
                </label>
                <label for="siret">
                    <p>n°SIRET*</p>
                    <input type="text" id="siret" name="siret" placeholder="Votre site" value="<?=!empty($user) ? $user->getEnterprise()->getSiret() : ""?>" required>
                </label>
                <label for="website">
                    <p>Site(s) web*<br><span>(séparé par une virgule OU un point-virgule)</span></p>
                    <input type="text" id="website" name="website" placeholder="Votre site" value="<?=!empty($user) ? $user->getEnterprise()->getWebSite() : ""?>" required>
                </label>

                <?php if(!isset($_SESSION['userModify'])) {?>
                <label for="old_password">
                    <p>Ancien mot de passe</p>
                    <input type="password" id="old-password" name="old_password" placeholder="Ancien mot de passe"/>
                </label>
                <label for="new_password">
                    <p>Nouveau mot de passe</p>
                    <input type="password" id="new-password" name="new_password" placeholder="Nouveau mot de passe"/>
                </label>
                <label for="confirm_new_password">
                    <p>Confirmation nouveau mot de passe</p>
                    <input  type="password" id="confirm-new-password" name="confirm_new_password" placeholder="Retapez votre mot de passe"/>
                </label>
                <?php } ?>

                <button id="submit" class="btn" type="submit">Modifier</button>
                <button class="btn" type="button" onclick="location.href='../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>&view=1'">Annuler</button>
            </form>
        </div>
    </section>
    <script src="../js/app.js"></script>
    <?php unset($_SESSION['userPendingEdit']); ?>
</body>
</html>