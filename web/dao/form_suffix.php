<?php

require_once 'dao.php';

class FormSuffixDAO extends DAO
{
    function findAll()
    {
        $query = "SELECT * FROM form_suffix ORDER BY id";

        return $this->execute( $query );
    }

}

$form_suffix_dao = new FormSuffixDAO();
