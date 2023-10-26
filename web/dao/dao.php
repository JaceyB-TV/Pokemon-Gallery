<?php

class DAO
{
    protected $db_name;

    protected function execute( $query ): array
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

    public function findById( $id ): array
    {
        $query = "SELECT * FROM $this->db_name WHERE id = $id";

        return $this->execute( $query )[0];
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM $this->db_name ORDER BY id";

        return $this->execute( $query );
    }

    public function findAllForDisplay(): array
    {
        return $this->findAll();
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
        if ( !$statement ) {
            return $statement;
        }
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

        $statement = $connection->prepare( $query );
        if ( !$statement ) {
            return $statement;
        }
        $statement->bind_param( $types, ...array_values( $record ) );
        $statement->execute();

        return $statement->error;
    }

    public function delete( $id ): string
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/secret/secret.php';

        $query = "DELETE FROM $this->db_name WHERE id = ?";

        $statement = $connection->prepare( $query );
        if ( !$statement ) {
            return $statement;
        }
        $statement->bind_param( "i", $id );
        $statement->execute();

        return $statement->error;
    }
}
