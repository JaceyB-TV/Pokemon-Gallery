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

		$id = 0;
		while ( $id <= 1009 ) {
			$shiny = $shinies[++$id];
			$pokemonNumber = sprintf('%03d', $id);

			if ( !isset($shiny) ) {
				echo "<a class='item' href='javascript:void(0);'>
					<div style='background-image: url(https://www.serebii.net/Shiny/SV/new/$pokemonNumber.png); '></div>
						<p>$id</p>
					</a>";
				continue;
			}

			echo "<a class='item' href='javascript:void(0);'>
					<div style='background-image: url(https://www.serebii.net/Shiny/SV/new/$pokemonNumber.png); '></div>
					<p>$id</p>
				</a>";
		}
		?>
	</div>

<?php

include_once "footer.php";

$connection->close();

?>