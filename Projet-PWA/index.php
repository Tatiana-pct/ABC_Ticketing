<?php
session_start();
require_once 'inc/head.php';
include_once 'back-end/classes/User.php';
include_once 'back-end/classes/Enterprise.php';

if (isset($_SESSION['user']))
    $user = unserialize($_SESSION['user']);
?>
<body>
    <section>
        <div id="login-body">
            <?php
                // Checking the variables saved in the session
                if((isset($_SESSION['connect'])
                    && $_SESSION['connect'] === 1
                    && !empty($user)
                    && $user->getBlocked() === 0)
                    || isset($_GET['success'])) {
                    // Welcome message display
                    echo "<h1>Bonjour ".$user->getEmail().'</h1>';
                    if (isset($_GET['success'])) {
                        // Display of the connection confirmation message
                        echo '<br><div class="alert success">Vous êtes maintenant connecté.</div>';
                    }
                    echo "<p> Vous allez arriver sur votre interface utilisateur</p>";
                }
            ?>
            <?php
                // Checking if the user is not blocked by an admin
                if (!empty($user) && intval($user->getBlocked()) === 0) {
                    // Checking if the user is an admin and redirecting to the admin section otherwise redirect to the user section
                    if(intval($user->getAdmin()) !== 0) {
                        header('refresh:3; url=back-end/admin/admin-mail-treatment.php');
                    } else {
                        header('refresh:3; url=pages/contact.php');
                    }
                // Redirection if user is blocked
                } else if (!empty($user) && $user->getBlocked() === 1) {
                    header('location: back-end/login/logout.php');
                    exit();
                } else {
                    require_once 'inc/login.php';
                }
            ?>
        </div>
    </section>
    <script src="js/app.js"></script>
</body>
</html>