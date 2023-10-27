<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . "/common/header.php";
include_once $root . '/common/functions.php';
include_once $root . '/common/form.php';
include_once $root . "/dao/pokemon.php";
include_once $root . "/dao/type.php";

$fields = array(
    new Field( Field::NUMBER, "id", "ID", null, false, true ),
    new Field( Field::NUMBER, "number", "#", null, false, true ),
    new Field( Field::TEXT, "name", "Name", null, false, true ),
    new Field( Field::LST, "gender_id", "Gender", "gender", false, true ),
    new Field( Field::LST, "form_id", "Form", "form", false, true ),
    new Field( Field::LST, "form_suffix_id", "Form Suffix", "form_suffix" ),
    new Field( Field::LST, "type1", "Type 1", "type", false, true ),
    new Field( Field::LST, "type2", "Type 2", "type" ) );

if ( $loggedIn && isset( $_POST['id'] ) ) {
    create( $fields, $GLOBALS['pokemon_dao'] );
}

if ( $loggedIn && isset( $_GET['delete'] ) && $_GET['delete'] === 'true' ) {
    delete( $GLOBALS['pokemon_dao'] );
}

?>
    <style>
        <?php
        $types = $GLOBALS['type_dao']->findAll( 0, $GLOBALS['type_dao']->countAll() );
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
        $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 10;
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
            $pokemon_count = $GLOBALS['pokemon_dao']->countAll( $weird_form, $missing );
            $pokemon = $GLOBALS['pokemon_dao']->findAll( $offset, $limit, $weird_form, $missing );

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
                <form action='?id=$id' method='post'>
                    <button type='submit' name='submit'>&#xe065;</button>
                </form>
            </td>
            <td style='width: 42px; '>
                <form action='?delete=true&id=$id' method='post'>
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
                    <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $first; ?>')"><<
                        First</a>
                    <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $prev; ?>')"><
                        Previous</a>
                    <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $next; ?>')">Next ></a>
                    <a href="javascript: void(0);" onclick="setSearchParam('offset', '<?php echo $last; ?>')">Last
                        >></a>
                </th>
            </tr>
        </table>
    </div>
<?php

if ( $loggedIn ) {
    $record = null;

    if ( isset( $_GET['id'] ) ) {
        $record = $GLOBALS['pokemon_dao']->findById( $_GET['id'] );
    }

    $form = new Form( $fields, $record );
    $form->echo_me();
}

include $root . "/common/footer.php";
