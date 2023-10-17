<?php

include_once '../dao/twitch.php';

$client_id = $twitch_dao->get( 'client_id' );
$redirect_uri = $twitch_dao->get( 'redirect_url' );
$code = $twitch_dao->get( 'code' );
$access_token = $twitch_dao->get( 'access_token' );

$scope = "chat:read+channel:read:redemptions";

//if ( !isset( $code ) ) {
$url = "https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$scope";

$curl = curl_init();

curl_setopt( $curl, CURLOPT_URL, $url );
curl_setopt( $curl, CURLOPT_POST, 1 );

$response = curl_exec( $curl );

if ( !$response ) {
    echo curl_error( $curl );
    die();
}

echo '<p>Response: ' . json_encode( $response ) . '</p>';

die();

echo "
<p>
    <a href='$url'>
        Code
    </a>
</p>";
//    die();
//}

//if ( !isset( $access_token ) ) {
$url = "https://id.twitch.tv/oauth2/authorize?response_type=token&client_id=$client_id&code=$code&grant_type=authorization_code&redirect_uri=$redirect_uri";

echo "
<p>
    <a href='$url'>
        Token
    </a>
</p>";
//    die();
//}

$url = "https://api.twitch.tv/helix/clips?client_id=$client_id&client_secret=$access_token&grant_type=client_credentials&broadaster_id=123456";

$curl = curl_init( $url );

curl_setopt( $curl, CURLOPT_POST, 1 );

$response = curl_exec( $curl );

echo "<p>Url: $url</p>";

echo '<p>Response' . print_r( $response, true ) . '</p>';

curl_close( $curl );

?>


<!--<iframe-->
<!--        src="https://clips.twitch.tv/embed?clip=PeacefulVivaciousEagleLitFam-g1xKLpbEfiHefZ_U&parent=jaceyb.co.uk&parent=www.jaceyb.co.uk&localhost"-->
<!--        height="300"-->
<!--        width="400"-->
<!--        allowfullscreen>-->
<!--</iframe>-->
