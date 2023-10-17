<?php

include_once '../dao/twitch.php';


/**
 * getRandomWeightedElement()
 * Utility function for getting random values with weighting.
 * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
 * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
 * The return value is the array key, A, B, or C in this case.  Note that the values assigned
 * do not have to be percentages.  The values are simply relative to each other.  If one value
 * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
 * chance of being selected.  Also note that weights should be integers.
 *
 * @param array $weightedValues
 */
function getRandomWeightedElement( array $weightedValues )
{
    $rand = mt_rand( 1, (int)array_sum( $weightedValues ) );

    foreach ( $weightedValues as $key => $value ) {
        $rand -= $value;
        if ( $rand <= 0 ) {
            return $key;
        }
    }
}

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
    curl_setopt( $curl, CURLOPT_POST, 1 );
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

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

$url = "https://api.twitch.tv/helix/clips?broadcaster_id=37516800&first=10";

$curl = curl_init();

$headers = [
    "Authorization: Bearer $access_token",
    "Client-Id: $client_id"
];

curl_setopt( $curl, CURLOPT_URL, $url );
//curl_setopt( $curl, CURLOPT_POST, 1 );
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

$count = sizeof( $response->data );

echo "<p>Located $count clips</p>";

$clips = array();

foreach ( $response->data as $clip ) {
    $clips[] = array(
        'created_at' => $clip->created_at,
        'is_featured' => $clip->is_featured,
        'embed_url' => $clip->embed_url
    );
}

function sortByOrder( $a, $b )
{
    if ( $a['created_at'] > $b['created_at'] ) {
        return -1;
    }
    elseif ( $a['created_at'] < $b['created_at'] ) {
        return 1;
    }

    return 0;
}

usort( $clips, 'sortByOrder' );

foreach ( $clips as $clip ) {
    echo "
<p>
    <iframe
            src='${clip["embed_url"]}&parent=jaceyb.co.uk&parent=www.jaceyb.co.uk&localhost'
            height='300'
            width='400'
            allowfullscreen>
    </iframe>
</p>";
}
