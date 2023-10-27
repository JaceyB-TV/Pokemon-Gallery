<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/admin/base.php';
include_once $root . '/dao/game.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "name", "Name" ),
    new Column( "default_selection", "Default" ) );

$fields = array(
    new Field( Field::NUMBER, "id", "ID", null, true ),
    new Field( Field::TEXT, "name", "Name", null, false, true ),
    new Field( Field::TEXT, "default_selection", "Default" ) );

createBasePage( $loggedIn, $columns, $fields, $GLOBALS['game_dao'] );

include_once $root . "/common/footer.php";
