<?php

require_once 'dao.php';

class GenderDAO extends DAO
{
    protected $db_name = "gender";
}

$gender_dao = new GenderDAO();
