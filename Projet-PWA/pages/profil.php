<?php
session_start();
require_once '../back-end/classes/User.php';
require_once '../back-end/classes/Enterprise.php';
include_once '../inc/head.php';

if (isset($_SESSION['userModify']))
    $user = unserialize($_SESSION['userModify']);
else
    $user = unserialize($_SESSION['user']);

if (intval(unserialize($_SESSION['user'])->getAdmin()) === 0
    && intval(unserialize($_SESSION['user'])->getId()) !== intval($_GET['id'])) {
    header('location: ../back-end/profil/profil-treatment.php?id='.unserialize($_SESSION['user'])->getId().'&view=1');
    exit();
}
?>

<body>
<button id="add-button"><i class="fas fa-download"></i></button>

    <?php include_once '../inc/header.php'; ?>
    <section id="landing">
        <div class="container">
            <h1>
                <b>
                    <?=
                    (intval(unserialize($_SESSION['user'])->getId()) === intval($user->getId())) ?
                        'Mon profil' : 'Profil de <br>'.$user->getLastName().' '.$user->getFirstName()
                    ?>
                </b>
            </h1>
        </div>
    </section>

    <?php
        if (isset($_GET['success'])) {
            echo '<div id="message" class="alert success">'.$_GET['message'].'</div><br>';
        }
    ?>

    <table>
        <tr>
            <td class="bold">Nom :</td>
            <td colspan="2"><?=$user->getLastName()?></td>
        </tr>
        <tr>
            <td class="bold">Prénom :</td>
            <td colspan="2"><?=$user->getFirstName()?></td>
        </tr>
        <tr>
            <td class="bold">Email :</td>
            <td colspan="2"><?=$user->getEmail()?></td>
        </tr>
        <tr>
            <td class="bold">Téléphone :</td>
            <td colspan="2"><?=$user->getPhone()?></td>
        </tr>
        <?php
            if ($user->getId() !== intval(unserialize($_SESSION['user'])->getId())
                && intval(unserialize($_SESSION['user'])->getAdmin()) === 1) {
        ?>
            <tr>
                <td class="bold">Bloqué :</td>
                <td colspan="2"><?=$user->getBlocked() === 1 ? "Oui" : "Non"?></td>
            </tr>
        <?php }?>
        <tr>
            <td class="bold">Entreprise :</td>
        </tr>
        <tr>
            <td></td>
            <td class="bold">N° client :</td>
            <td><?=$user->getEnterprise()->getClientNumber()?></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold">Nom :</td>
            <td><?=$user->getEnterprise()->getName()?></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold">SIRET :</td>
            <td><?=$user->getEnterprise()->getSiret()?></td>
        </tr>
        <tr>
            <td></td>
            <td class="bold">Site(s) Web :</td>
            <td>
                <?php
                    $websites = $user->getEnterprise()->getWebsiteArray();
                    if (count($websites) > 0) {
                        echo "<ul class='list-site'>";
                        foreach ($websites as $website) {
                            echo "<li><a class='site-link' href='https://".$website."' target='_blank'>".$website."</a></li>";
                        }
                        echo "</ul>";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td id="empty-td" colspan="3"></td>
        </tr>
        <tr>
            <td colspan="3">
                <button class="btn btn-profil" onclick="location.href='../back-end/profil/profil-treatment.php?id=<?=$user->getId()?>'">Modifier</button>
                <?php if (intval(unserialize($_SESSION['user'])->getAdmin()) === 0) {?>
                    <button class="btn btn-profil" onclick="location.href='contact.php'">Retour</button>
                <?php
                    } else {
                        if(intval(unserialize($_SESSION['user'])->getId()) !== $user->getId()) {
                ?>
                    <button class="btn btn-profil" onclick="location.href='../back-end/admin/admin-user-treatment.php?id=<?=$user->getId()?>&blockRequest=1&profile=1'"><?=$user->getBlocked() === 1 ? "Débloquer" : "Bloquer"?></button>
                    <?php } ?>
                    <button class="btn btn-profil" onclick="location.href='../back-end/admin/admin-user-treatment.php'">Retour</button>
                <?php } ?>
            </td>
        </tr>
    </table>
    <script src="../js/app.js"></script>
</body>
</html>