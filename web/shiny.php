<?php

include_once "header.php";
include_once "secret.php";

$shinySql = "SELECT s.id, s.pokemon_id, p.name AS pokemon_name, s.gender_id, g.name AS gender_name, s.form_id, f.name AS form_name, s.caught_date
             FROM shiny AS s
             JOIN pokemon AS p ON p.id = s.pokemon_id
             JOIN gender AS g ON g.id = s.gender_id
             JOIN form AS f ON f.id = s.form_id";
$shinyResult = $connection->query( $shinySql );
$shinies = array();
while ( $shiny = $shinyResult->fetch_assoc() ) {
    $shinies[ $shiny[ "pokemon_id" ] ] = $shiny;
}

$shinyCount = count( $shinies );
$shinyPercentage = sprintf( "%.2f%%", $shinyCount / 1010 );

if ( isset( $_POST[ 'pokemon' ] ) ) {
    $pokemonId = $_POST[ 'pokemon' ];
    $gameId = $_POST[ 'game_id' ];
    $date = $_POST[ 'date' ];

    if ( !$pokemonId || !$gameId || !$date ) {
        header( "Location: shiny.php?error=fields" );
        die();
    }

    if ( !$insertStatement = $connection->prepare( "INSERT INTO shiny (pokemon_id, game_id, caught_date) VALUES (?, ?, ?)" ) ) {
        header( "Location: shiny.php?error=prepare" );
        die();
    }

    $insertStatement->bind_param( "sss", $pokemonId, $gameId, $date );
    $insertStatement->execute();

    if ( $insertStatement->error !== "" ) {
        header( "Location: shiny.php?error=insert" );
        die();
    }

    header( "Location: shiny.php?message=success" );
}

if ( isset ( $_GET[ 'delete' ] ) && $_GET[ 'delete' ] === "true" ) {
    header( "Location: shiny.php?message=delete" );
}
?>
<p class="info">
    My name is Jacey and welcome to my Shiny page! I am currently trying to complete a shiny living dex. You can
    join in with the journey while I'm streaming over at <u><a href="https://twitch.tv/JaceyB">Twitch</a></u>.
    Feel free to come say hi.
</p>
<div class="progress">
    <div style="width:<?php echo $shinyPercentage ?>; ">
        <p>
            <?php echo $shinyCount ?> / 1010 (<?php echo $shinyPercentage ?>)
        </p>
    </div>
</div>
<div class="shiny"><?php
    $showAll = isset( $_GET[ "showAll" ] ) && $_GET[ "showAll" ] === "true";

    $id = 0;
    while ( $id < 1010 ) {
        $exists = array_key_exists( ++$id, $shinies );

        $pokemonNumber = sprintf( '%03d', $id );

        $date = "";
        $class = "item gray";

        if ( $exists ) {
            $date = $shinies[ $id ][ "caught_date" ];
            $class = "item";
        }
        else if ( !$showAll ) {
            continue;
        }

        echo "
        <a class='$class' href='javascript:void(0);'>
            <div style='background-image: url(https://www.serebii.net/Shiny/SV/new/$pokemonNumber.png); '></div>
            <p>$date</p>
            <p>$id</p>
        </a>";
    }
    ?>

</div><?php

if ( $loggedIn ) {
    $pokemonSql = "SELECT p.id, p.name
               FROM pokemon AS p
               LEFT JOIN shiny AS s ON s.pokemon_id = p.id
               WHERE s.id IS NULL";
    $pokemonResult = $connection->query( $pokemonSql );
    $pokemon = array();
    while ( $p = $pokemonResult->fetch_assoc() ) {
        $pokemon[] = $p;
    }
    $pokemonOptions = "";
    foreach ( $pokemon as $p ) {
        $id = $p[ 'id' ];
        $name = $p[ 'name' ];
        $pokemonOptions .= "\n\t\t\t\t\t<option value='$id'>$name</option>";
    }

    $gameSql = "SELECT g.id, g.name FROM game AS g";
    $gamesResult = $connection->query( $gameSql );
    $games = array();
    while ( $g = $gamesResult->fetch_assoc() ) {
        $games[] = $g;
    }
    $gameOptions = "";
    foreach ( $game as $g ) {
        $id = $g[ 'id' ];
        $name = $g[ 'name' ];
        $gameOptions .= "\n\t\t\t\t\t<option value='$id'>$name</option>";
    }

    echo "
    <div class='upload'>
        <form action='shiny.php' method='post'>
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
                <input type='date' name='date' id='date'>
            </div>
            <div class='submit'>
                <button type='submit' name='submit'>UPLOAD</button>
            </div>
        </form>
    </div>";

    echo "
    <div class='table'>
        <table>
            <tr>
                <th>ID</th>
                <th>Pokemon</th>
                <th>Gender</th>
                <th>Form</th>
                <th colspan='2'>Actions</th>
            </tr>";

    foreach ( $shinies as $shiny ) {
        $id = $shiny[ 'id' ];
        $pokemonId = $shiny[ "pokemon_id" ];
        $pokemon = $shiny[ "pokemon_name" ];
        $genderId = $shiny[ "gender_id" ];
        $gender = $shiny[ "gender_name" ];
        $formId = $shiny[ "form_id" ];
        $form = $shiny[ "form_name" ];

        echo "
            <tr>
                <td>$id</td>
                <td>$pokemon</td>
                <td>$gender</td>
                <td>$form</td>
                <td style='width: 42px;'>
                    <form action='shiny.php?delete=true&id=$id&gender_id=$genderId&form_id=$formId' method='post'>
                        <button type='submit' name='submit'>&#xe065;</button>
                    </form>
                </td>
                <td style='width: 42px;'>
                    <form action='shiny.php?delete=true&id=$id&gender_id=$genderId&form_id=$formId' method='post'>
                        <button type='submit' name='submit'>&times;</button>
                    </form>
                </td>
            </tr>";
    }

    echo "
        </table>
    </div>";
}

include_once "footer.php";

$connection->close();

?>
