<?php

require_once 'dao.php';

class ShinyDAO extends DAO
{
    function findById( $id )
    {
        include '../secret/secret.php';

        $query = "SELECT * FROM shiny WHERE id = ?";

        $statement = $connection->prepare( $query );
        $statement->bind_param( "i", $id );
        $statement->execute();

        return $statement->get_result()->fetch_all( MYSQLI_ASSOC )[0];
    }

    function selectAll()
    {
        $query = "";

        return $this->execute( $query );
    }
}

$shiny_dao = new ShinyDAO();
