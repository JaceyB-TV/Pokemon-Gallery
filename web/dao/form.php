<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/dao/dao.php';

class FormDAO extends DAO
{
    function findAll()
    {
        $query = "SELECT * FROM form ORDER BY id";

        return $this->execute( $query );
    }

    function findAllForDisplay() {
        $query = "
        SELECT 
            f.id
            , f.form_type_id
            , ft.name AS form_type_name
            , f.name
            , f.suffix
        FROM form AS f
            JOIN form_type AS ft ON ft.id = f.form_type_id
        ORDER BY
            id";

        return $this->execute( $query );

    }
}

$form_dao = new FormDAO();
