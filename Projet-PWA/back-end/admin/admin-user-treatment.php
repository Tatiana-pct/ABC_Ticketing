<?php
// Initialize the session
session_start();
require_once '../classes/User.php';
require_once '../classes-requests/user.php';
require_once 'admin-utility.php';

if (isset ($_SESSION['researchUser'])) {
    $_SESSION['researchUser']=[];
}

if (isset($_GET['blockRequest'])) {
    $user_to_update = selectUserById(intval($_GET['id']));
    if ($user_to_update->getBlocked() === 0) {
        $user_to_update->setBlocked(1);
    } else {
        $user_to_update->setBlocked(0);
    }
    updateBlockedUser($user_to_update);
    if (isset($_GET['profile']) && intval($_GET['profile']) === 1) {
        header('location: ../profil/profil-treatment.php?id='.$_GET['id'].'&view=1');
        exit();
    }
}

if (isset($_GET['delete'])) {
    deleteUserById(intval($_GET['id']));
}

// On détermine sur quelle page on se trouve
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}

if(empty($_POST) || empty($_POST['general_search_user'])) {
    $result = pageParPage($currentPage);
} else {
    // Search with parameters
    $general_search = filter_var($_POST['general_search_user'], FILTER_SANITIZE_STRING);
    $result = selectFiltersUsers($general_search);
}

foreach ($result as $datas) {
    $_SESSION['researchUser'][] = serialize($datas);
}
// Redirection to ticket list
header('location: ../../pages/admin-users.php?page=' . $currentPage);