<?php

include_once "header.php";
include_once "secret.php";

if ( isset( $_GET["showAll"] ) && $_GET["showAll"] === "true" ) {
    $gallerySql = "SELECT p.id, p.number, p.name, g.filename, g.datetime, g.viewer
               FROM pokemon AS p
               LEFT JOIN gallery AS g ON g.pokemon_id = p.id 
               ORDER BY p.id ASC";
}
else {
    $gallerySql = "SELECT p.id, p.number, p.name, g.filename, g.datetime, g.viewer
               FROM gallery AS g
               JOIN pokemon AS p ON p.id = g.pokemon_id
               ORDER BY datetime DESC";
}

$gallery = $connection->query( $gallerySql );

if ( isset( $_POST['pokemon'] ) ) {
    $pokemonId = $_POST['pokemon'];
    $now = time();
    $viewer = $_POST['viewer'];
    $file = $_FILES['file'];

    $fileError = $file['error'];

    if ( $fileError !== 0 ) {
        header( "Location: index.php?error=error.file." . $fileError );
        die();
    }

    // TODO: errors

    $fileDestination = "img/gallery/" . $pokemonId . "." . uniqid( "", true ) . ".png";

    $insertStatement = $connection->prepare( "INSERT INTO gallery (pokemon_id, filename, datetime, viewer) VALUES (?, ?, ?, ?)" );
    $insertStatement->bind_param( "isis", $pokemonId, $fileDestination, $now, $viewer );
    $insertStatement->execute();

    move_uploaded_file( $file['tmp_name'], $fileDestination );

    header( "Location: index.php?message=success" );
}

?>

    <p class="info">
        My name is Jacey and welcome to my Pokédex page! I draw Pokémon for fun. You can request a drawing while I'm
        streaming over at <u><a href="https://twitch.tv/JaceyB">Twitch</a></u>. Feel free to come say hi.
    </p>

    <div class="gallery">

        <?php
        while ( $row = $gallery->fetch_assoc() ) {
            $date = date( "d/m/Y", $row["datetime"] );

            echo "<a class='item' onclick='openModal(this)' href='javascript:void(0);'><div style='background-image: url({$row["filename"]});'></div><h3>{$row["number"]} {$row["name"]}</h3>";

            if ( $row['viewer'] ) {
                echo "<p>$date</p><p>Requested by <b>{$row["viewer"]}</b></p>";
            }

            echo "</a>";
        }

        ?>

    </div>

    <div id="modal-wrapper" class="modal-wrapper">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modal-content" alt="Modal Image" />
        <div id="modal-caption"></div>
    </div>

<?php

if ( $loggedIn ) {
    $pokemonSql = "SELECT p.id, p.name
               FROM pokemon AS p
               LEFT JOIN gallery AS g ON g.pokemon_id = p.id
               WHERE g.id IS NULL";

    $pokemon = $connection->query( $pokemonSql );

    echo "    <div class='upload'>
        <form action='index.php' method='post' enctype='multipart/form-data'>
            <div class='field'>
                <label for='pokemon'>Pokémon</label>
                <select name='pokemon' id='pokemon'>";
    while ( $p = $pokemon->fetch_assoc() ) {
        echo "<option value='" . $p["id"] . "'>" . $p["name"] . "</option>";
    }
    echo "</select>
            </div>
            <div class='field'>
                <label for='viewer'>Viewer</label>
                <input type='text' name='viewer' id='viewer'>
            </div>
            <div class='field'>
                <label for='file'>File</label>
                <input type='file' name='file' id='file'>
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
