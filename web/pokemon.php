<?php

include_once "header.php";
include_once "secret.php";

$pokemonSql = "SELECT
                   p.id
                   , p.number
                   , p.name
                   , g.id AS gender_id
                   , g.name AS gender_name
                   , f.id AS form_id
                   , f.name AS form_name
                   , t1.name AS type1
                   , t2.name AS type2
		       FROM pokemon AS p
               JOIN gender AS g ON g.id = p.gender_id
		       JOIN form AS f ON f.id = p.form_id
		       JOIN type AS t1 ON t1.id = p.type1
		       LEFT JOIN type AS t2 ON t2.id = p.type2
		       ORDER BY p.number";
$pokemonResult = $connection->query( $pokemonSql );

$typeSql = "SELECT id, name, colour, border FROM type ORDER BY id";
$typesResult = $connection->query( $typeSql );
$types = array();
if ( $typesResult->num_rows > 0 ) {
    while ( $t = $typesResult->fetch_assoc() ) {
        $types[] = $t;
    }
}

if ( isset( $_POST[ 'number' ] ) ) {
    $number = $_POST[ 'number' ];
    $name = $_POST[ 'name' ];
    $gender_id = $_POST[ 'gender' ];
    $form_id = $_POST[ 'form' ];
    $type1 = $_POST[ 'type1' ];
    $type2 = $_POST[ 'type2' ] === "" || $_POST[ 'type2' ] === 0 ? null : $_POST[ 'type2' ];

    $insertStatement = $connection->prepare( "INSERT INTO pokemon (number, name, gender_id, form_id, type1, type2) VALUES (?, ?, ?, ?, ?, ?)" );
    $insertStatement->bind_param( "isiiii", $number, $name, $gender_id, $form_id, $type1, $type2 );
    $insertStatement->execute();

    if ( $insertStatement->error !== "" ) {
        header( "Location: pokemon.php?error=insert" );
        die();
    }

    header( "Location: pokemon.php?message=success" );
}

if ( isset ( $_GET[ 'delete' ] ) && $_GET[ 'delete' ] === "true" ) {
    $id = $_GET[ 'id' ];

    $deleteStatement = $connection->prepare( "DELETE FROM pokemon WHERE id = ?" );
    $deleteStatement->bind_param( "i", $id );
    $deleteStatement->execute();

    if ( $deleteStatement->error !== "" ) {
        header( "Location: pokemon.php?error=delete" );
        echo "" . $deleteStatement->error;
        die();
    }

    header( "Location: pokemon.php?message=delete" );
}

?>

<style>
    <?php
    foreach ( $types as $type ) {
        echo "
        .table div.{$type["name"]} {
            background-color: {$type["colour"]};
            border-color: {$type["border"]};
        }";
    }
    ?>
</style>

<div class="table">
    <table>
        <tr>
            <th class='number'>#</th>
            <th>Pokémon</th>
            <th>Gender</th>
            <th>Form</th>
            <th class="type" colspan='2'>Type</th>
            <?php
            if ( $loggedIn ) {
                echo "<th class='action'>Actions</th>";
            }
            ?>

        </tr><?php
        if ( $pokemonResult->num_rows > 0 ) {
            while ( $row = $pokemonResult->fetch_assoc() ) {
                echo "
        <tr>
            <td>{$row["number"]}</td>
            <td>{$row["name"]}</td>
            <td>{$row["gender_name"]}</td>
            <td>{$row["form_name"]}</td>";

                if ( isset ( $row[ 'type2' ] ) ) {
                    echo "
            <td>
                <div class='type {$row["type1"]}'>{$row["type1"]}</div>
            </td>
            <td>
                <div class='type {$row["type2"]}'>{$row["type2"]}</div>
            </td>";
                }
                else {
                    echo "
            <td colspan='2'>
                <div class='type {$row["type1"]}'>{$row["type1"]}</div>
            </td>";
                }

                if ( $loggedIn ) {
                    echo "
            <td>
                <form action='pokemon.php?delete=true&id={$row["id"]}' method='post'>
                    <input type='submit' name='submit' value='&times;'>
                </form>
            </td>";
                }

                echo "
        </tr>";
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
    $genderSql = "SELECT id, name FROM gender ORDER BY id";
    $genderResult = $connection->query( $genderSql );
    $genders = array();
    if ( $genderResult->num_rows > 0 ) {
        while ( $g = $genderResult->fetch_assoc() ) {
            $genders[] = $g;
        }
    }

    $formSql = "SELECT id, name FROM form ORDER BY id";
    $formResult = $connection->query( $formSql );
    $forms = array();
    if ( $formResult->num_rows > 0 ) {
        while ( $f = $formResult->fetch_assoc() ) {
            $forms[] = $f;
        }
    }

    echo "
<div class='upload'>
    <form action='pokemon.php' method='post'>
        <div class='field'>
            <label for='number'>#</label>
            <input type='number' name='number' id='number' placeholder='#'/>
        </div>
        <div class='field'>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' placeholder='Name'/>
        </div>
        <div class='field'>
            <label for='gender'>Gender</label>
            <select name='gender' id='gender'>";

    foreach ( $genders as $gender ) {
        echo "
                <option value='{$gender["id"]}'>{$gender["name"]}</option>";
    }

    echo "
            </select>
        </div>
        <div class='field'>
            <label for='form'>Form</label>
            <select name='form' id='form'>";

    foreach ( $forms as $form ) {
        echo "
                <option value='{$form["id"]}'>{$form["name"]}</option>";
    }

    echo "
             </select>
        </div>
        <div class='field'>
            <label for='type1'>Type 1</label>
            <select name='type1' id='type1'>";

    foreach ( $types as $type ) {
        echo "
                <option value='{$type["id"]}'>{$type["name"]}</option>";
    }

    echo "
            </select>
        </div>
        <div class='field'>
            <label for='type2'>Type 2</label>
            <select name='type2' id='type2'>
                <option value>-- Please Select --</option>";

    foreach ( $types as $type ) {
        echo "
                <option value='{$type["id"]}'>{$type["name"]}</option>";
    }

    echo "
            </select>
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
