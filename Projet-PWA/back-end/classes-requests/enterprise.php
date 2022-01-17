<?php

require_once '../config.php';
require_once '../classes/Enterprise.php';

/**
 * Function allowing to insert a company in BDD
 * @param Enterprise $enterprise
 * @return int|null
 */
function insertEnterprise(Enterprise $enterprise): ?int {
    // Creation of the unique ClientNumber for the new enterprise
    $enterprise->generateClientNumber();
    // Check if the ClientNumber does not already exist
    while (clientNumberExist($enterprise)) {
        $enterprise->generateClientNumber();
    }
    // Creation of the insertion request in BDD with the values set in parameters of the function insertEnterprise
    $insertEnterprise = "INSERT INTO `enterprises`(`clientNumber`, `name`, `siret`, `website`, `address`) VALUES (:clientNumber, :nameEnterprise, :siret, :webSite, :address);";

    // Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();

    // Preparation of the request
    $req = $pdo->prepare($insertEnterprise);

    // Association with the parameter values of the function
    $req->bindValue(':clientNumber', $enterprise->getClientNumber());
    $req->bindValue(':nameEnterprise', $enterprise->getName());
    $req->bindValue(':siret', $enterprise->getSiret());
    $req->bindValue(':webSite', $enterprise->getWebSite());
    $req->bindValue(':address', $enterprise->getAddress());
    // Execution of the request
    $lineChange = $req->execute();
    // Retrieve the ID of the company that has just been inserted
    if ($lineChange) {
        $enterprise->setId($pdo->lastInsertId());
    }
    //Closing the cursor allowing the query to be executed again
    $req->closeCursor();

    return $enterprise->getId();
}


/**
 * Function allowing to insert a company in BDD
 * @param Enterprise $enterprise
 * @return void
 */
function updateEnterprise(Enterprise $enterprise): void {
    // Creation of the insertion request in BDD with the values set in parameters of the function insertEnterprise
    $updateEnterprise = "UPDATE `enterprises` SET `name` = :nameEnterprise, `siret` = :siret, `website` = :webSite WHERE `id` = :id;";

    // Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();

    // Preparation of the request
    $req = $pdo->prepare($updateEnterprise);

    // Association with the parameter values of the function
    $req->bindValue(':nameEnterprise', $enterprise->getName());
    $req->bindValue(':siret', $enterprise->getSiret());
    $req->bindValue(':webSite', $enterprise->getWebSite());
    $req->bindValue(':id', $enterprise->getId());

    // Execution of the request
    $req->execute();

    //Closing the cursor allowing the query to be executed again
    $req->closeCursor();
}


/**
 * Function allowing to check if the clientNumberExist exists
 * @param Enterprise $enterprise
 * @return bool
 */
function clientNumberExist(Enterprise $enterprise): bool {

    // Creation of the insertion request in BDD with the values set in parameters of the function clientNumberExist()
    $checkClientNumber = "SELECT count(*) as correspond FROM `enterprises` WHERE clientNumber = :clientNumber";
    //Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    //Preparation of the request
    $req = $pdo->prepare($checkClientNumber);
    //Association with the parameter values of the function
    $req->bindValue(':clientNumber', $enterprise->getClientNumber());
    // Execution of the request
    $req->execute();
    $result = $req->fetch();
    //Closing the cursor allowing the query to be executed again
    $req->closeCursor();

    // Check the uniqueness of the clientNumber
    if ($result['correspond'] > 0){
        return true;
    }
    return false;
}