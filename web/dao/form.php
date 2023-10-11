<?php

require_once 'dao.php';

class FormDAO extends DAO
{
    function findAll()
    {
        $query = "SELECT * FROM form ORDER BY id";

        return $this->execute( $query );
    }

}

$form_dao = new FormDAO();
