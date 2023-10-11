<?php

include '../common/header.php';
include_once '../secret/secret.php';

if ( !isset( $_GET['id'] ) ) {
    header( "Location: /pokemon" );
    die();
}

if ( isset ( $_POST['number'] ) ) {
    $id = $_POST['id'];
    $number = $_POST['number'];
    $name = $_POST['name'];
    $gender_id = $_POST['gender'];
    $form_id = $_POST['form'];
    $type1 = $_POST['type1'];
    $type2 = ( $_POST['type2'] === "" || $_POST['type2'] === 0 ) ? null : $_POST['type2'];

    $query = "UPDATE pokemon SET number = ?, name = ?, gender_id = ?, form_id = ?, type1 = ?, type2 = ? WHERE id = ?";

    $updateStatement = $connection->prepare( $query );
    $updateStatement->bind_param( "isiiiii", $number, $name, $gender_id, $form_id, $type1, $type2, $id );
    $updateStatement->execute();

    if ( $updateStatement->error !== "" ) {
        header( "Location: /pokemon/edit.php?id=$id&error=insert" );
        die();
    }

    header( "Location: /pokemon?message=success" );
}

$pokemon_id = $_GET['id'];

include_once '../dao/pokemon.php';

$found_pokemon = $pokemon_dao->findById( $pokemon_id );

include_once 'form.php';
