<?php
// Initialize the session
session_start();
require_once '../classes-requests/user.php';
require_once 'reset-password-requests.php';

$isSessionSet = isset($_SESSION['userRequested']) && isset($_SESSION['idRequest']);

// Processing the first page of the reset
if (isset($_POST['lastname']) && isset($_POST['email'])) {
    $lastName = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $user = selectUserByMail($email);
    if (!empty($user) && strtoupper($user->getLastname()) === strtoupper($lastName)) {
        // Check that a request is not already in progress, otherwise modify it
        $recoveringRequest = selectRecoveringByIdUser($user->getId());
        if (!empty($recoveringRequest)) {
            $newCode = updateRecoveringRequest($recoveringRequest['idRequest']);
            $requestInfos = array('idRequest' => $recoveringRequest['idRequest'], 'code' => $newCode);
        } else {
            // BDD insertion
            $requestInfos = insertRecoveringRequest($user->getId());
        }

        // Send mail to user with the vérification code
        $to = $user->getEmail();
        $subject = "Réinitialisation de votre mot de passe ABC-Conception";
        $mailMessage = "Bonjour ".$user->getLastname()
                        ." ".$user->getFirstname().",\r\n\r\n"
                        ."Voici votre code de validation pour réinitialiser votre mot de passe :\r\n\r\n"
                        .$requestInfos['code']."\r\n\r\n"
                        ."Ce dernier n'est valable que 24 heures.";
        $headers = array(
            'Content-type' => 'text/html',
            'charset' => 'uft-8',
            'From' => 'support@abcconception.fr'
        );
        mail($to, $subject, nl2br($mailMessage), $headers);

        // Create session and redirect user to next step
        $_SESSION['userRequested'] = $user->getId();
        $_SESSION['idRequest'] = $requestInfos['idRequest'];
        header('location: ../../pages/reset-password.php?codeRequest=1');
    } else {
        header('location: ../../pages/reset-password.php?error=1&message=Au moins l\'une des informations fournises est incorrete.');
    }
}

// Verify code that user enter (code received by email)
if ($isSessionSet && isset($_POST['code'])) {
    $code = filter_var($_POST['code'], FILTER_SANITIZE_STRING);
    $recoveringRequest = selectRecoveringByIdUser($_SESSION['userRequested']);
    $sessionExist = strval($_SESSION['idRequest']) === $recoveringRequest['idRequest'] && strval($_SESSION['userRequested']) === $recoveringRequest['id_user'];

    if ($sessionExist) {
        if ($code === $recoveringRequest['code'])
            header('location: ../../pages/reset-password.php?resetPassword=1');
        else
            header('location: ../../pages/reset-password.php?codeRequest=1&error=1&message=Veuillez vérifier votre code.');
    } else {
        header('location: ../../index.php');
    }
}


// New password verification and update
if ($isSessionSet && isset($_POST['password']) && isset($_POST['password_two'])) {
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $password_two = filter_var($_POST['password_two'], FILTER_SANITIZE_STRING);

    if($password !== $password_two){
        header('location: ../../pages/reset-password.php?resetPassword=1&error=1&message=Vos mots de passe ne sont pas identiques.');
        exit();
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/', $password)) {
        header('Location: ../../pages/reset-password.php?resetPassword=1&error=1&message=Un mot de passe valide aura :<br>- 8 à 15 caractères<br>- au moins une lettre minuscule<br>- au moins une lettre majuscule<br>- au moins un chiffre<br>- au moins un de ces caractères spéciaux: $ @ % * + - !');
        exit();
    }

    $passwordHash = hash('sha256', $password);
    updatePassword($_SESSION['userRequested'], $passwordHash, $_SESSION['idRequest']);

    session_unset();
    session_destroy();

    header('location: ../../pages/reset-password.php?success=1');
}