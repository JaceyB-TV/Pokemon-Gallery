<?php

include '../common/header.php';
include_once '../secret/secret.php';

if ( !isset( $_GET['id'] ) ) {
    header( "Location: /shiny" );
    die();
}

$shinyId = $_GET['id'];

include_once '../dao/shiny.php';

$found_shiny = $shiny_dao->findById( $shinyId );

include 'form.php';
