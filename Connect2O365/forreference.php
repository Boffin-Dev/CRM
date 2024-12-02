<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Azure AD configuration
$tenantId = 'afa24598-2ab7-4663-a0fb-9737c1c933e5';
$clientId = 'a55bf6f7-c0a6-40ee-a440-f7e7eefa97eb';
$clientSecret = 'Zuy8Q~WtVLiaIhxkoMqFjrrrqRqv3FQQ6pnGZbNj';
$redirectUri = 'https://vendor.boffinchina.com/callback.php'; // URL to handle callback after authentication




if(isset($_COOKIE['accessToken'])){
    $accessToken = $_COOKIE['accessToken'];
    $client = new Client();
    // Step 4: Use access token to fetch user's photo
    $graphApiEndpoint = 'https://graph.microsoft.com/v1.0/me/photo/$value';
    $response = $client->get($graphApiEndpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
        ],
    ]);

    $client = new Client();
    $photo = $response->getBody()->getContents();
    header('Content-Type: image/jpeg');
    echo $photo;
}else{
    header("Location:conn.php");
}

?>
