<?php

include_once "header.php";
include_once "secret.php";

$pokemonSql = "SELECT p.id, p.name, t1.name AS type1, t2.name AS type2, t1.colour AS type1_colour, t2.colour AS type2_colour
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

    header( "Location: pokemon.php?message=success" );
}

if ( isset ( $_GET['delete'] ) ) {
    $id = $_GET['delete'];

    $deleteStatement = $connection->prepare( "DELETE FROM pokemon WHERE id = ?" );
    $deleteStatement->bind_param( "i", $id );
    $deleteStatement->execute();

    header( "Location: pokemon.php?message=delete" );
}

?>

<div class="table">
    <table>
        <tr>
            <th>#</th>
            <th>Pokémon</th>
            <th colspan='2'>Type</th>
            <?php
            if ( $loggedIn ) {
                echo "<th></th>";
            }
            ?>
        </tr>
        <?php
        if ( $pokemonResult->num_rows > 0 ) {
            while ( $row = $pokemonResult->fetch_assoc() ) {
                echo "<tr>
        <td>{$row["id"]}</td>
        <td>{$row["name"]}</td>";

                if ( isset ( $row['type2'] ) ) {
                    echo "<td style='background-color: {$row["type1_color"]}; '>{$row["type1"]}</td>
    <td style='background-color: {$row["type2_color"]}; '>{$row["type2"]}</td>";
                }
                else {
                    echo "<td colspan='2' style='background-color: {$row["type1_color"]}; '>{$row["type1"]}</td>";
                }

                echo "</tr>";
            }
        }
        else {
            echo "<tr><td colspan='4'>No Content</td></tr>";
        }
        ?>
    </table>
</div>
<?php

if ( $loggedIn ) {
    echo "
    <div class='upload'>
        <form action='pokemon.php' method='post'>
            <div class='field'>
                <label for='id'>#</label>
                <input type='number' name='id' id='id' placeholder='#'/>
            </div>
            <div class='field'>
                <label for='name'>Name</label>
                <input type='text' name='name' id='name' placeholder='Name'/>
            </div>
            <div class='field'>
                <label for='type1'>Type 1</label>
                <select name='type1' id='type1'>";
    foreach ( $types as $type ) {
        echo "<option value='{$type["id"]}'>{$type["name"]}</option>";
    }
    echo "</select>
            </div>
            <div class='field'>
                <label for='type2'>Type 2</label>
                <select name='type2' id='type2'>
                    <option value>-- Please Select --</option>";
    foreach ( $types as $type ) {
        echo "<option value='{$type["id"]}'>{$type["name"]}</option>";
    }
    echo "</select>
            </div>
            <div class='submit'>
                <button type='submit' name='submit'>UPLOAD</button>
            </div>
        </form>
    </div>";
}

?>

<?php

include "footer.php";

$connection->close();

?>
