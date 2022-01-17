<?php
// Initialize the session
session_start();
// Deactivate the session
session_unset();
// Destroy the session
session_destroy();
setcookie('auth', '', time()-1, '/', null, false, true); // DETRUIT LE COOKIE
// Referral to index.php
header('location: ../../');
exit();
