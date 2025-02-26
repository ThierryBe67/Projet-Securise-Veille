<?php
session_start();
require 'config.php';

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['mdp'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format d'email invalide.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM UTILISATEURS WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error_message = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Insérer le nouvel utilisateur
                $stmt = $pdo->prepare("INSERT INTO UTILISATEURS (email, mdp) VALUES (?, ?)");
                $stmt->execute([$email, $hashedPassword]);
                $success_message = "Inscription réussie !";
            } catch (PDOException $e) {
                $error_message = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Site Sécurisé</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Site Sécurisé</h1>
    </header>
    
    <div class="container">
        <nav>
            <a href="index.php">Accueil</a>
            <a href="views/login.php">Connexion</a>
        </nav>
        
        <div class="form-container">
            <h2 class="form-title">Créer un compte</h2>
            
            <?php if ($error_message): ?>
                <div class="message message-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="message message-success">
                    <?php echo $success_message; ?> 
                    <a href="views/login.php">Se connecter</a>
                </div>
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
                
                <button type="submit" class="btn btn-full">S'inscrire</button>
            </form>
            
            <div class="separator">
                <span>Déjà inscrit ?</span>
            </div>
            
            <a href="views/login.php" class="btn btn-success btn-full">Se connecter</a>
        </div>
    </div>
</body>
</html>