<?php
function verifierEtRafraichirToken($client) {
    // Vérifier si on a un token en session
    if (isset($_SESSION['token_acces'])) {
        $client->setAccessToken($_SESSION['token_acces']);
        
        // Vérifier si le token est expiré
        if ($client->isAccessTokenExpired()) {
            // Tenter de rafraîchir le token
            if ($client->getRefreshToken()) {
                try {
                    $nouveauToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    $_SESSION['token_acces'] = $nouveauToken;
                    return true;
                } catch (Exception $e) {
                    // Si le refresh token est invalide, déconnecter l'utilisateur
                    session_destroy();
                    return false;
                }
            } else {
                // Pas de refresh token disponible
                session_destroy();
                return false;
            }
        }
        return true;
    }
    return false;
}