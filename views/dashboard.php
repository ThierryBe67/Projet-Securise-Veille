<?php
define('ROOT_PATH', dirname(dirname(__FILE__)));
require ROOT_PATH . '/google-config.php';

// Vérifier si l'utilisateur est connecté (peu importe la méthode)
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Si c'est une connexion Google, vérifier le token
if (isset($_SESSION['user']['auth_type']) && $_SESSION['user']['auth_type'] === 'google') {
    if (!verifierEtRafraichirToken($client)) {
        header('Location: ../login.php');
        exit();
    }
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue, <?= htmlspecialchars($user['name']) ?> !</h1>
    <p>Email : <?= htmlspecialchars($user['email']) ?></p>
    
    <?php if (isset($user['picture'])): ?>
        <img src="<?= htmlspecialchars($user['picture']) ?>" alt="Photo de profil">
    <?php endif; ?>

    <a href="logout.php">Déconnexion</a>
</body>
</html>