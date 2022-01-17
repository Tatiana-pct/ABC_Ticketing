<?php
    // Use of different <meta> depending on the page displayed on the browser
    $page = basename($_SERVER['REQUEST_URI']);
    $isIndex = !(gettype(strpos(basename($page), 'index.php')) === "boolean")
            || basename($page) === "Projet-PWA"
            || basename($page) === "";
    $needLoginCss = $isIndex !== false
                    || strpos(basename($page), 'register.php') !== false
                    || strpos(basename($page), 'reset-password.php') !== false;
    $needAdminCss = strpos(basename($page), 'admin') !== false
                    || strpos(basename($page), 'mails.php') !== false;

    if (!$needLoginCss && !isset($_SESSION['user'])) {
        header('location: ../back-end/login/login-treatment.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="description" content="Conception & développement de sites web, visibilité sur internet, référencement et publicité. Expertise gratuite et sans engagement de votre projet en 24h.">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Site web + Référencement à partir de 1200€ | ABC Conception">
    <meta property="og:description" content="Conception & développement de sites web, visibilité sur internet, référencement et publicité. Expertise gratuite et sans engagement de votre projet en 24h.">
    <meta property="og:url" content="https://www.abc-conception.com/contact.php">
    <meta property="og:image" content="https://www.abc-conception.com/img/opengraph_ban_fb_2.jpg">
    <meta property="og:site_name" content="ABC Conception">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="Conception & développement de sites web, visibilité sur internet, référencement et publicité. Expertise gratuite et sans engagement de votre projet en 24h.">
    <meta name="twitter:title" content="Site web + Référencement à partir de 1200€ | ABC Conception">
    <meta name="twitter:site" content="@adrien_binet">
    <?php
        if ($needLoginCss && !$isIndex && !$needAdminCss) {
            echo '<link rel="stylesheet" href="../css/login.css">';
        } else if ($isIndex) {
            echo '<link rel="stylesheet" href="css/login.css">';
        } else if ($needAdminCss) {
            echo '<link rel="stylesheet" href="../css/admin.css">';
        } else{
            echo '<link rel="stylesheet" href="../css/style.css">';
        }
    ?>

    <!-- Images and manifest -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $isIndex ? 'img/icons/apple-touch-icon.png' : '../img/icons/apple-touch-icon.png' ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $isIndex ? 'img/icons/favicon-32x32.png' : '../img/icons/favicon-32x32.png' ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $isIndex ? 'img/icons/favicon-16x16.png' : '../img/icons/favicon-16x16.png' ?>">
    <?php if ($isIndex) { ?>
        <link rel="manifest" href="manifest.json">
    <?php } else { ?>
        <link rel="manifest" href="../manifest.json">
    <?php } ?>


    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b513040493.js" crossorigin="anonymous"></script>

    <title>Systeme de ticket</title>
</head>