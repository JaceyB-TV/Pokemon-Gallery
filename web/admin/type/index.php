<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/admin/base.php';
include_once $root . '/dao/type.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "name", "Name" ),
    new Column( "colour", "Colour" ),
    new Column( "border", "Border" ) );

$fields = array(
    new Field( Field::NUMBER, "id", "ID", null, true ),
    new Field( Field::TEXT, "name", "Name", null, false, true ),
    new Field( Field::TEXT, "colour", "Colour" ),
    new Field( Field::TEXT, "border", "Border" ) );

createBasePage( $loggedIn, $columns, $fields, $type_dao );

include "../../common/footer.php";
