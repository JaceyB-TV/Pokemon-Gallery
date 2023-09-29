<?php

include_once "header.php";
include_once "secret.php";

$shinySql = "SELECT * FROM shiny";

$shinyResult = $connection->query( $shinySql );

$shinies = array();

while ( $shiny = $shinyResult->fetch_assoc() ) {
    $shinies[$shiny["pokemon_id"]] = $shiny;
}

$shinyCount = count( $shinies );
$shinyPercentage = sprintf( "%.2f%%", $shinyCount / 1010 );

if ( isset( $_POST['pokemon'] ) ) {
    $pokemonId = $_POST['pokemon'];
    $gameId = $_POST['game_id'];
    $date = $_POST['date'];

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
        header( "Location: shiny.php?error=database" );
        die();
    }

    header( "Location: shiny.php?message=success" );
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

    <div class="shiny">
        <?php
        $showAll = isset( $_GET["showAll"] ) && $_GET["showAll"] === "true";

        $id = 0;
        while ( $id <= 1010 ) {
            $exists = array_key_exists( ++$id, $shinies );

            $pokemonNumber = sprintf( '%03d', $id );

            $date = "";
            $class = "item gray";

            if ( $exists ) {
                $date = $shinies[$id]["caught_date"];
                $class = "item";
            }
            else if ( !$showAll ) {
                continue;
            }

            echo "<a class='$class' href='javascript:void(0);'>
					<div style='background-image: url(https://www.serebii.net/Shiny/SV/new/$pokemonNumber.png); '></div>
					<p>$date</p>
					<p>$id</p>
				</a>";
        }
        ?>
    </div>

<?php

if ( $loggedIn ) {
    $pokemonSql = "SELECT p.id, p.name
               FROM pokemon AS p
               LEFT JOIN shiny AS s ON s.pokemon_id = p.id
               WHERE s.id IS NULL";

    $pokemon = $connection->query( $pokemonSql );

    $gameSql = "SELECT g.id, g.name FROM game AS g";

    $games = $connection->query( $gameSql );

    echo "    <div class='upload'>
        <form action='shiny.php' method='post'>
            <div class='field'>
                <label for='pokemon'>Pok�mon</label>
                <select name='pokemon' id='pokemon'>";
    while ( $p = $pokemon->fetch_assoc() ) {
        echo "<option value='" . $p["id"] . "'>" . $p["name"] . "</option>";
    }
    echo "</select>
            </div>
            <div class='field'>
            <label for='game_id'>Game</label>
            <select name='game_id' id='game_id'>";

    while ( $g = $games->fetch_assoc() ) {
        echo "<option value='" . $g["id"] . "'>" . $g["name"] . "</option>";
    }

    echo "</select>
            </div><div class='field'>
                <label for='date'>Date</label>
                <input type='date' name='date' id='date'>
            </div>
            <div class='submit'>
                <button type='submit' name='submit'>UPLOAD</button>
            </div>
        </form>
    </div>";
}

include_once "footer.php";

$connection->close();

?>
