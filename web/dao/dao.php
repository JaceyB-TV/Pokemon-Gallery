<?php

class DAO
{
    protected $db_name;

    protected function execute( $query )
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/secret/secret.php';

        $result = $connection->query( $query );

        $rows = array();

        if ( $result->num_rows > 0 ) {
            while ( $row = $result->fetch_assoc() ) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    public function create( $record ): string
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/secret/secret.php';

        $field_count = count( $record );
        $keys = join( ", ", array_keys( $record ) );
        $values = substr_replace( str_repeat( "?,", $field_count ), "", -1 );
        $types = str_repeat( "s", $field_count );

        $query = "INSERT INTO $this->db_name ( $keys ) VALUES ( $values )";

        $statement = $connection->prepare( $query );
        $statement->bind_param( $types, ...array_values( $record ) );
        $statement->execute();

        return $statement->error;
    }

    public function update( $record ): string
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/secret/secret.php';

        $field_count = count( $record );
        $keys = join( " = ?, ", array_keys( $record ) );
        $types = str_repeat( "s", $field_count );

        $query = "UPDATE $this->db_name SET $keys = ? WHERE id = {$record['id']}";

        echo $query;

        $statement = $connection->prepare( $query );
        $statement->bind_param( $types, ...array_values( $record ) );
        $statement->execute();

        return $statement->error;
    }
}
