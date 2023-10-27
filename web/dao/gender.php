<?php

require_once 'dao.php';

class GenderDAO extends DAO
{
    protected $db_name = "gender";
}

$GLOBALS['gender_dao'] = new GenderDAO();
