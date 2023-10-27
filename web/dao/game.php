<?php

require_once 'dao.php';

class GameDAO extends DAO
{
    protected $db_name = "game";
}

$game_dao = new GameDAO();
