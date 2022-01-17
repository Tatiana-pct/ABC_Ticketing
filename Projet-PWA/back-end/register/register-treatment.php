<?php
// Initialize the session
session_start();
require_once '../classes/User.php';
require_once '../classes/Enterprise.php';
require_once '../classes-requests/user.php';

// Instantiation of the company and the linked user
$enterprise = new Enterprise(
    filter_var($_POST['name_enterprise'], FILTER_SANITIZE_STRING),
    filter_var($_POST['siret'], FILTER_SANITIZE_STRING),
    filter_var($_POST['website'], FILTER_SANITIZE_STRING),
    filter_var($_POST['address'], FILTER_SANITIZE_STRING)
);

$user= new User(
    filter_var($_POST['lastname'], FILTER_SANITIZE_STRING),
    filter_var($_POST['firstname'], FILTER_SANITIZE_STRING),
    $enterprise,
    filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
    filter_var($_POST['phone'], FILTER_SANITIZE_STRING),
    filter_var($_POST['password'], FILTER_SANITIZE_STRING)
);

//Retrieval of variables linked to data entered by the user who wants to register
$password_two = filter_var($_POST['password_two'], FILTER_SANITIZE_STRING);


//Check that all the mandatory information is filled
if($enterprise->isRequiredComplete() && $user->isRequiredComplete())
{
    // Verify if user exists or not
    if (!empty(selectUserByMail($user->getEmail()))) {
        header('location: ../../index.php?error=1&message=Un compte existe déjà avec cette adresse mail.');
        exit();
    }

    if (!empty(clientSiret($enterprise->getSiret()))) {
        header('location: ../../pages/register.php?error=1&message=Un compte existe déjà avec ce N° de siret.');
        exit();
    }

    // Vérification  password == password_two
    if($user->getPassword() !== $password_two){
        header('location: ../../pages/register.php?error=1&message=Vos mots de passe ne sont pas identiques.');
        exit();
    }

    //Password verification (syntax requested)
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/', $user->getPassword())) {
        header('Location: ../../pages/register.php?error=1&message=Un mot de passe valide aura :<br>- 8 à 15 caractères<br>- au moins une lettre minuscule<br>- au moins une lettre majuscule<br>- au moins un chiffre<br>- au moins un de ces caractères spéciaux: $ @ % * + - !');
        exit();
    }

    // HASH $secret for cookie 'auth'
    $secret = sha1($user->getEmail()).time();
    $secret = sha1($secret).time();
    $user->setSecret($secret);

    // HASH password
    $password = hash('sha256', $user->getPassword());
    $user->setPassword($password);

    // Insertion User in BDD
    insertUser($user);
    header('location:../../pages/register.php?success=1');
    exit();
}
