<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/admin/base.php';
include_once $root . '/dao/form.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "form_type_id", "Form Type", "form_type_name" ),
    new Column( "name", "Name" ),
    new Column( "suffix", "Suffix" ) );

$fields = array(
    new Field( Field::NUMBER, "id", "ID", null, true, false ),
    new Field( Field::LST, "form_type_id", "Form Type", "form_type", false, true ),
    new Field( Field::TEXT, "name", "Name", null, false, true ),
    new Field( Field::TEXT, "suffix", "Suffix" ) );

createBasePage( $loggedIn, $columns, $fields, $form_dao );

include "../../common/footer.php";
