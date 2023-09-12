<?php

include_once "header.php";
include_once "secret.php";

$shinySql = "SELECT * FROM shiny";

$shinyResult = $connection->query( $shinySql );

?>

	<div class="shiny">
		<?php
		$shinies = array();

		while ( $shiny = $shinyResult->fetch_assoc() ) {
			$shinies[$shiny["pokemon_id"]] = $shiny;
		}

		$showAll = isset( $_GET["showAll"] ) && $_GET["showAll"];

		$id = 1;
		while ( $id <= 1010 ) {
			$exists = array_key_exists($id, $shinies);

			$pokemonNumber = sprintf('%03d', $id);

			$date = "";
			$class = "item gray";

			if ( $exists ) {
				$date = $shinies[$id]["caught_date"];
				$class = "item";
			} else if ( !$showAll ) {
				$id++;
				continue;
			}

			echo "<a class='$class' href='javascript:void(0);'>
					<div style='background-image: url(https://www.serebii.net/Shiny/SV/new/$pokemonNumber.png); '></div>
					<p>$date</p>
					<p>$id</p>
				</a>";

			$id++;
		}
		?>
	</div>

<?php

include_once "footer.php";

$connection->close();

?>