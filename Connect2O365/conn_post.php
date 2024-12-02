<?php
require 'config.php';
require 'vendor/autoload.php'; // Ensure Guzzle is loaded

use GuzzleHttp\Client;


$scopes = 'https://graph.microsoft.com/.default';

// Get access token
function getAccessToken($scopes,$tenantId, $clientId, $clientSecret) {
    $url = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
    
    $client = new Client();
    try {
        $response = $client->post($url, [
            'form_params' => [
                'client_id' => $clientId,
                'scope' => $scopes,
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials'
            ]
        ]);

        $body = json_decode((string)$response->getBody(), true);
        return $body['access_token'];
    } catch (Exception $e) {
        echo 'Error getting access token: ',  $e->getMessage(), "\n";
        return null;
    }
}

echo getAccessToken($scopes,$tenantId, $clientId, $clientSecret);
?>