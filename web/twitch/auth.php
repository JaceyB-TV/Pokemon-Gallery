<?php

include_once '../dao/twitch.php';

if ( isset( $_GET['code'] ) ) {
    $code = $_GET['code'];

    $response = $twitch_dao->set( 'code', $code );

    if ( $response !== '' ) {
        header( "Location: /twitch/index.php?error=twitch" );
        die();
    }

    header( "Location: /twitch?message=success" );
}

if ( isset( $_GET['accessToken'] ) ) {
    $access_token = $_GET['accessToken'];

    $response = $twitch_dao->set( 'access_token', $access_token );

    if ( $response !== '' ) {
        header( "Location: /twitch/index.php?error=twitch" );
        die();
    }

    header( "Location: /twitch?message=success" );
}

?>

<script>
    function redirect() {
        const hash = window.location.hash;

        if (!hash) {
            return;
        }

        const parts = hash.substring(1).split("&");

        parts.forEach(part => {
            if (part.startsWith("access_token")) {
                const accessToken = part.split("=").pop();

                // window.location.href = "?accessToken=" + accessToken;
            } else if (part.startsWith("code")) {
                const accessToken = part.split("=").pop();

                window.location.href = "?code=" + accessToken;
            }
        });
    }

    redirect();
</script>
