<?php
// Démarrez la session en haut de votre script
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruisez complètement la session si elle a été initialisée
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Détruire complètement la session
session_destroy();

// Rediriger vers la page de login
header("Location: home.html");
exit();
?>
