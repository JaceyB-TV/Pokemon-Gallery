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

include_once "footer.php";

$connection->close();

?>
