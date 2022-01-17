<?php
// Initialize the session
session_start();
require_once '../classes/User.php';
require_once '../classes-requests/user.php';

// Session always active
if (isset($_SESSION['connect'])) {
    if($_SESSION["blocked"] === 1) {
        header('location: ../login/logout.php');
        exit();
    }
}

// if wanted remember user but to session
if (isset($_COOKIE['auth']) && !isset($_SESSION['connect'])) {
    $secret = filter_var($_COOKIE['auth'], FILTER_SANITIZE_STRING);

    $user = checkSecret($secret);
    if (!empty($user)) {
        $_SESSION['connect'] = 1;
        generateUserSession($user);
    }
}

// Connexion
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    // Get values of login form
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // HASH password
    $password = hash('sha256', $password);

    // Verify if user exists or not
    $user = selectUserByMail($email);
    if (empty($user) || $user->getPassword() !== $password) {
        header('location: ../../index.php?error=1&message=Vos identifiants ne sont pas reconnus.');
        exit();
    }

    // User not empty and passwords correspond, now just check if user is blocked or not
    if ($user->getBlocked() === 1) {
        header('location: ../../index.php?error=1&message=Votre compte a été bloqué. Veuillez contacter le support.');
    } else {
        $_SESSION['connect'] = 1;

        // creation of the 'auth' Cookie valid for 30 days used by the choice remember me
        if($_POST['remind-me']){
            setcookie('auth', $user->getSecret(), time()+60*60*24*30, '/', null, false, true);
        }
        generateUserSession($user);
        header('location: ../../index.php?success=1');
    }
    exit();
}

header('location: ../../');
exit();

function generateUserSession(User $user): void {
    $user->setPassword("");
    $user->setSecret("");
    $_SESSION['user'] = serialize($user);
}