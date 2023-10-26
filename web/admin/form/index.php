<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/header.php';
include_once $root . '/common/table.php';
include_once $root . '/common/form.php';
include_once $root . '/dao/form.php';

$columns = array(
    new Column( "id", "ID" ),
    new Column( "form_type_id", "Form Type", "form_type_name" ),
    new Column( "name", "Name" ),
    new Column( "suffix", "Suffix" ) );

$fields = array(
    new Field( Field::TEXT, "id", "ID", null, true ),
    new Field( Field::TEXT, "form_type_id", "Form Type", "form_type" ),
    new Field( Field::TEXT, "name", "Name" ),
    new Field( Field::TEXT, "suffix", "Suffix" ) );

if ( $loggedIn && isset( $_POST['id'] ) ) {
    $values = array();

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field->dataIndex] ) && $_POST[$field->dataIndex] !== "" ) {
            $values[$field->dataIndex] = $_POST[$field->dataIndex];
        }
    }

    if ( isset( $_GET['id'] ) ) {
        $result = $form_dao->update( $values );
    }
    else {
        $result = $form_dao->create( $values );
    }

    if ( $result !== '' ) {
        header( "Location: /admin/form?error=insert" );
        die();
    }

    header( "Location: /admin/form?message=success" );
}

$table = new Table( $columns, $form_dao->findAllForDisplay(), $loggedIn );
$table->echo_me();

if ( $loggedIn ) {
    $record = null;

    if ( isset( $_GET['id'] ) ) {
        $record = $form_dao->findById( $_GET['id'] );
    }

    $form = new Form( $fields, $record );
    $form->echo_me();
}

include "../../common/footer.php";
