<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/dao/dao.php';

class FormSuffixDAO extends DAO
{
    protected $db_name = 'form_suffix';
}

$form_suffix_dao = new FormSuffixDAO();
