<?php

require_once 'dao.php';

class GenderDAO extends DAO
{
    function findAll()
    {
        $query = "SELECT * FROM gender ORDER BY id";

        return $this->execute( $query );
    }
}

$gender_dao = new GenderDAO();
