<?php

include_once "../common/header.php";
include_once "../dao/pokemon.php";
include_once "../dao/type.php";
include_once "../secret/secret.php";

if ( isset( $_POST['number'] ) ) {
    $id = $_POST['id'];
    $number = $_POST['number'];
    $name = $_POST['name'];
    $gender_id = $_POST['gender_id'];
    $form_id = $_POST['form_id'];
    $form_suffix_id = ( $_POST['form_suffix_id'] === "" ) ? null : $_POST['form_suffix_id'];
    $type1 = $_POST['type1'];
    $type2 = ( $_POST['type2'] === "" || $_POST['type2'] === 0 ) ? null : $_POST['type2'];

    $insertStatement = $connection->prepare( "INSERT INTO pokemon (id, number, name, gender_id, form_id, form_suffix_id, type1, type2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)" );
    $insertStatement->bind_param( "iisiiiii", $id, $number, $name, $gender_id, $form_id, $form_suffix_id, $type1, $type2 );
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
    $types = $type_dao->findAll( 0, $type_dao->countAll() );
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
    <?php
    $offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
    $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 25;
    $weird_form = isset( $_GET['form'] ) && $_GET['form'] === "true";
    $missing = isset( $_GET['missing'] ) && $_GET['missing'] === "true";
    ?>

    <table>
        <tr>
            <?php
            if ( $weird_form || $missing ) {
                echo "<th class='number'>ID</th>";
            }
            ?>

            <th>#</th>
            <th></th>
            <th>Pokémon</th>
            <th class="hide">Gender</th>
            <th class="hide">Suffix</th>
            <th class="hide">Form</th>
            <th class="hide">Suffix</th>
            <th class="hide type" colspan='2'>Type</th>
            <?php
            if ( $loggedIn ) {
                echo "<th class='action' colspan='2'>Actions</th>";
            }
            ?>

        </tr><?php
        $pokemon_count = $pokemon_dao->countAll( $weird_form, $missing );
        $pokemon = $pokemon_dao->findAll( $offset, $limit, $weird_form, $missing );

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

                $national_dex = sprintf( '%04d', $number );
                $gender_suffix = $row['gender_suffix'];
                $form_suffix = $row['form_suffix'];

                echo "
        <tr>";
                if ( $weird_form || $missing ) {
                    echo "
            <td>$id</td>";
                }
                echo "
            <td>$number</td>
            <td>
                <img alt='$name' src='https://pokejungle.net/sprites/shiny/$national_dex$form_suffix$gender_suffix.png'/>
            </td>
            <td>$name</td>
            <td class='hide'>$gender</td>
            <td class='hide'>$gender_suffix</td>
            <td class='hide'>$form</td>
            <td class='hide'>$form_suffix</td>";

                if ( isset ( $type2 ) ) {
                    echo "
            <td class='hide'>
                <div class=' type $type1'>$type1</div>
            </td>
            <td class='hide'>
                <div class='type $type2'>$type2</div>
            </tdclass>";
                }
                else {
                    echo "
            <td class='hide' colspan='2'>
                <div class='type $type1'>$type1</div>
            </td>";
                }

                if ( $loggedIn ) {
                    echo "
            <td style='width: 42px; '>
                <form action='/pokemon/edit.php?id=$id' method='post'>
                    <button type='submit' name='submit'>&#xe065;</button>
                </form>
            </td>
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
    include_once 'form.php';
}

?>

<?php

include "../common/footer.php";

$connection->close();

?>
