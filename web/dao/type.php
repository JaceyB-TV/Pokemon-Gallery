<?php

require_once 'dao.php';

class TypeDAO extends DAO
{
    protected $db_name = "type";
}

$type_dao = new TypeDAO();
