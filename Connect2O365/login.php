<?php
session_start();

require 'config.php';

$redirectUri = 'https://vendor.boffinchina.com/login_handle.php';

$authorizationUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize";
$params = [
    'client_id' => $clientId,
    'response_type' => 'code',
    'redirect_uri' => $redirectUri,
    'response_mode' => 'query',
    'scope' => 'openid profile email User.Read',
    'state' => bin2hex(random_bytes(16))
];

$_SESSION['oauth2state'] = $params['state'];
$authorizationUrl .= '?' . http_build_query($params);

header('Location: ' . $authorizationUrl);
exit;
?>