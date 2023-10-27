<?php

require_once 'dao.php';

class GameDAO extends DAO
{
    protected $db_name = "game";
}

$GLOBALS['game_dao'] = new GameDAO();
