<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Azure AD configuration
$tenantId = 'afa24598-2ab7-4663-a0fb-9737c1c933e5';
$clientId = 'a55bf6f7-c0a6-40ee-a440-f7e7eefa97eb';
$clientSecret = 'Zuy8Q~WtVLiaIhxkoMqFjrrrqRqv3FQQ6pnGZbNj';
$redirectUri = 'https://vendor.boffinchina.com/callback.php'; // URL to handle callback after authentication


// Step 2: Handle callback after authentication
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $state = $_GET['state']; // Validate state parameter to prevent CSRF attacks


    // Step 3: Exchange authorization code for access token
    $tokenEndpoint = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
    $client = new Client();
    try {
        $response = $client->post($tokenEndpoint, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $accessToken = $data['access_token'];
        $expires_in = $data['expires_in'];

        setcookie("accessToken", $accessToken, time()+$expires_in, '/');
        header('Location: info.php');
        Exit;
        
        $refreshToken = $data['refresh_token']; // Optionally handle refresh token

        echo 'accessToken: ' .$accessToken;
        echo 'refreshToken: ' .$refreshToken;
        exit;

        // Step 4: Use access token to fetch user's photo
        $graphApiEndpoint = 'https://graph.microsoft.com/v1.0/me/photo/$value';
        $response = $client->get($graphApiEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        $photoData = $response->getBody()->getContents();

        // Display HTML page with photo
        echo '<html>';
        echo '<head><title>User Photo</title></head>';
        echo '<body>';
        echo '<h1>User Photo</h1>';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($photoData) . '" />';
        echo '</body>';
        echo '</html>';

    } catch (RequestException $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }
}
?>