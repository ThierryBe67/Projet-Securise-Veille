<?php
session_start();
require '../config.php';
require '../google-config.php';

// Générer l'URL d'authentification Google
$login_url = $client->createAuthUrl();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['mdp'];

    // Vérifier l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM UTILISATEURS WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mdp'])) {
        // Stocker les informations utilisateur dans la session de manière cohérente
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['email'], // Ou un autre champ si vous avez le nom dans votre BDD
            'auth_type' => 'database' // Pour distinguer le type d'authentification
        ];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Site Sécurisé</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Site Sécurisé</h1>
    </header>
    
    <div class="container">
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="../register.php">Inscription</a>
        </nav>
        
        <div class="form-container">
            <h2 class="form-title">Connexion</h2>
            
            <?php if ($error): ?>
                <div class="message message-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Votre adresse email" required>
                </div>
                
                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required>
                </div>
                
                <button type="submit" class="btn btn-full">Se connecter</button>
            </form>
            
            <div class="separator">
                <span>OU</span>
            </div>
            
            <a href="<?= $login_url ?>" class="btn btn-google btn-full">
                Se connecter avec Google
            </a>
            
            <div class="separator">
                <span>Pas encore de compte ?</span>
            </div>
            
            <a href="../register.php" class="btn btn-success btn-full">Créer un compte</a>
        </div>
    </div>
</body>
</html>