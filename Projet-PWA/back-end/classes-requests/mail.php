<?php

require_once '../config.php';
require_once '../classes/Enterprise.php';
require_once '../classes/User.php';
require_once '../classes-requests/user.php';
require_once '../classes/Mail.php';

/**
 * Function allowing to insert a Ticket in BDD
 * @param Mail $mail
 */
function insertMail(Mail $mail)
{
    // Creation of the insertion request in BDD with the values set in parameters of the function insertMail()
    $sqlInsert = 'INSERT INTO mails (id_user, website_concerned, object, message, speedcall, callDate, callHour)
                            VALUES (:id, :website_concerned, :object, :message, :speedcall, :callDate, :callHour)';

    // Check if the required data is present to avoid unnecessary access to BDD
    if ($mail->isRequiredComplete()) {
        //Establishment of the connection to the database and recovery of the PDO
        $pdo = getConnexion();

        //Preparation of the request
        $req = $pdo->prepare($sqlInsert);

        //Association with the parameter values of the function
        $req->bindValue(':id', $mail->getUser()->getId());
        $req->bindValue(':website_concerned', $mail->getWebsite());
        $req->bindValue(':object', $mail->getObject());
        $req->bindValue(':message', $mail->getMessage());
        $req->bindValue(':speedcall', $mail->isSpeedCall(), PDO::PARAM_BOOL);
        $req->bindValue(':callDate', $mail->getSpeedCallDate());
        $req->bindValue(':callHour', $mail->getSpeedCallHours());

        // Execution of the request
        $insert = $req->execute();
        //Closing the cursor allowing the query to be executed again
        $req->closeCursor();

    }
}

/**
 * General into mails search function
 */
function selectAllMails(): array
{
    // Creation of the insertion request in BDD with the values set in parameters of the function selectAllMails
    $selectAll = "SELECT * "
        ."FROM mails as m INNER JOIN users as u on m.id_user = u.id "
        ."INNER JOIN enterprises as e ON u.id_enterprise = e.id "
        ."ORDER BY sendDate DESC";
    // get connexion to BDD
    $pdo=getConnexion();
    // Execution of the request
    $queryResult = $pdo->query($selectAll);

    $results = $queryResult->fetchAll();

    $mails = Array();
    foreach ($results as $result) {
        $mail = createMail($result);
        array_push($mails, $mail);
    }
    //Closing the cursor allowing the query to be executed again
    $queryResult->closeCursor();
    return $mails;
}

/**
 * Search into mails function with filters
 *
 * @param $general_search
 * @param $filter_search
 * @param $filterSpeedCall
 * @param ?int $idUser
 * @return array
 */
function selectFiltersMails($general_search, $filter_search, $filterSpeedCall, int $idUser = null): array
{
    $selectSearch = "SELECT * "
        ."FROM mails as m INNER JOIN users as u on m.id_user = u.id "
        ."INNER JOIN enterprises as e on u.id_enterprise = e.id "
        ."WHERE";

    if (!empty($general_search)) {
        $selectSearch.=" ((lastname LIKE :search) 
                       OR (firstname LIKE :search)
                       OR (email LIKE :search)
                       OR (website_concerned LIKE :search)
                       OR (message LIKE :search))";

    }
    if (!empty($filter_search)) {
        if(!empty($general_search)) {
            $selectSearch.=" AND";
        }
        $selectSearch.=" (object = :object)";
    }
    if (!empty($filterSpeedCall)) {
        if(!empty($general_search) || !empty($filter_search)) {
            $selectSearch.=" AND";
        }
        $selectSearch.=" (speedcall = :speedCall)";
    }

    if (!empty($idUser)) {
        if(!empty($general_search) || !empty($filter_search) || !empty($filterSpeedCall)) {
            $selectSearch.=" AND";
        }
        $selectSearch.=" (id_user = :id_user)";
    }

    $selectSearch.=" ORDER BY sendDate DESC;";

    $pdo = getConnexion();
    $req = $pdo->prepare($selectSearch);

    if (!empty($general_search))
        $req->bindValue(':search', '%' . trim($general_search) . '%');
    if (!empty($filter_search))
        $req->bindValue(':object',  $filter_search);
    if (!empty($filterSpeedCall))
        $req->bindValue(':speedCall',  $filterSpeedCall);
    if (!empty($idUser))
        $req->bindValue(':id_user', $idUser);
    $req->execute();
    $results = $req->fetchAll();

    $mails = array();
    foreach ($results as $result) {
        $mail = createMail($result, 1);
        array_push($mails, $mail);
    }
    $req->closeCursor();
    return $mails;
}

/**
 * Fonction to delete a user
 * @param int $id
 */
function deleteMailById(int $id): void {
    // Creation of the delete request in BDD with the values set in parameters of the function DeleteMailById()
    $deleteMailById = "DELETE FROM mails WHERE id = :id";
    //Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    //Preparation of the request
    $queryResult = $pdo->prepare($deleteMailById);
    //Association with the parameter values of the function
    $queryResult->bindValue(':id', $id);
    // Execution of the request
    $queryResult->execute();
    $queryResult->closeCursor();
}


/**
 * Fonction to create mail as Mail
 * @param array $datas
 * @return Mail|null
 */
function createMail(array $datas): ?Mail {
    $user = createUser($datas, 1);

    $mail = new Mail(
        $user,
        $datas['website_concerned'],
        $datas['object'],
        $datas['message'],
        $datas['speedcall']
    );
    $mail->setId($datas[0]);
    $mail->setSpeedCallDate($datas['callDate']);
    $mail->setSpeedCallHours($datas['callHour']);
    $mail->setSendDate(DateTime::createFromFormat('Y-m-d H:i:s', $datas['sendDate']));

    return $mail;
}



/**
 * Function to select all users
 * @return array|null
 */
function pageParPageMail(int $currentPageMail): ?array
{   
    $pdo = getConnexion();
    // On détermine le nombre total d'articles
    $pageParPageMail = 'SELECT COUNT(*) AS nb_mails FROM `mails`;';

    // On prépare la requête
    $query = $pdo->prepare($pageParPageMail);

    // On exécute
    $query->execute();

    // On récupère le nombre d'articles
    $result = $query->fetch();

    $nbMails = intval($result['nb_mails']);

    // On détermine le nombre d'articles par page
    $parPageMail = 10;

    // On calcule le nombre de pages total
    $pagesMail = ceil($nbMails / $parPageMail);

    // Calcul du 1er article de la page
    $premierMail = ($currentPageMail * $parPageMail) - $parPageMail;

    $pageParPageMail = 'SELECT * FROM `mails` as m INNER JOIN users as u on m.id_user = u.id INNER JOIN enterprises as e on u.id_enterprise = e.id  LIMIT :premierMail, :parpageMail;';

    // On prépare la requête
    $query = $pdo->prepare($pageParPageMail);

    $query->bindValue(':premierMail', $premierMail, PDO::PARAM_INT);
    $query->bindValue(':parpageMail', $parPageMail, PDO::PARAM_INT);

    // On exécute
    $query->execute();

    $results = $query->fetchAll();

    $mails = array();
    
    foreach ($results as $result) {
        $mail = createMail($result, 0);
        array_push($mails, $mail);
    }

    //Closing the cursor allowing the query to be executed again
    $query->closeCursor();

    $_SESSION['pagesMail'] = $pagesMail;

    return $mails;
}