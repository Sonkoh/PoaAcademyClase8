<?php

require __DIR__ . '/vendor/autoload.php';

session_start();


$provider = new \Wohali\OAuth2\Client\Provider\Discord([
    'clientId' => '1025208462222831637',
    'clientSecret' => '4LdnTD2adhHLcpf-H8CUnP020lVRNO_X',
    'redirectUri' => 'http://localhost/clase8'
]);

if (!isset($_GET['code'])) {

    // Step 1. Get authorization code
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Step 2. Get an access token using the provided authorization code
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Show some token details
    

    // Step 3. (Optional) Look up the user's profile with the provided token
    try {

        $user = $provider->getResourceOwner($token);
        $_SESSION['user'] = $user->toArray();
        header('Location: bienvenido.php');

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');

    }
}