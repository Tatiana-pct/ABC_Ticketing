<?php
require_once '../config.php';

/**
 * Function to create the verification code to allow the reset of password
 * @param int $idUser
 * @return array
 */
function insertRecoveringRequest(int $idUser): array {
    $code = generateRecoveringCode();

    $insertRecoveringRequest = "INSERT INTO `recovering` (`id_user`, `code`) "
                                ."VALUES (:idUser, :code)";
    $pdo = getConnexion();

    $req = $pdo->prepare($insertRecoveringRequest);
    $req->bindValue(':idUser', $idUser);
    $req->bindValue(':code', $code);
    $req->execute();
    $idRequest = $pdo->lastInsertId();
    $req->closeCursor();

    return array('idRequest' => $idRequest, 'code' => $code);
}


/**
 * Function allowing to update verification code
 * @param int $id
 * @return int
 */
function updateRecoveringRequest(int $id): int {
    $code = generateRecoveringCode();
    $updateRecoveringRequest = "UPDATE `recovering` SET `code` = :code "
                                ."WHERE id = :id";
    $pdo = getConnexion();

    $req = $pdo->prepare($updateRecoveringRequest);
    $req->bindValue(':code', $code);
    $req->bindValue(':id', $id);
    $req->execute();
    $req->closeCursor();

    return $code;
}


/**
 * Function to search for the verification code according to the USER
 * @param int $idUser
 * @return array|null
 */
function selectRecoveringByIdUser(int $idUser): ?array {
    $selectRequestByIdUser = "SELECT * "
                            ."FROM `recovering` "
                            ."WHERE id_user = :idUser";
    $pdo = getConnexion();

    $req = $pdo->prepare($selectRequestByIdUser);
    $req->bindValue(':idUser', $idUser);
    $req->execute();
    $result = $req->fetch();

    $resultArray = array();
    if (!empty($result)) {
        $resultArray = array(
            "idRequest" => $result['id'],
            "id_user" => $result['id_user'],
            "code" => $result['code'],
            "sendDate" => $result['sendDate']
        );
    }
    $req->closeCursor();

    return $resultArray;
}


/**
 * Function to delete the verification request
 * @param int $id
 */
function deleteRecoveringRequest(int $id) {
    $deleteRecoveringRequest = "DELETE FROM `recovering` WHERE `id` = :id";

    $pdo = getConnexion();
    $req = $pdo->prepare($deleteRecoveringRequest);
    $req->bindValue(':id', $id);
    $req->execute();
    $req->closeCursor();
}


/**
 * Function to generate a verification code
 * @return string
 */
function generateRecoveringCode(): string {
    return strval(mt_rand(100000, 999999));
};