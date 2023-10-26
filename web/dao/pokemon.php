<?php

require_once 'dao.php';

class PokemonDAO extends DAO
{
    protected $db_name = "pokemon";

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

    public function findById( $id ): array
    {
        $query = "SELECT * FROM pokemon WHERE id = $id";

        return $this->execute( $query )[0];
    }

    public function findAll( $offset = 0, $limit = 50, $weird_form = false, $missing = false ): array
    {
        if ( $weird_form || $missing ) {
            $query = "SELECT
                        n.id
                        , p.id AS pokemon_id
                        , p.number
                        , p.name
                        , g.name AS gender_name
                        , g.suffix AS gender_suffix
                        , g.short_name AS gender_short_name
                        , f.name AS form_name
                        , fs.name AS form_suffix
                        , t1.name AS type1
                        , t2.name AS type2
                    FROM
                        number AS n
                            LEFT JOIN pokemon AS p ON p.id = n.id
                            LEFT JOIN gender AS g ON g.id = p.gender_id
                            LEFT JOIN form AS f ON f.id = p.form_id
                            LEFT JOIN form_suffix AS fs ON fs.id = p.form_suffix_id
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
                        , g.suffix AS gender_suffix
                        , g.short_name AS gender_short_name
                        , f.name AS form_name
                        , fs.name AS form_suffix
                        , t1.name AS type1
                        , t2.name AS type2
                    FROM pokemon AS p
                    JOIN gender AS g ON g.id = p.gender_id
                    JOIN form AS f ON f.id = p.form_id
                    LEFT JOIN form_suffix AS fs ON fs.id = p.form_suffix_id
                    JOIN type AS t1 ON t1.id = p.type1
                    LEFT JOIN type AS t2 ON t2.id = p.type2
                    ORDER BY p.number, p.gender_id, p.form_id
                    LIMIT " . $limit . " OFFSET " . $offset;
        }

        return $this->execute( $query );
    }
}

$pokemon_dao = new PokemonDAO();
