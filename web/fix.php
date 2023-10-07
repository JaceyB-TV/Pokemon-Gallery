<?php

include 'secret.php';

$limit = isset( $_GET['limit'] ) ? intval( $_GET['limit'] ) : 10;
$offset = isset( $_GET['offset'] ) ? intval( $_GET['offset'] ) : 0;

$typeResult = $connection->query( "SELECT * FROM type" );
$types = array();
if ( $typeResult->num_rows > 0 ) {
    while ( $row = $typeResult->fetch_assoc() ) {
        $types[] = $row;
    }
}
$typesByName = array();
foreach ( $types as $type ) {
    $typesByName[strtolower( $type['name'] )] = $type['id'];
}

$pokemonResult = $connection->query( "SELECT * FROM pokemon LIMIT $limit OFFSET $offset" );
$pokemon = array();
if ( $pokemonResult->num_rows > 0 ) {
    while ( $row = $pokemonResult->fetch_assoc() ) {
        $pokemon[] = $row;
    }
}

$pokemonUpdate = $connection->prepare( "UPDATE pokemon SET `type1` = ?, type2 = ? WHERE id = ?" );

$count = 0;
foreach ( $pokemon as $p ) {
    try {
        $data = file_get_contents( "https://pokeapi.co/api/v2/pokemon/"
            . strtolower( str_replace(" ", "-", $p['name']) ) );

        if ( !isset( $data ) || $data == null ) {
            echo print_r( "Failed to load info for pokemon: " . $p['name'], true ) . "<br>";
            continue;
        }

        $data = json_decode( $data );

        $type1 = $data->types[0]->type->name;
        $type2 = array_key_exists( 1, $data->types ) ? $data->types[1]->type->name : null;

        $pokemonUpdate->bind_param( "iii", $typesByName[$type1], $typesByName[$type2], $p['id'] );
        $pokemonUpdate->execute();

        echo "Fixed {$p["name"]}<br>";
    } catch ( Exception $e ) {
        echo print_r( "Exception occurred for pokemon: " . $p['name'], true ) . "<br>";
    }
}

echo "<a href='fix.php?offset=" . ( $offset + $limit ) . "&limit=$limit'>Next >></a>";
