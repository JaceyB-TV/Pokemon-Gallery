<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/secret/secret.php';

if ( !isset( $_GET['id'] ) ) {
    header( "Location: /shiny" );
    die();
}

$shinyId = $_GET['id'];

include_once $root . '/dao/shiny.php';

$found_shiny = $GLOBALS['shiny_dao']->findById( $shinyId );

include 'form.php';
