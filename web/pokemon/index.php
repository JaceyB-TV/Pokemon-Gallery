<?php

include_once "../common/header.php";
include_once "../dao/pokemon.php";
include_once "../dao/type.php";
include_once "../secret/secret.php";

if ( isset( $_POST['number'] ) ) {
    $id = $_POST['id'];
    $number = $_POST['number'];
    $name = $_POST['name'];
    $gender_id = $_POST['gender'];
    $form_id = $_POST['form'];
    $type1 = $_POST['type1'];
    $type2 = ( $_POST['type2'] === "" || $_POST['type2'] === 0 ) ? null : $_POST['type2'];

    $insertStatement = $connection->prepare( "INSERT INTO pokemon (id, number, name, gender_id, form_id, type1, type2) VALUES (?, ?, ?, ?, ?, ?, ?)" );
    $insertStatement->bind_param( "iisiiii", $id, $number, $name, $gender_id, $form_id, $type1, $type2 );
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
        die();
    }

    header( "Location: /pokemon?message=delete" );
}

?>

<style>
    <?php
    $types = $type_dao->findAll();
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
        <tr><?php
            $offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
            $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 50;
            $missing = isset( $_GET['missing'] ) && $_GET['missing'] === "true";

            if ( $missing ) {
                echo "
            <th class='number'>ID</th>";
            }
            ?>

            <th class='number'>#</th>
            <th>Pokémon</th>
            <th class="hide">Gender</th>
            <th class="hide">Form</th>
            <th class="type" colspan='2'>Type</th>
            <?php
            if ( $loggedIn ) {
                echo "<th class='action'>Actions</th>";
            }
            ?>

        </tr><?php
        $pokemon_count = $pokemon_dao->countAll( $missing );
        $pokemon = $pokemon_dao->findAll( $offset, $limit, $missing );

        if ( count( $pokemon ) === 0 ) {
            echo "<tr><td colspan='20'>No Content</td></tr>";
        }
        else {
            foreach ( $pokemon as $row ) {
                $id = isset( $row['id'] ) ? $row['id'] : $row['pokemon_id'];
                $number = $row['number'];
                $name = $row['name'];
                $gender = $row['gender_short_name'];
                $form = $row['form_name'];
                $type1 = $row['type1'];
                $type2 = $row['type2'];

                echo "
        <tr>";
                if ( $missing ) {
                    echo "
            <td>$id</td>";
                }
                echo "
            <td>$number</td>
            <td>$name</td>
            <td class='hide'>$gender</td>
            <td class='hide'>$form</td>";

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

        $first = 0;

        $prev = $offset - $limit;
        if ( $prev < 0 ) {
            $prev = 0;
        }

        $next = $offset + $limit;
        if ( $next > $pokemon_count ) {
            $next = floor( $pokemon_count / $limit ) * $limit;
        }

        $last = floor( $pokemon_count / $limit ) * $limit;

        ?>

        <tr>
            <th colspan="20">
                <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $first; ?>')"><< First</a>
                <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $prev; ?>')">< Previous</a>
                <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $next; ?>')">Next ></a>
                <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $last; ?>')">Last >></a>
            </th>
        </tr>
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
    <form action='' method='post'>
        <div class='field'>
            <label for='id'>ID</label>
            <input type='number' name='id' id='id' placeholder='ID'/>
        </div>
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
