<?php

require_once 'dao.php';

class PokemonDAO extends DAO
{
    function selectAll()
    {
        $query = "SELECT
                    p.id
                    , p.number
                    , p.name
                    , g.name AS gender_name
                    , f.name AS form_name
                    , t1.name AS type1
                    , t2.name AS type2
                FROM pokemon AS p
                JOIN gender AS g ON g.id = p.gender_id
                JOIN form AS f ON f.id = p.form_id
                JOIN type AS t1 ON t1.id = p.type1
                LEFT JOIN type AS t2 ON t2.id = p.type2
                ORDER BY p.number, p.gender_id, p.form_id";

        return $this->execute( $query );
    }
}

$pokemon_dao = new PokemonDAO();
