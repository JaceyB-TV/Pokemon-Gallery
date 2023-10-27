<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/common/functions.php';
include_once $root . '/common/table.php';
include_once $root . '/common/form.php';

function createBasePage( $loggedIn, $columns, $fields, $dao )
{
    if ( $loggedIn && isset( $_POST['id'] ) ) {
        create( $fields, $dao );
    }

    if ( $loggedIn && isset( $_GET['delete'] ) && $_GET['delete'] === 'true' ) {
        delete( $dao );
    }

    $offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
    $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 10;

    $table = new Table( $columns, $dao->findAllForDisplay( $offset, $limit ), $dao->countAll(), $loggedIn );
    $table->echo_me();

    if ( $loggedIn ) {
        $record = null;

        if ( isset( $_GET['id'] ) ) {
            $record = $dao->findById( $_GET['id'] );
        }

        $form = new Form( $fields, $record );
        $form->echo_me();
    }
}
