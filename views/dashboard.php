<?php
define('ROOT_PATH', dirname(dirname(__FILE__)));
require ROOT_PATH . '/google-config.php';

// Vérifier si l'utilisateur est connecté (peu importe la méthode)
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Si c'est une connexion Google, vérifier le token
if (isset($_SESSION['user']['auth_type']) && $_SESSION['user']['auth_type'] === 'google') {
    if (!verifierEtRafraichirToken($client)) {
        header('Location: login.php');
        exit();
    }
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Site Sécurisé</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Site Sécurisé</h1>
    </header>
    
    <div class="container">
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
        
        <div class="dashboard">
            <div class="dashboard-header">
                <h2>Tableau de bord</h2>
                <a href="logout.php" class="btn">Déconnexion</a>
            </div>
            
            <div class="user-info">
                <?php if (isset($user['picture'])): ?>
                    <img src="<?= htmlspecialchars($user['picture']) ?>" alt="Photo de profil" class="user-avatar">
                <?php else: ?>
                    <div class="user-avatar" style="background-color: #3498db; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px;">
                        <?= strtoupper(substr($user['email'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                
                <div class="user-details">
                    <h2>Bienvenue, <?= htmlspecialchars($user['name']) ?> !</h2>
                    <p>Email : <?= htmlspecialchars($user['email']) ?></p>
                    <p>Type d'authentification : 
                        <?php if ($user['auth_type'] === 'google'): ?>
                            <span style="color: #dd4b39; font-weight: bold;">Google</span>
                        <?php else: ?>
                            <span style="font-weight: bold;">Email et mot de passe</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div>
                <h3>Votre compte est sécurisé</h3>
                <p>Ce site utilise les meilleures pratiques de sécurité :</p>
                <ul>
                    <li>Authentification OAuth2 via Google</li>
                    <li>Mots de passe hashés en base de données</li>
                    <li>Protection contre l'injection SQL</li>
                    <li>Token pour vous sécuriser en cas de péremption de votre durée de connexion</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>