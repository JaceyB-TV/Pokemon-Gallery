<?php

include_once '../dao/twitch.php';
include_once '../dao/twitch_clips.php';

$client_id = $twitch_dao->get( 'client_id' );
$client_secret = $twitch_dao->get( 'client_secret' );
$redirect_uri = $twitch_dao->get( 'redirect_url' );
$code = $twitch_dao->get( 'code' );
$access_token = $twitch_dao->get( 'access_token' );
$expires_in = $twitch_dao->get( 'now' ) + $twitch_dao->get( 'expires_in' );
$scope = "chat:read+channel:read:redemptions";

if ( !isset( $client_id ) || !isset( $client_secret ) || !isset( $redirect_uri ) ) {
    echo "<p>Missing params:";

    if ( !isset( $client_id ) ) {
        echo "<br>  Client ID";
    }

    if ( !isset( $client_secret ) ) {
        echo "<br>  Client Secret";
    }

    if ( !isset( $redirect_uri ) ) {
        echo "<br>  Redirect URI";
    }

    echo "</p>";

    die();
}

log_msg( "Expires at : " . date( "d/m/y H:i:s", $expires_in ) );

if ( date( 'U' ) > $expires_in && !isset( $code ) ) {
    log_msg( "Auth Token has expired, let's reset." );

    $url = "https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope&state=5";

    echo "<p><a href='$url'>Connect with Twitch</a></p>";

    die();
}

if ( isset( $code ) ) {
    log_msg( "Found a code, let's try and get an auth token." );

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

    $http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

    curl_close( $curl );

    if ( $http_code != 200 ) {
        echo "<p>Returned with error code: $http_code</p>";
        die();
    }

    $twitch_dao->remove( 'code' );

    $response = json_decode( $response );

    $twitch_dao->set( 'access_token', $response->access_token );
    $twitch_dao->set( 'now', date( 'U' ) );
    $twitch_dao->set( 'expires_in', $response->expires_in );
    $twitch_dao->set( 'refresh_token', $response->refresh_token );
    $twitch_dao->set( 'token_type', $response->token_type );

    log_msg( "Finished authorisation." );
}

log_msg( "Attempting to load clips." );

$url = "https://api.twitch.tv/helix/clips?broadcaster_id=37516800&first=100";

$curl = curl_init();

$headers = [
    "Authorization: Bearer $access_token",
    "Client-Id: $client_id"
];

curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

$response = curl_exec( $curl );

if ( !$response ) {
    echo curl_error( $curl );
    die();
}

curl_close( $curl );

$response = json_decode( $response );

foreach ( $response->data as $clip ) {
    $slug = $clip->id;
    $created_at = date( 'Y-m-d H:i:s', strtotime( $clip->created_at ) );
    $is_featured = $clip->is_featured === '1';
    $embed_url = $clip->embed_url;

    $twitch_clips_dao->save( $slug, $created_at, $is_featured, $embed_url );
}

$count = sizeof( $response->data );

log_msg( "Located $count clips" );
