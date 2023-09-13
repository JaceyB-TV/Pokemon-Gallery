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
    $date = $_POST['date'];

    // TODO: errors

    $insertStatement = $connection->prepare( "INSERT INTO shiny (pokemon_id, caught_date) VALUES (?, ?)" );
    $insertStatement->bind_param( "si", $pokemonId, $date );
    $insertStatement->execute();

    header( "Location: shiny.php?message=success" );
}

?>

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

    echo "    <div class='upload'>
        <form action='shiny.php' method='post'>
            <div class='field'>
                <label for='pokemon'>Pokémon</label>
                <select name='pokemon' id='pokemon'>";
    while ( $p = $pokemon->fetch_assoc() ) {
        echo "<option value='" . $p["id"] . "'>" . $p["name"] . "</option>";
    }
    echo "</select>
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
}

include_once "footer.php";

$connection->close();

?>
