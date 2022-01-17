<?php
// Initialize the session
session_start();
require_once '../classes/Mail.php';
require_once '../classes/User.php';
require_once '../classes/Enterprise.php';
require_once '../classes-requests/mail.php';
require_once 'admin-utility.php';

// Verification $_SESSION['research'] exist due to user search
if (isset ($_SESSION['research_mail'])) {
    $_SESSION['research_mail']=[];
}
$user = unserialize($_SESSION['user']);

if (isset($_GET['delete'])) {
    deleteMailById(intval($_GET['id']));
}


// On détermine sur quelle page on se trouve
if(isset($_GET['pageMail']) && !empty($_GET['pageMail'])){
    $currentPageMail = (int) strip_tags($_GET['pageMail']);
}else{
    $currentPageMail = 1;
}
// Search without parameters
if (empty($_POST) || (empty($_POST['general_search_mails']) && empty($_POST['object']) && empty($_POST['speedCall']))) {
    if (intval($user->getAdmin()) === 1)
        $result = pageParPageMail($currentPageMail);
    else
        $result = pageParPageMail($currentPageMail, null, null, null, intval($user->getId()));
} else {
    // Search with parameters
    if (empty($_POST['general_search_mails']))
        $general_search = null;
    else
        $general_search = filter_var($_POST['general_search_mails'], FILTER_SANITIZE_STRING);

    if (empty($_POST['object']))
        $object = null;
    else
        $object = filter_var($_POST['object'], FILTER_SANITIZE_STRING);

    if (empty($_POST['speedCall']))
        $speedCall = null;
    else
        $speedCall = filter_var($_POST['speedCall'], FILTER_SANITIZE_STRING);

    // If Admin ABC
    if ($user->getAdmin() === 1)
        $id_user= null;
    else
        $id_user = $user->getId();

    $result= selectFiltersMails($general_search, $object, $speedCall,$id_user);
}

// Send all results
foreach ($result as $datas) {
    $_SESSION['research_mail'][] = serialize($datas);
}

// Redirection to ticket list
header('location: ../../pages/mails.php?pageMail=' . $currentPageMail);

