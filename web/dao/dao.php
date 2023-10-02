<?php


class DAO
{
    function execute( $query )
    {
        include '../secret/secret.php';

        $result = $connection->query( $query );

        $rows = array();

        if ( $result->num_rows > 0 ) {
            while ( $row = $result->fetch_assoc() ) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
