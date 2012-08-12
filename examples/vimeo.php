<?php
/**
 * Example of retrieving an authentication token of the Vimeo service
 *
 * PHP version 5.4
 *
 * @author     Lusitanian <alusitanian@gmail.com>
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
use OAuth\OAuth1\Signature\Signature;
use OAuth\OAuth1\Service\Vimeo;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\Uri;

/**
 * Bootstrap the example
 */
require_once __DIR__ . '/bootstrap.php';

// We need to use a persistent storage to save the token, because oauth1 requires the token secret received before'
// the redirect (request token request) in the access token request.
$storage = new Session();

// Setup the credentials for the requests
$credentials = new Credentials(
    $servicesCredentials['vimeo']['key'],
    $servicesCredentials['vimeo']['secret'],
    $currentUri->getAbsoluteUri()
);

// setup the signature for the requests
$signature = new Signature($credentials);

// Instantiate the google service using the credentials, http client and storage mechanism for the token
$vimeoService = new Vimeo($credentials, $httpClientProvider(), $storage, $signature);

if( !empty( $_GET['oauth_token'] ) ) {
    $token = $storage->retrieveAccessToken();

    // This was a callback request from google, get the token
    $token = $vimeoService->requestAccessToken( $_GET['oauth_token'], $_GET['oauth_verifier'], $token->getRequestTokenSecret() );

    // Send a request with it
    $result = json_decode( $vimeoService->sendAuthenticatedRequest( new Uri('https://vimeo.com/oauth/api/rest/v2?format=json&method=vimeo.people.getInfo'), [], 'GET' ), true );

    // Show some of the resultant data
    echo 'Suggestions made to you: ';
    $delimiter = '';
    foreach($result as $item) {
        echo $delimiter . $item['name'];

        $delimiter = ', ';
    }
} elseif( !empty($_GET['go'] ) && $_GET['go'] == 'go' ) {
    // extra request needed for oauth1 to request a request token :-)
    $token = $vimeoService->requestRequestToken();

    $url = $vimeoService->getAuthorizationUri(['oauth_token' => $token->getRequestToken()]);
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
    echo "<a href='$url'>Login with Vimeo!</a>";
}