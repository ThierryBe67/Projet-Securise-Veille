<?php
// Charger les variables d'environnement
require 'vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    die('Erreur : Fichier .env non trouvé. Veuillez créer le fichier .env à la racine du projet.');
}

// Définition du chemin du certificat
$certPath = 'C:\Users\thier\Desktop\UwAmp\bin\php\cacert.pem';

// Vérifier si le fichier de certificat existe
if (!file_exists($certPath)) {
    die('Erreur : Le fichier de certificat SSL est introuvable.');
}

// Configuration du certificat
putenv('CURL_CA_BUNDLE=' . $certPath);
putenv('SSL_CERT_FILE=' . $certPath);

// Démarrer une session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chemin vers l'autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Modification de l'import pour utiliser le namespace complet
use Google\Service\Oauth2;
use Google\Client;

// Vérification si la classe existe
if (!class_exists('Google\Client')) {
    die('Erreur : La classe Google\Client est introuvable.');
}

// Créer une instance du client Google en utilisant la classe correcte
$client = new Client();

// Configuration du client avec les options SSL
$client->setHttpClient(
    new GuzzleHttp\Client([
        'verify' => $certPath,
        'defaults' => [
            'verify' => $certPath
        ]
    ])
);

// Configuration du client Google
// Configuration du client Google avec les variables d'environnement
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
$client->addScope('email');
$client->addScope('profile');

$client->setAccessType('offline');
$client->setPrompt('consent');

// Inclure le gestionnaire de token
require_once 'gestionnaire-token.php';
?>