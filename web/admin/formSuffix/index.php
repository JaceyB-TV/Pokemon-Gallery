<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/common/table.php';
include_once $root . '/dao/form_suffix.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "name", "Name" ) );

$table = new Table( $columns, $form_suffix_dao->findAll() );

$table->print();

include "../../common/footer.php";
