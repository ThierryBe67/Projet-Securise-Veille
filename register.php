<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['mdp'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Format d'email invalide.";
        exit;
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM UTILISATEURS WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo "Cet email est déjà utilisé.";
        exit;
    }

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insérer le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO UTILISATEURS (email, mdp) VALUES (?, ?)");
        $stmt->execute([$email, $hashedPassword]);
        echo "Inscription réussie ! <a href='views/login.php'>Se connecter</a>";
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <a href="index.php"><button>Retour à l'accueil</button></a>

    <title>Inscription</title>
</head>
<body>
    <h2>Créer un compte</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="mdp" name="mdp" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
