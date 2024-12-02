<?php
require 'vendor/autoload.php'; // Ensure Guzzle is loaded

use GuzzleHttp\Client;

$tenantId = 'afa24598-2ab7-4663-a0fb-9737c1c933e5';
$clientId = 'a55bf6f7-c0a6-40ee-a440-f7e7eefa97eb';
$clientSecret = 'Zuy8Q~WtVLiaIhxkoMqFjrrrqRqv3FQQ6pnGZbNj';
$email = 'fei.yang@boffin.com';

// Get access token
function getAccessToken($tenantId, $clientId, $clientSecret) {
    $url = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
    
    $client = new Client();
    try {
        $response = $client->post($url, [
            'form_params' => [
                'client_id' => $clientId,
                'scope' => 'https://graph.microsoft.com/.default',
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

$accessToken = getAccessToken($tenantId, $clientId, $clientSecret);

if ($accessToken) {
    // Get user info
    function getUserInfo($email, $accessToken) {
        $url = "https://graph.microsoft.com/v1.0/users/$email";
    
        $client = new Client();
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => "Bearer $accessToken"
                ]
            ]);
    
            return json_decode((string)$response->getBody(), true);
        } catch (Exception $e) {
            echo 'Error getting user info: ',  $e->getMessage(), "\n";
            return null;
        }
    }

    $userInfo = getUserInfo($email, $accessToken);

    // Get user photo
    function getUserPhoto($email, $accessToken) {
        $url = "https://graph.microsoft.com/v1.0/users/$email/photo/\$value";
    
        $client = new Client();
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => "Bearer $accessToken"
                ]
            ]);
    
            return $response->getBody();
        } catch (Exception $e) {
            echo 'Error getting user photo: ',  $e->getMessage(), "\n";
            return null;
        }
    }

    $userPhoto = getUserPhoto($email, $accessToken);

    // Output user info
    if ($userInfo) {
        echo "User Info: \n";
        print_r($userInfo);
    }

    // Save user photo
    if ($userPhoto) {
        file_put_contents('user_photo.jpg', $userPhoto);
        echo "User photo saved to user_photo.jpg\n";
    }
} else {
    echo "Failed to obtain access token.\n";
}
