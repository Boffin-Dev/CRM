<?php

// Azure AD configuration
$tenantId = 'afa24598-2ab7-4663-a0fb-9737c1c933e5';
$clientId = 'a55bf6f7-c0a6-40ee-a440-f7e7eefa97eb';
$clientSecret = 'Zuy8Q~WtVLiaIhxkoMqFjrrrqRqv3FQQ6pnGZbNj';
$redirectUri = 'https://vendor.boffinchina.com/callback.php'; // URL to handle callback after authentication

// Step 1: Redirect user to Azure AD login page
$authEndpoint = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize";
$scopes = 'openid profile email User.Read'; // Scopes required by your application

// Step 1: Redirect user to Azure AD login page
if (!isset($_GET['code'])) {
    $authEndpoint = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize";
    $state = bin2hex(random_bytes(16)); // Use state parameter to prevent CSRF attacks

    $authUrl = "$authEndpoint?client_id=$clientId&response_type=code&redirect_uri=$redirectUri&response_mode=query&scope=$scopes&state=$state";
    header('Location: ' . $authUrl);
    exit;
}

?>
