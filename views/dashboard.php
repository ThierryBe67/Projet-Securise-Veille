<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h2>Bienvenue sur votre tableau de bord !</h2>
    <a href="logout.php"><button>Se déconnecter</button></a>
</body>
</html>
