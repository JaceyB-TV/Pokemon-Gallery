<?php

require_once 'dao.php';

class TypeDAO extends DAO
{
    function selectAll()
    {
        $query = "SELECT id, name, colour, border FROM type ORDER BY id";

        return $this->execute($query);
    }
}

$type_dao = new TypeDAO();
