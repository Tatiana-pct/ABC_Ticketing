<?php
// Initialize the session
session_start();
require_once '../classes/Mail.php';
require_once '../classes/User.php';
require_once '../classes/Enterprise.php';
require_once '../classes-requests/mail.php';
require_once '../classes-requests/user.php';

//Checking that the form fields are filled
if (!empty($_POST))
{
    $user = unserialize($_SESSION['user']);
    // Creation and sending of the email
    $mail = new Mail(
        $user,
        filter_var($_POST['monselect-site'], FILTER_SANITIZE_STRING),
        filter_var($_POST['monselect-object'], FILTER_SANITIZE_STRING),
        filter_var($_POST['message'], FILTER_SANITIZE_STRING),
        filter_var($_POST['speedcall'], FILTER_VALIDATE_BOOLEAN)
    );

    //  Check if speedCall is requested
    if ($mail->isSpeedCall()) {
        $mail->setSpeedCallDate(filter_var($_POST["monselect-date"], FILTER_SANITIZE_STRING));
        $mail->setSpeedCallHours(filter_var($_POST["monselect-hours"], FILTER_SANITIZE_STRING));
    }

    if (strpos(unserialize($_SESSION['user'])->getEnterprise()->getWebsite(), $mail->getWebsite()) === false) {
        header('location: ../../pages/contact.php?error=1&message=Ce site n\'est pas référencé dans votre liste, veuillez l\'ajouter dans votre site');
        exit();
    }

    // Sending the contact request email to the admin
    $mail->sendContactMail();

    // BDD insertion
    try {
        insertMail($mail);
    } catch (Exception $e) {
        $msg = 'ERREUR PDO dans' . $e->getFile() . ':' . $e->getLine() . ':' . $e->getMessage();
        header('location: ../../pages/contact.php?error=1&message=Une erreur est survenu, veuillez réessayer plus tard ou contacter le support');
        exit();
    }

    header('location: ../../pages/contact.php?success=1&message=Votre demande a bien été envoyée à ABC-Conception');
    exit();
}