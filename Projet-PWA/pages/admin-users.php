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
if (intval(unserialize($_SESSION['user'])->getAdmin()) === 0) {
    header('location: contact.php');
    exit();
}
?>
<section id="landing">
    <div class="container">
        <h1><b>Utilisateurs</b></h1>
    </div><!-- .container -->
</section><!-- #landing -->

<form name="form_filter" id="form_admin" action="../back-end/admin/admin-user-treatment.php" method="post" accept-charset="UTF-8" >
    <div class="filters">
        <div>
            <label for="general_search_user">
                <input type="text" name="general_search_user" placeholder="Votre recherche" maxlength="100" >
            </label>
        </div>
        <button type="submit" class="btn-search" formmethod="post">Rechercher</button>
        <button type="button" onclick="location.href='../back-end/admin/admin-user-treatment.php'" class="btn-search">Rafraîchir</button>
    </div>

</form>

<?php
// Checking the session variable ['researchUser'] which contains the results of the query
if (isset($_SESSION['researchUser']) && !empty($_SESSION['researchUser'])) {
    // Display of the results table header
    echo '<table>';
    tableHeadUsers();
    // Iterate on the emails contained in the result of the query
    foreach ($_SESSION['researchUser'] as $user)
    {
        tableLineUsers(unserialize($user));
    }
    echo '</table>';
} else {
    echo '<div>Pas de résultats !</div>';
}

if(isset($_SESSION['pagesUser']))
    $pages = intval($_SESSION['pagesUser']);

    // On détermine sur quelle page on se trouve
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}
?>

<nav id="pagination">
    <ul>
        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
        <?php if(($currentPage !== 1 ) ? "disabled" : "" ) : ?>
        <li >
            <a id="page-item" href="../back-end/admin/admin-user-treatment.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
        </li>
        <?php endif; ?>
        <?php for($page = 1; $page <= $pages; $page++): ?>
            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
            <li class="page-item1 <?= ($currentPage == $page) ? "active" : "" ?>">
                <a href="../back-end/admin/admin-user-treatment.php?page=<?= $page ?>" class="page-link-page"><?= $page ?></a>
            </li>
        <?php endfor ?>
        <?php if(($currentPage >= 1) ? "disabled" : "") : ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li id="page-item2">
            <a href="../back-end/admin/admin-user-treatment.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
        </li>
        <?php endif; ?>

    </ul>
</nav>

<script src="../js/app.js"></script>

</body>
</html>