<?php
require_once '../google-config.php';  // Ceci va démarrer la session automatiquement

// Révoquer l'accès Google si un token existe
if (isset($_SESSION['token_acces'])) {
    try {
        $client->revokeToken($_SESSION['token_acces']);
    } catch (Exception $e) {
        // Continue même si la révocation échoue
    }
}

// Vider toutes les variables de session
$_SESSION = array();

// Détruire le cookie de session si il existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: login.php');
exit();
?>