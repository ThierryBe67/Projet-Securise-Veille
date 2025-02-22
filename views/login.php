<?php
session_start();
require '../config.php';
require '../google-config.php';

// Générer l'URL d'authentification Google
$login_url = $client->createAuthUrl();

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
    <title>Connexion</title>
</head>
<body>
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <h2>Connexion</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mdp" placeholder="Mot de passe" required> <!-- Changé de "mdp" à "password" -->
        <button type="submit">Se connecter</button>
    </form>
    <p>OU</p>
    <a href="<?= $login_url ?>"><button>Se connecter avec Google</button></a>
</body>
</html>