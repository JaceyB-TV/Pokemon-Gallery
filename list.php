<?php

include "header.php";
include "secret.php";

$pokemonSql = "SELECT p.id, p.name, t1.name AS type1, t2.name AS type2
		       FROM pokemon AS p
		       JOIN type AS t1 ON t1.id = p.type1
		       LEFT JOIN type AS t2 ON t2.id = p.type2";
$pokemonResult = $connection->query( $pokemonSql );

$typeSql = "SELECT id, name FROM type";
$typesResult = $connection->query( $typeSql );
$types = array();
if ( $typesResult->num_rows > 0 ) {
    while ( $t = $typesResult->fetch_assoc() ) {
        $types[] = $t;
    }
}

if ( isset( $_POST['id'] ) ) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type1 = $_POST['type1'];
    $type2 = $_POST['type2'];

    $insertStatement = $connection->prepare( "INSERT INTO pokemon (id, name, type1, type2) VALUES (?, ?, ?, ?)" );
    $insertStatement->bind_param( "isii", $id, $name, $type1, $type2 );
    $insertStatement->execute();

    header( "Location: list.php?message=success" );
}

?>

<div class="table">
    <div class="header row">
        <div class="cell">#</div>
        <div class="cell">Pokémon</div>
        <div class="cell">Type 1</div>
        <div class="cell">Type 2</div>
    </div>
    <?php
    if ( $pokemonResult->num_rows > 0 ) {
        while ( $row = $pokemonResult->fetch_assoc() ) {
            echo "
            <div class=\"row\">
                <div class=\"cell\">{$row["id"]}</div>
                <div class=\"cell\">{$row["name"]}</div>
                <div class=\"cell\">{$row["type1"]}</div>
                <div class=\"cell\">{$row["type2"]}</div>
            </div>";
        }
    }
    else {
        echo "No Content";
    }
    ?>
</div>

<div class="upload">
    <form action="list.php" method="post">
        <div class="field">
            <label for="id">#</label>
            <input type="number" id="id" name="id" placeholder="#"/>
        </div>
        <div class="field">
            <label for="name"></label>
            <input type="text" id="name" name="name" placeholder="Name"/>
        </div>
        <div class="field">
            <label for="type1">Type 1</label>
            <select id="type1" name="type1">
                <?php
                foreach ( $types as $type ) {
                    echo "<option value='{$type["id"]}'>{$type["name"]}</option>";
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label for="type2">Type 2</label>
            <select id="type2" name="type2">
                <option value>-- Please Select --</option>
                <?php
                foreach ( $types as $type ) {
                    echo "<option value='{$type["id"]}'>{$type["name"]}</option>";
                }
                ?>
            </select>
        </div>
        <div class="submit">
            <button type="submit" name="submit">UPLOAD</button>
        </div>
    </form>
</div>

<?php

include "footer.php";

$connection->close();

?>
