<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/admin/base.php';
include_once $root . '/dao/form_suffix.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "name", "Name" ) );

$fields = array(
    new Field( Field::NUMBER, "id", "ID", null, true ),
    new Field( Field::TEXT, "name", "Name", null, false, true ) );

createBasePage( $loggedIn, $columns, $fields, $GLOBALS['form_suffix_dao'] );

include_once $root . "/common/footer.php";
