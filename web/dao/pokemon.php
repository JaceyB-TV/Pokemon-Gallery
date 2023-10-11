<?php

require_once 'dao.php';

class PokemonDAO extends DAO
{
    function countAll( $weird_form = false, $missing = false )
    {
        if ( $weird_form || $missing ) {
            $query = "SELECT COUNT(*) AS cnt
                    FROM number AS n
                        LEFT JOIN pokemon AS p ON p.id = n.id
                        LEFT JOIN form AS f ON f.id = p.form_id";

            if ( $weird_form && $missing ) {
                $query .= " WHERE p.id IS NULL OR f.id > 1";
            }
            else if ( $weird_form ) {
                $query .= " WHERE f.id > 1";
            }
            else if ( $missing ) {
                $query .= " WHERE p.id IS NULL";
            }
        }
        else {
            $query = "SELECT COUNT(*) AS cnt FROM pokemon";
        }

        return $this->execute( $query )[0]['cnt'];
    }

    function findById( $id )
    {
        include '../secret/secret.php';

        $query = "SELECT * FROM pokemon WHERE id = ?";

        $statement = $connection->prepare( $query );
        $statement->bind_param( "i", $id );
        $statement->execute();

        return $statement->get_result()->fetch_all( MYSQLI_ASSOC )[0];
    }

    function findAll( $offset = 0, $limit = 50, $weird_form = false, $missing = false )
    {
        if ( $weird_form || $missing ) {
            $query = "SELECT
                        n.id
                        , p.id AS pokemon_id
                        , p.number
                        , p.name
                        , g.name AS gender_name
                        , g.short_name AS gender_short_name
                        , f.name AS form_name
                        , t1.name AS type1
                        , t2.name AS type2
                    FROM
                        number AS n
                            LEFT JOIN pokemon AS p ON p.id = n.id
                            LEFT JOIN gender AS g ON g.id = p.gender_id
                            LEFT JOIN form AS f ON f.id = p.form_id
                            LEFT JOIN type AS t1 ON t1.id = p.type1
                            LEFT JOIN type AS t2 ON t2.id = p.type2";

            if ( $weird_form && $missing ) {
                $query .= " WHERE p.id IS NULL OR f.id > 1";
            }
            else if ( $weird_form ) {
                $query .= " WHERE f.id > 1";
            }
            else if ( $missing ) {
                $query .= " WHERE p.id IS NULL";
            }

            $query .= " ORDER BY n.id LIMIT $limit OFFSET $offset";
        }
        else {
            $query = "SELECT
                        p.id AS pokemon_id
                        , p.number
                        , p.name
                        , g.name AS gender_name
                        , g.short_name AS gender_short_name
                        , f.name AS form_name
                        , t1.name AS type1
                        , t2.name AS type2
                    FROM pokemon AS p
                    JOIN gender AS g ON g.id = p.gender_id
                    JOIN form AS f ON f.id = p.form_id
                    JOIN type AS t1 ON t1.id = p.type1
                    LEFT JOIN type AS t2 ON t2.id = p.type2
                    ORDER BY p.number, p.gender_id, p.form_id
                    LIMIT " . $limit . " OFFSET " . $offset;
        }

        return $this->execute( $query );
    }
}

$pokemon_dao = new PokemonDAO();
