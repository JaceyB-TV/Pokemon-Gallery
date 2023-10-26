<?php

$root = $_SERVER['DOCUMENT_ROOT'];

echo $root;

include_once $root . '/common/header.php';
include_once $root . '/common/table.php';
include_once $root . '/dao/form.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "form_type_id", "Form Type ID", null, true ),
    new Column( "form_type_id", "Form Type", "form_type_name" ),
    new Column( "name", "Name" ),
    new Column( "suffix", "Suffix" ) );

$table = new Table( $columns, $form_dao->findAllForDisplay() );

$table->print();

include "../../common/footer.php";
