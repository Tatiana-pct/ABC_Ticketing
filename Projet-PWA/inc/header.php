<?php
include_once '../back-end/classes/User.php';
include_once '../back-end/classes/Enterprise.php';

if (isset($_SESSION['user']))
    $user_infos = unserialize($_SESSION['user']);
?>

<header>
    <nav>
    <img src="../img/burger.png" alt="logo menu" id="burger" >
        <ul id="nav">
            <li>
                <a href="../back-end/admin/admin-mail-treatment.php">
                    <?php
                        if ($user_infos->getAdmin() === 0)
                            echo "Mes demandes";
                        else
                            echo "Tous les mails";
                    ?>
                </a>
            </li>
            <?php if ($user_infos->getAdmin() !== 0) { ?>
                <li><a href="../back-end/admin/admin-user-treatment.php">Utilisateurs</a></li>
            <?php } ?>
            <li><a href="../back-end/profil/profil-treatment.php?id=<?=$user_infos->getId()?>&view=1">Mon profil</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="../back-end/login/logout.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>