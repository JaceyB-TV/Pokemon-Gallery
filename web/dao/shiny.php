<?php

require_once 'dao.php';

class ShinyDAO extends DAO
{
    function findById( $id )
    {
        include '../secret/secret.php';

        $query = "SELECT * FROM shiny WHERE id = ?";

        $statement = $connection->prepare( $query );
        $statement->bind_param( "i", $id );
        $statement->execute();

        return $statement->get_result()->fetch_all( MYSQLI_ASSOC )[0];
    }

    function countAll()
    {
        $query = "SELECT COUNT(*) AS cnt FROM shiny";

        return $this->execute( $query )[0]['cnt'];
    }

    function findAll( $includeMissing = false, $sort = 1 )
    {
        if ( $includeMissing ) {
            $query = "SELECT
                    s.id
                    , s.pokemon_id
                    , p.number AS pokemon_number
                    , p.name AS pokemon_name
                    , p.gender_id
                    , g.name AS gender_name
                    , g.suffix AS gender_suffix
                    , p.form_id
                    , f.name AS form_name
                    , f.suffix AS form_suffix
                    , s.caught_date
                FROM pokemon AS p
                JOIN gender AS g ON g.id = p.gender_id
                JOIN form AS f ON f.id = p.form_id
                LEFT JOIN shiny AS s ON s.pokemon_id = p.id";
        }
        else {
            $query = "SELECT
                    s.id
                    , s.pokemon_id
                    , p.number AS pokemon_number
                    , p.name AS pokemon_name
                    , p.gender_id
                    , g.name AS gender_name
                    , g.suffix AS gender_suffix
                    , p.form_id
                    , f.name AS form_name
                    , f.suffix AS form_suffix
                    , s.caught_date
                FROM shiny AS s
                JOIN pokemon AS p ON p.id = s.pokemon_id
                JOIN gender AS g ON g.id = p.gender_id
                JOIN form AS f ON f.id = p.form_id";
        }

        switch ( $sort ) {
            case "1":
            default:
            {
                $query .= " ORDER BY p.id ASC";
                break;
            }
            case "2":
            {
                $query .= " ORDER BY p.id DESC";
                break;
            }
            case "3":
            {
                $query .= " ORDER BY s.caught_date DESC, p.id DESC";
                break;
            }
            case "4":
            {
                $query .= " ORDER BY s.caught_date ASC, p.id ASC";
                break;
            }
        }

        return $this->execute( $query );
    }
}

$shiny_dao = new ShinyDAO();
