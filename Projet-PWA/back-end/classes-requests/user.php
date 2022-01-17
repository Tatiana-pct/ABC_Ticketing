<?php

require_once '../config.php';
require_once '../classes/User.php';
require_once '../classes/Enterprise.php';
require_once '../classes-requests/enterprise.php';
require_once '../login/reset-password-requests.php';


/**
 * Function allowing to insert a User in BDD
 * @param User $user
 */
function insertUser (User $user): void {
    // Retrieving the user's enterprise ID
    $idEnterprise = insertEnterprise($user->getEnterprise());

    // Creation of the insertion request in BDD with the values set in parameters of the function insertUser
    $insertUser = "INSERT INTO `users`(`lastname`, `firstname`, `id_enterprise`, `email`, `phone`, `password`, `secret`, `admin`, `blocked`) "
                    ."VALUES (:lastname, :firstname, :idEnterprise, :email, :phone, :password, :secret, :isAdmin, :blocked);";

    // Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
// Preparation of the request
    $req = $pdo->prepare($insertUser);
    // Association with the parameter values of the function
    $req->bindValue(':lastname', $user->getLastname());
    $req->bindValue(':firstname', $user->getFirstname());
    $req->bindValue(':idEnterprise', $idEnterprise);
    $req->bindValue(':email', $user->getEmail());
    $req->bindValue(':phone', $user->getPhone());
    $req->bindValue(':password', $user->getPassword());
    $req->bindValue(':secret', $user->getSecret());
    $req->bindValue(':isAdmin', $user->getAdmin());
    $req->bindValue(':blocked', $user->getBlocked());
    // Execution of the request
    $req->execute();
    //Closing the cursor allowing the query to be executed again
    $req->closeCursor();
}

/**
 * Function to select all users
 * @return array|null
 */
function selectAllUsers(): ?array
{
    // get connexion to BDD
    $pdo = getConnexion();
    // Creation of the insertion request in BDD with the values set in parameters of the function selectAllUsers
    $selectAllUsers = "SELECT * "
        ."FROM users as u "
        ."INNER JOIN enterprises as e ON u.id_enterprise = e.id ";
    // Execution of the request
    $queryResult = $pdo->query($selectAllUsers);
    $results = $queryResult->fetchAll();

    $users = array();
    foreach ($results as $result) {
        $user = createUser($result, 0);
        array_push($users, $user);
    }
    //Closing the cursor allowing the query to be executed again
    $queryResult->closeCursor();

    return $users;
}


/**
 * Function allowing to select the user by their email
 * @param string $email
 * @return User
 */
function selectUserByMail(string $email): ?User
{
    //Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    // Creation of the insertion request in BDD with the values set in parameters of the function selectUserByMail()
    $selectUserByMail = "SELECT * "
        ."FROM users as u "
        ."INNER JOIN enterprises as e ON u.id_enterprise = e.id "
        ."WHERE email = :mail";
    //Preparation of the request
    $req = $pdo->prepare($selectUserByMail);
    //Association with the parameter values of the function
    $req->bindValue(':mail', trim($email));
    // Execution of the request
    $req->execute();

    $result = $req->fetch();

    $user = null;
    // If everything is good then creation in user database !
    if ($result) {
        $user = createUser($result, 0);
    }
    $req->closeCursor();

    return $user;
}

/**
 * 
 * @param string $siret
 * @return User
 */
function clientSiret(string $siret): ?User
{
    //Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    // Creation of the insertion request in BDD with the values set in parameters of the function clientSiret()
    $checkClientSiret = "SELECT * "
        ."FROM users as u "
        ."INNER JOIN enterprises as e ON u.id_enterprise = e.id "
        ."WHERE siret = :siret";
    //Preparation of the request
    $req = $pdo->prepare($checkClientSiret);
    //Association with the parameter values of the function
    $req->bindValue(':siret', trim($siret));
    // Execution of the request
    $req->execute();

    $result = $req->fetch();

    $user = null;
    // If everything is good then creation in user database !
    if ($result) {
        $user = createUser($result, 0);
    }
    $req->closeCursor();

    return $user;
}

/**
 * Function allowing to select the user by their Id
 * @param int $id
 * @return User|null
 */
function selectUserById(int $id): ?User
{
    // Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    // Creation of the insertion request in BDD with the values set in parameters of the function insertMail()
    $selectUserById = "SELECT * "
        ."FROM users as u "
        ."INNER JOIN enterprises as e ON u.id_enterprise = e.id "
        ."WHERE u.id = :id";
    //Preparation of the request
    $req = $pdo->prepare($selectUserById);
    //Association with the parameter values of the function
    $req->bindValue(':id', $id);
    // Execution of the request
    $req->execute();
    $result = $req->fetch();

    $user = null;
    if ($result) {
        $user = createUser($result, 0);
    }
    $req->closeCursor();

    return $user;
}


/**
 * Search into users function with filters
 *
 * @param string $research_user
 * @param int|null $id_enterprise
 * @return array
 */
function selectFiltersUsers(String $research_user, int $id_enterprise = null): array
{
    $selectFilterUsers = "SELECT * "
        ."FROM users as u "
        ."INNER JOIN enterprises as e ON u.id_enterprise = e.id "
        ."WHERE";

    if (!empty($research_user)) {
        $selectFilterUsers .= " (lastname LIKE :search) 
                       OR (firstname LIKE :search)
                       OR (email LIKE :search)
                       OR (name LIKE :search)
                       OR (clientNumber LIKE :search)";
    }
    $selectFilterUsers .= " ORDER BY lastname ASC;";

    $pdo = getConnexion();
    $req = $pdo->prepare($selectFilterUsers);

    if (!empty($research_user))
        $req->bindValue(':search', '%'.trim($research_user).'%');
    $req->execute();
    $results = $req->fetchAll();

    $users = array();
    foreach ($results as $result) {
        $user = createUser($result, 0);
        array_push($users, $user);
    }
    $req->closeCursor();
    return $users;
}

/**
 * Function allowing the update of the password
 * @param int $idUser
 * @param string $password
 * @param int|null $idRecoveringRequest
 */
function updatePassword (int $idUser, string $password, int $idRecoveringRequest = null) {
    // Creation of the insertion request in BDD with the values set in parameters of the function updatePassword
    $updatePassword = "UPDATE `users` SET `password` = :password WHERE `id` = :id";
    // Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    // Preparation of the request
    $req = $pdo->prepare($updatePassword);
    // Association with the parameter values of the function
    $req->bindValue(':password', $password);
    $req->bindValue(':id', $idUser);
    // Execution of the request
    $req->execute();
    //Closing the cursor allowing the query to be executed again
    $req->closeCursor();

    //  Delete recoveringRequest if exist
    if (!empty($idRecoveringRequest)) {
        deleteRecoveringRequest($idRecoveringRequest);
    }
}

/**
 * Function allowing the update of the password
 * @param User $user
 */
function updateUser (User $user): void {
    // Creation of the insertion request in BDD with the values set in parameters of the function updatePassword
    $updateUser = "UPDATE `users` SET "
                    ."`lastname` = :lastname, "
                    ."`firstname` = :firstname, "
                    ."`email` = :email, "
                    ."`phone` = :phone, "
                    ."`admin` = :role "
                    ."WHERE `id` = :id";

    updateEnterprise($user->getEnterprise());

    // Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();

    // Preparation of the request
    $req = $pdo->prepare($updateUser);
    // Association with the parameter values of the function
    $req->bindValue(':lastname', $user->getLastname());
    $req->bindValue(':firstname', $user->getFirstname());
    $req->bindValue(':email', $user->getEmail());
    $req->bindValue(':phone', $user->getPhone());
    $req->bindValue(':role', $user->getAdmin());
    $req->bindValue(':id', $user->getId());
    // Execution of the request
    $req->execute();
    //Closing the cursor allowing the query to be executed again
    $req->closeCursor();
}

/**
 * Block or unblock an user
 * @param User $user
 */
function updateBlockedUser(User $user): void {
    $block_unblock_user = "UPDATE users SET blocked = :block WHERE id = :id ;";

    $pdo = getConnexion();
    $req = $pdo->prepare($block_unblock_user);
    $req->bindValue(":block", $user->getBlocked());
    $req->bindValue(":id", $user->getId());
    $req->execute();
    $req->closeCursor();
}


/**
 * Fonction to delete a user
 * @param int $id
 */
function deleteUserById(int $id): void {
    // Creation of the delete request in BDD with the values set in parameters of the function deleteUserById()
    $deleteUserById = "DELETE FROM users WHERE id = :id";
    //Establishment of the connection to the database and recovery of the PDO
    $pdo = getConnexion();
    //Preparation of the request
    $queryResult = $pdo->prepare($deleteUserById);
    //Association with the parameter values of the function
    $queryResult->bindValue(':id', $id);
    // Execution of the request
    $queryResult->execute();
    $queryResult->closeCursor();

}


/**
 * Function allowing to verify the user key $secret
 * @param string $secret
 * @return User
 */
function checkSecret(string $secret): ?User
{
    $pdo = getConnexion();

    $checkSecret = "SELECT * "
                    ."FROM users as u "
                    ."INNER JOIN enterprises as e ON u.id_enterprise = e.id "
                    ."WHERE secret = :secret;";
    $req = $pdo->prepare($checkSecret);
    $req->bindValue(':secret', $secret);
    $req->execute();
    $result = $req->fetch();

    $user = null;
    if ($result) {
        $user = createUser($result, 0);
    }
    $req->closeCursor();

    return $user;
}

/*--------------------------- FUNCTIONS -----------------------------------*/
/**
 * Function allowing to create a user and its company
 * @param array $datas
 * @param int $indexId
 * @return User|null
 */
function createUser(array $datas, int $indexId): ?User {
    $enterprise = new Enterprise(
        $datas['name'],
        $datas['siret'],
        $datas['website'],
        $datas['address']
    );
    $enterprise->setClientNumber($datas['clientNumber']);
    $enterprise->setId($datas['id_enterprise']);

    $user = new User(
        $datas['lastname'],
        $datas['firstname'],
        $enterprise,
        $datas['email'],
        $datas['phone'],
        $datas['password']
    );
    $user->setId($datas[$indexId]);
    $user->setSecret($datas['secret']);
    $user->setBlocked($datas['blocked']);
    $user->setAdmin($datas['admin']);
    $user->setDateCreated(DateTime::createFromFormat('Y-m-d H:i:s', $datas['dateCreated']));

    return $user;
}


/**
 * Function to select all users
 * @return array|null
 */
function pageParPage(int $currentPage): ?array
{   
    $pdo = getConnexion();
    // On détermine le nombre total d'articles
    $pageParPage = 'SELECT COUNT(*) AS nb_users FROM `users`;';

    // On prépare la requête
    $query = $pdo->prepare($pageParPage);

    // On exécute
    $query->execute();

    // On récupère le nombre d'articles moins nous même 
    $result = $query->fetch();

    $nbUsers = intval($result['nb_users']) - 1;

    // On détermine le nombre d'articles par page
    $parPage = 10;

    // On calcule le nombre de pages total
    $pages = ceil($nbUsers / $parPage);

    // Calcul du 1er article de la page
    $premier = ($currentPage * $parPage) - $parPage;

    $pageParPage = 'SELECT * FROM `users` as u INNER JOIN enterprises as e ON u.id_enterprise = e.id LIMIT :premier, :parpage;';

    // On prépare la requête
    $query = $pdo->prepare($pageParPage);

    $query->bindValue(':premier', $premier, PDO::PARAM_INT);
    $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);

    // On exécute
    $query->execute();

    $results = $query->fetchAll();

    $users = array();
    foreach ($results as $result) {
        $user = createUser($result, 0);
        array_push($users, $user);
    }

    //Closing the cursor allowing the query to be executed again
    $query->closeCursor();

    $_SESSION['pagesUser'] = $pages;

    return $users;
}