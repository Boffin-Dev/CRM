<?php

require 'vendor/autoload.php';
require 'config.php';
use GuzzleHttp\Client;
session_start();


//Step 1 - Check if OAuth2 Authorization code was already redeemed
if(!isset($_GET['code'])) {
    header('Location:login.php');
} elseif((isset($_SESSION['code']) && $_SESSION['code']==$_GET['code'])) {
    header('Location:login.php');
}

//Step 2 - Get access token
$accessToken = getAccessToken($tenantId, $clientId, $clientSecret);
$_SESSION['code'] = $_GET['code']; //Keep the code to avoid Repeated redemption

//Step 3 - Get user info
$user = getUserInfo($accessToken);
$_SESSION['username'] = $user['givenName'] . ' ' . $user['surname'];
$_SESSION['email'] = $user['userPrincipalName'];

//Step 4 - Get user photo
$photoData = getUserPhoto($accessToken);
$_SESSION['photo'] = $photoData;

//Step 5 - Redirect after login
if(!empty($_SESSION['retURL'])) {
    header('Location:'. $_SESSION['retURL']);
} else {
    header('Location:/');
}


// Function - Get access token 
function getAccessToken($tenantId, $clientId, $clientSecret) {
    $tokenUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
    $redirectUri = 'https://vendor.boffinchina.com/login_handle.php';
    $client = new Client();
    try {
        $response = $client->post($tokenUrl, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'authorization_code',
                'code' => $_GET['code'],
                'redirect_uri' => $redirectUri,
            ]
        ]);

        $body = json_decode((string)$response->getBody(), true);
        return $body['access_token'];
    } catch (Exception $e) {
        echo 'Error getting access token: ',  $e->getMessage(), "\n";
        return null;
    }
}

// Function - Get user information
function getUserInfo($accessToken){
    $client = new Client();
    try {
        $response = $client->get('https://graph.microsoft.com/v1.0/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ]
        ]);

        $user = json_decode($response->getBody(), true);
        return $user;
    } catch (Exception $e) {
        echo 'Error getting user info: ',  $e->getMessage(), "\n";
        return null;
    }
}

// Function - Get user photo
function getUserPhoto($accessToken){
    try{
        $client = new Client();
        $response = $client->get('https://graph.microsoft.com/v1.0/me/photo/$value', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);
        
        $photoData = $response->getBody()->getContents();
        return $photoData;        
        
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        echo 'Error getting user photo: ',  $e->getMessage(), "\n";
        return null; 
    }
}


?>