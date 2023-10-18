<style>
    * {
        padding: 0;
        margin: 0;
        overflow: hidden;
    }
</style>

<?php

include_once '../dao/twitch.php';
include_once '../dao/twitch_clips.php';
include_once '../utils/functions.php';

/**
 * TODO:
 * 1. Add paging
 * 2. Add last updated time, only refresh once a week?
 * 3. Add clips table
 * 4. Store in clips table
 * 5. Added "twitch" table to sql
 * 6. Add logging
 */

$twitch_dao->set( 'update_clips', '0' );

if ( $twitch_dao->get( 'update_clips' ) === '1' ) {
    include_once 'update_clips.php';
}

$clips = $twitch_clips_dao->selectAll();

$month = date( 'U', strtotime( '-1 month' ) );
$month6 = date( 'U', strtotime( '-6 month' ) );
$year = date( 'U', strtotime( '-1 year' ) );

foreach ( $clips as &$c ) {
    $date = date( 'U', strtotime( $c['created_at'] ) );
    $c['created_at'] = $date;

    if ( $date > $month ) {
        $c['weight'] = 100;
    }
    elseif ( $date > $month6 ) {
        $c['weight'] = 50;
    }
    elseif ( $date > $year ) {
        $c['weight'] = 20;
    }
    else {
        $c['weight'] = 1;
    }
}

if ( sizeof( $clips ) > 0 ) {
    echo "<table style='padding: 10px; width: 80%; max-width: 800px; margin: auto; '>
<tr><td>Clip ID</td><td>Created</td><td>Weight</td></tr>";

    for ( $i = 0; $i < 10; $i++ ) {
        $key = getRandomWeightedElement( $clips );
        $clip = $clips[$key];
        unset( $clips[$key] );

        $id = $clip['id'];
        $created = date( 'd/m/Y', $clip['created_at'] );
        $count = $clip['weight'];

        echo "<tr><td>$id</td><td>$created</td><td>$count</td></tr>";
    }

    echo "</table>";

//    $key = getRandomWeightedElement( $clips );
//    $clip = $clips[$key];
//    unset( $clips[$key] );
//    echo "<iframe src='{$clip["embed_url"]}&parent=localhost&autoplay=true' height='100%' width='100%'/>";
}
?>
