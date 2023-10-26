<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/admin/base.php';
include_once $root . '/dao/gender.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "name", "Name" ),
    new Column( "short_name", "Short Name" ),
    new Column( "suffix", "Suffix" ) );

$fields = array(
    new Field( Field::TEXT, "id", "ID", null, true ),
    new Field( Field::TEXT, "name", "Name", null, false, true ),
    new Field( Field::TEXT, "short_name", "Short Name" ),
    new Field( Field::TEXT, "suffix", "Suffix" ) );

createBasePage( $loggedIn, $columns, $fields, $gender_dao );

include "../../common/footer.php";
