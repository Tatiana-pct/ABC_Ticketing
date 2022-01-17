<?php
session_start();
require_once '../back-end/admin/admin-utility.php';
require_once '../inc/head.php';
include_once '../back-end/classes/Mail.php';
include_once '../back-end/classes/User.php';
include_once '../back-end/classes/Enterprise.php';
?>

<body>
<button id="add-button"><i class="fas fa-download"></i></button>
<?php
include_once '../inc/header.php';
?>
<section id="landing">
    <div class="container">
        <h1>
            <b>
                <?php
                if ($user_infos->getAdmin() === 0)
                    echo "Mes demandes";
                else
                    echo "Toutes les demandes";
                ?>
            </b>
        </h1>
    </div><!-- .container -->
</section><!-- #landing -->

<form name="form_filter" id="form_admin" action="../back-end/admin/admin-mail-treatment.php" method="post" accept-charset="UTF-8">
    <div class="filters">
        <div>
            <label for="general_search_mails">
                <input type="text" name="general_search_mails" placeholder="Votre recherche" maxlength="100">
            </label>
        </div>
        <label for="object">
            <select name="object">
                <option value="" disabled selected>Objet de la demande</option>
                <option value="Modification du site">Modification du site</option>
                <option value="Facturation">Facturation</option>
                <option value="Dysfonctionnement">Dysfonctionnement</option>
                <option value="Besoin d\'assistance">Besoin d'assistance</option>
                <option value="Autre">Autre</option>
            </select>
        </label>
        <label for="speedCall">
            <select name="speedCall">
                <option value="" disabled selected>Demande de rappel</option>
                <option value="1">Oui</option>
                <option value=false >Non</option>
            </select>
        </label>
        <button type="submit" class="btn-search" formmethod="post">Rechercher</button>
        <button type="button" onclick="location.href='../back-end/admin/admin-mail-treatment.php'" class="btn-search">Rafraîchir</button>
    </div>
</form>

<?php
    // Checking the session variable ['research_mail'] which contains the results of the query
    if (isset($_SESSION['research_mail']) && !empty($_SESSION['research_mail'])) {
        // Create the table if there is result
        echo '<table>';
        tableHeadMails();
        // Iterate on the emails contained in the result of the query
        foreach ($_SESSION['research_mail'] as $mail)
        {
            tableLineMails(unserialize($mail));
        }
        echo '</table>';
    } else {
        echo '<div>Pas de résultats !</div>';
    }


    if(isset($_SESSION['pagesMail']))
        $pagesMail = intval($_SESSION['pagesMail']);

        // On détermine sur quelle page on se trouve
    if(isset($_GET['pageMail']) && !empty($_GET['pageMail'])){
        $currentPageMail = (int) strip_tags($_GET['pageMail']);
    }else{
        $currentPageMail = 1;
    }
?>

<nav id="pagination">
    <ul>
        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
        <?php if(($currentPageMail !== 1 ) ? "disabled" : "" ) : ?>
        <li >
            <a id="page-item" href="../back-end/admin/admin-mail-treatment.php?pageMail=<?= $currentPageMail - 1 ?>" class="page-link">Précédente</a>
        </li>
        <?php endif; ?>
        <?php for($pageMail = 1; $pageMail <= $pagesMail; $pageMail++): ?>
            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
            <li class="page-item1 <?= ($currentPageMail == $pageMail) ? "active" : "" ?>">
                <a href="../back-end/admin/admin-mail-treatment.php?pageMail=<?= $pageMail ?>" class="page-link-page"><?= $pageMail ?></a>
            </li>
        <?php endfor ?>
        <?php if(($currentPageMail >= 1) ? "disabled" : "") : ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li id="page-item2">
            <a href="../back-end/admin/admin-mail-treatment.php?pageMail=<?= $currentPageMail + 1 ?>" class="page-link">Suivante</a>
        </li>
        <?php endif; ?>

    </ul>
</nav>


<script src="../js/app.js"></script>

</body>
</html>