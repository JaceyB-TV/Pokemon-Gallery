<?php

include_once "../common/header.php";
include_once "../dao/pokemon.php";
include_once "../dao/type.php";
include_once "../secret/secret.php";

if ( isset( $_POST['number'] ) ) {
    $number = $_POST['number'];
    $name = $_POST['name'];
    $gender_id = $_POST['gender'];
    $form_id = $_POST['form'];
    $type1 = $_POST['type1'];
    $type2 = ( $_POST['type2'] === "" || $_POST['type2'] === 0 ) ? null : $_POST['type2'];

    $insertStatement = $connection->prepare( "INSERT INTO pokemon (number, name, gender_id, form_id, type1, type2) VALUES (?, ?, ?, ?, ?, ?)" );
    $insertStatement->bind_param( "isiiii", $number, $name, $gender_id, $form_id, $type1, $type2 );
    $insertStatement->execute();

    if ( $insertStatement->error !== "" ) {
        header( "Location: /pokemon?error=insert" );
        die();
    }

    header( "Location: /pokemon?message=success" );
}

if ( isset ( $_GET['delete'] ) && $_GET['delete'] === "true" ) {
    $id = $_GET['id'];

    $deleteStatement = $connection->prepare( "DELETE FROM pokemon WHERE id = ?" );
    $deleteStatement->bind_param( "i", $id );
    $deleteStatement->execute();

    if ( $deleteStatement->error !== "" ) {
        header( "Location: /pokemon?error=delete" );
        echo "" . $deleteStatement->error;
        die();
    }

    header( "Location: /pokemon?message=delete" );
}

?>

<style>
    <?php
    $types = $type_dao->selectAll();
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
        $pokemon = $pokemon_dao->selectAll();

        if ( count( $pokemon ) === 0 ) {
            echo "<tr><td colspan='7'>No Content</td></tr>";
        }
        else {
            foreach ( $pokemon as $row ) {
                $id = $row['id'];
                $number = $row['number'];
                $name = $row['name'];
                $gender = $row['gender_name'];
                $form = $row['form_name'];
                $type1 = $row['type1'];
                $type2 = $row['type2'];

                echo "
        <tr>
            <td>$number</td>
            <td>$name</td>
            <td>$gender</td>
            <td>$form</td>";

                if ( isset ( $type2 ) ) {
                    echo "
            <td>
                <div class='type $type1'>$type1</div>
            </td>
            <td>
                <div class='type $type2'>$type2</div>
            </td>";
                }
                else {
                    echo "
            <td colspan='2'>
                <div class='type $type1'>$type1</div>
            </td>";
                }

                if ( $loggedIn ) {
                    echo "
            <td style='width: 42px; '>
                <form action='/pokemon?delete=true&id=$id' method='post'>
                    <button type='submit' name='submit'>&times;</button>
                </form>
            </td>";
                }

                echo "
        </tr>";
            }
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
    <form action='pokemon' method='post'>
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

include "../common/footer.php";

$connection->close();

?>
