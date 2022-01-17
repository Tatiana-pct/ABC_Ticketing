<?php
session_start();
require_once '../classes-requests/user.php';
require_once '../classes/User.php';
require_once '../classes/Enterprise.php';

$user = unserialize($_SESSION['user']);

// Going to editing page
if(isset($_GET['id'])) {
    if (isset($_GET['view'])) {
        if (isset($_SESSION['userModify']))
            unset($_SESSION['userModify']);
        if (isset($_SESSION['userPendingEdit']))
            unset($_SESSION['userPendingEdit']);
    }

    if (isset($_GET['success']) && isset($_GET['message']) && intval($_GET['success']) === 1) {
        $successMessage = "&success=1&message=".$_GET['message'];
    } else {
        $successMessage = "";
    }

    if(intval($_GET['id']) === intval($user->getId())) {
        if (isset($_GET['view']) && intval($_GET['view']) === 1)
            header('location: ../../pages/profil.php?id='.$user->getId().$successMessage);
        else
            header('location: ../../pages/edit-profil.php?id='.$user->getId());
    } else {
        $user_modify = selectUserById(intval($_GET['id']));
        $user_modify->setPassword("");
        $user_modify->setSecret("");
        $_SESSION['userModify'] = serialize($user_modify);

        if (isset($_GET['view']) && intval($_GET['view']) === 1)
            header('location: ../../pages/profil.php?id='.$user_modify->getId().$successMessage);
        else
            header('location: ../../pages/edit-profil.php?id='.$user_modify->getId());
    }
    exit();
}

// Treatment update
if(isset($_POST) && !empty($_POST)) {
    // Check if it's my profil or no, and update user infos
    if (isset($_SESSION['userModify']))
        $user = unserialize($_SESSION['userModify']);
    if (isset($_SESSION['userPendingEdit']))
        $user = unserialize($_SESSION['userPendingEdit']);

    $user->setLastName(filter_var($_POST['lastname'], FILTER_SANITIZE_STRING));
    $user->setFirstName(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING));
    $user->setEmail(filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL));
    $user->setPhone(filter_var($_POST['phone'], FILTER_SANITIZE_STRING));
    if (isset($_POST['role']))
        $user->setAdmin(intval(filter_var($_POST['role'], FILTER_SANITIZE_STRING)));
    $user->getEnterprise()->setName(filter_var($_POST['enterpriseName'], FILTER_SANITIZE_STRING));
    $user->getEnterprise()->setSiret(filter_var($_POST['siret'], FILTER_SANITIZE_STRING));
    $user->getEnterprise()->setWebSite(filter_var($_POST['website'], FILTER_SANITIZE_URL));

    if (intval(unserialize($_SESSION['user'])->getId()) !== intval($user->getId()))
        $_SESSION['userModify'] = serialize($user);
    else
        $_SESSION['userPendingEdit'] = serialize($user);

    if(isset($_POST['old_password']) && !empty($_POST['old_password'])
        && !empty($_POST['new_password'])
        && !empty($_POST['confirm_new_password'])) {

        $old_password = filter_var($_POST['old_password'], FILTER_SANITIZE_STRING);
        $new_password = filter_var($_POST['new_password'], FILTER_SANITIZE_STRING);
        $confirm_new_password = filter_var($_POST['confirm_new_password'], FILTER_SANITIZE_STRING);

        // HASH password and verify in BDD
        $old_password = hash('sha256', $old_password);
        $userPasswordRegister = (selectUserById($user->getId()))->getPassword();
        if($old_password !== $userPasswordRegister) {
            header('location: ../../pages/edit-profil.php?id='.$user->getId().'&error=1&message=L\'ancien mot de passe est erroné.');
            exit();
        }

        // Vérification  password == password_two
        if($new_password !== $confirm_new_password){
            header('location: ../../pages/edit-profil.php?id='.$user->getId().'&error=1&message=Vos mots de passe ne sont pas identiques.');
            exit();
        }

        //Password verification (syntax requested)
        if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/', $new_password)) {
            header('location: ../../pages/edit-profil.php?id='.$user->getId().'&error=1&message=Un mot de passe valide doit contenir :<br>- 8 à 15 caractères<br>- au moins une lettre minuscule<br>- au moins une lettre majuscule<br>- au moins un chiffre<br>- au moins un de ces caractères spéciaux: $ @ % * + - !');
            exit();
        }

        $new_password = hash('sha256', $new_password);
        updatePassword($user->getId(), $new_password);
    }
    updateUser($user);
    if (isset($_SESSION['userModify']))
        unset($_SESSION['userModify']);
    if (isset($_SESSION['userPendingEdit']))
        unset($_SESSION['userPendingEdit']);
    if (intval(unserialize($_SESSION['user'])->getId()) === intval($user->getId())) {
        $user->setPassword("");
        $user->setSecret("");
        $_SESSION['user'] = serialize($user);
    }
    header('location: profil-treatment.php?id='.$user->getId().'&view=1&success=1&message=Le profil a été modifié');
    exit();
}