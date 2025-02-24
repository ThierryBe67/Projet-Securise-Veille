<?php
require 'google-config.php';

if (isset($_GET['code'])) {
    try {
        // Un seul appel à fetchAccessTokenWithAuthCode
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        $_SESSION['token_acces'] = $token;
        
        if (!class_exists('Google\Service\Oauth2')) {
            throw new Exception('La classe Google\Service\Oauth2 n\'est pas disponible.');
        }

        $oauth = new Google\Service\Oauth2($client);
        $userInfo = $oauth->userinfo->get();

        $_SESSION['user'] = [
            'id' => $userInfo->id,
            'email' => $userInfo->email,
            'name' => $userInfo->name,
            'picture' => $userInfo->picture,
            'auth_type' => 'google'
        ];

        header('Location: views/dashboard.php');
        exit();
    } catch (Exception $e) {
        echo "Une erreur s'est produite : " . $e->getMessage();
        exit();
    }
}
?>