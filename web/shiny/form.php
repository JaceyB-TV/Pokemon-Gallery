<?php

if ( isset( $found_shiny ) ) {
    $found_id = $found_shiny['id'];
    $found_pokemon_id = $found_shiny['pokemon_id'];
    $found_game_id = $found_shiny['game_id'];
    $found_caught_date = $found_shiny['caught_date'];
}
else {
    $found_id = '';
    $found_caught_date = date( 'Y-m-d' );
}

$pokemonSql = "SELECT p.id, p.name AS pokemon_name, p.gender_id, p.form_id, f.name AS form_name
               FROM pokemon AS p
               JOIN form AS f ON f.id = p.form_id
               LEFT JOIN shiny AS s ON s.pokemon_id = p.id
               WHERE s.id IS NULL";

if ( isset( $found_pokemon_id ) ) {
    $pokemonSql .= " OR p.id = $found_pokemon_id";
}

$pokemonSql .= " ORDER BY p.id";

$pokemonResult = $connection->query( $pokemonSql );
$pokemon = array();
while ( $p = $pokemonResult->fetch_assoc() ) {
    $pokemon[] = $p;
}
$pokemonOptions = "";
foreach ( $pokemon as $p ) {
    $id = $p['id'];
    $pokemon = $p['pokemon_name'];
    $gender = $p['gender_id'];
    $formId = $p['form_id'];
    $form = $p['form_name'];

    $pokemonValue = "";

    if ( $formId > 1 ) {
        $pokemonValue .= "$form ";
    }

    $pokemonValue .= $pokemon;

    if ( isset( $gender ) ) {
        if ( $gender === '2' ) {
            $pokemonValue .= " ♂";
        }
        else if ( $gender === '3' ) {
            $pokemonValue .= " ♀";
        }
    }

    if ( isset( $found_pokemon_id ) && $id == $found_pokemon_id ) {
        $pokemonOptions .= "\n\t\t\t\t\t<option value='$id' selected>$pokemonValue</option>";
    }
    else {
        $pokemonOptions .= "\n\t\t\t\t\t<option value='$id'>$pokemonValue</option>";
    }
}

$gameSql = "SELECT g.id, g.name, g.default_selection FROM game AS g ORDER BY g.id";
$gamesResult = $connection->query( $gameSql );
$games = array();
while ( $g = $gamesResult->fetch_assoc() ) {
    $games[] = $g;
}
$gameOptions = "";
foreach ( $games as $g ) {
    $id = $g['id'];
    $name = $g['name'];

    if ( isset( $found_game_id ) ) {
        if ( $id == $found_game_id ) {
            $gameOptions .= "\n\t\t\t\t\t<option value='$id' selected>$name</option>";
        }
        else {
            $gameOptions .= "\n\t\t\t\t\t<option value='$id'>$name</option>";
        }
    }
    else {
        if ( $g['default_selection'] ) {
            $gameOptions .= "\n\t\t\t\t\t<option value='$id' selected>$name</option>";
        }
        else {
            $gameOptions .= "\n\t\t\t\t\t<option value='$id'>$name</option>";
        }
    }
}

echo "
    <div class='upload'>
        <form method='post'>
            <div class='field' style='display: none; '>
                <label for='id'>ID</label>
                <input type='hidden' name='id' id='id' value='$found_id'/>
            </div>
            <div class='field'>
                <label for='pokemon'>Pokémon</label>
                <select name='pokemon' id='pokemon'>$pokemonOptions
                </select>
            </div>
            <div class='field'>
                <label for='game_id'>Game</label>
                <select name='game_id' id='game_id'>$gameOptions
                </select>
            </div>
            <div class='field'>
                <label for='date'>Date</label>
                <input type='date' name='date' id='date' value='$found_caught_date'/>
            </div>
            <div class='submit'>
                <button type='submit' name='submit'>SAVE</button>
            </div>
        </form>
    </div>";

?>