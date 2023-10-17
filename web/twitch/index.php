<?php

include_once '../dao/twitch.php';

$client_id = $twitch_dao->get( 'client_id' );
$client_secret = $twitch_dao->get( 'client_secret' );
$redirect_uri = $twitch_dao->get( 'redirect_url' );
$code = $twitch_dao->get( 'code' );
$access_token = $twitch_dao->get( 'access_token' );
$expires_in = $twitch_dao->get( 'now' ) + $twitch_dao->get( 'expires_in' );

$scope = "chat:read+channel:read:redemptions";

if ( date( 'U' ) > $expires_in ) {
    $url = "https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope&state=5";

    $curl = curl_init();

    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );

    $response = curl_exec( $curl );

    if ( !$response ) {
        echo curl_error( $curl );
        die();
    }

    curl_close( $curl );

    die();
}

if ( isset( $code ) ) {
    $url = "https://id.twitch.tv/oauth2/token?client_id=$client_id&client_secret=$client_secret&code=$code&grant_type=authorization_code&redirect_uri=$redirect_uri";

    $curl = curl_init();

    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_POST, 1 );
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

    $response = curl_exec( $curl );

    if ( !$response ) {
        echo curl_error( $curl );
        die();
    }

    curl_close( $curl );

    $twitch_dao->remove( 'code' );

    $response = json_decode( $response );

    $twitch_dao->set( 'access_token', $response->access_token );
    $twitch_dao->set( 'now', date( 'U' ) );
    $twitch_dao->set( 'expires_in', $response->expires_in );
    $twitch_dao->set( 'refresh_token', $response->refresh_token );
    $twitch_dao->set( 'token_type', $response->token_type );

    die();
}

echo $response;

?>

<iframe
        src="https://clips.twitch.tv/embed?clip=PeacefulVivaciousEagleLitFam-g1xKLpbEfiHefZ_U&parent=jaceyb.co.uk&parent=www.jaceyb.co.uk&localhost"
        height="300"
        width="400"
        allowfullscreen>
</iframe>
