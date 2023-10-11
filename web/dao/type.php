<?php

require_once 'dao.php';

class TypeDAO extends DAO
{
    function findAll()
    {
        $query = "SELECT * FROM type ORDER BY id";

        return $this->execute($query);
    }
}

$type_dao = new TypeDAO();
