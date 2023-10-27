<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/dao/dao.php';

class FormTypeDAO extends DAO
{
    protected $db_name = 'form_type';
}

$GLOBALS['form_type_dao'] = new FormTypeDAO();
