<?php

function create( $fields, $dao )
{
    $values = array();

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field->dataIndex] ) && $_POST[$field->dataIndex] !== "" ) {
            $values[$field->dataIndex] = $_POST[$field->dataIndex];
        }
        else if ( $field->required ) {
            header( "Location: ?error=fields" );
            die();
        }
    }

    if ( isset( $_GET['id'] ) ) {
        $result = $dao->update( $values );
    }
    else {
        $result = $dao->create( $values );
    }

    if ( $result === false ) {
        header( "Location: ?error=prepare" );
        die();
    }

    if ( $result !== '' ) {
        header( "Location: ?error=insert" );
        die();
    }

    header( "Location: ?message=success" );
}

function delete( $dao )
{
    $result = $dao->delete( $_GET['id'] );

    if ( $result !== '' ) {
        header( "Location: ?error=delete" );
        die();
    }

    header( "Location: ?message=delete" );
}
