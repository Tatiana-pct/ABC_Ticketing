<?php

// Establishing connection values
// LOCALHOST
const DBHOST = 'localhost';
const DBNAME = 'bdd_abc_conception_ticket';
const DBUSER = 'root';
const DBPASS = '';
// ONLINE
//const DBHOST = 'abcmaivticket.mysql.db';
//const DBNAME = 'abcmaivticket';
//const DBUSER = 'abcmaivticket';
//const DBPASS = 'sAXt1RZ10DVP';

/**
 * BDD connection creation function
 * @return PDO|void
 */
function getConnexion()
{
    try {
        // Création de la chaîne de connexion à la BDD
        $dsn = 'mysql:host=' . DBHOST.';dbname=' . DBNAME.';charset=utf8';

        //création d'une instance de connexion à la base de données et ouverture de la connexion
        $pdo= new PDO($dsn, DBUSER, DBPASS);
        $pdo->exec("set names utf8");

        /**
         * Configures an attribute of the BDD manager
         * PDO::ERRMODE_EXCEPTION : throws an exception if PDO error
         */
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Catch exception
    } catch (PDOException $e) {
        $msg = 'ERREUR PDO dans' . $e->getFile() . ':' . $e->getLine() . ':' . $e->getMessage();
        die($msg);
    }
    // Return $pdo for the request
    return $pdo;
}
