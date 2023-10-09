<?php

include_once "../common/header.php";
include_once "../secret/secret.php";
include_once "../dao/shiny.php";

$showAll = isset( $_GET["showAll"] ) && $_GET["showAll"] === "true";

$shinies = $shiny_dao->findAll( $showAll );
$shinyCount = count( $shinies );
$shinyPercentage = sprintf( "%.2f%%", $shinyCount / 1010 );

if ( isset( $_POST['pokemon'] ) ) {
    $pokemonId = $_POST['pokemon'];
    $gameId = $_POST['game_id'];
    $date = $_POST['date'];

    if ( !$pokemonId || !$gameId || !$date ) {
        header( "Location: /shiny?error=fields" );
        die();
    }

    if ( !$insertStatement = $connection->prepare( "INSERT INTO shiny (pokemon_id, game_id, caught_date) VALUES (?, ?, ?)" ) ) {
        header( "Location: /shiny?error=prepare" );
        die();
    }

    $insertStatement->bind_param( "sss", $pokemonId, $gameId, $date );
    $insertStatement->execute();

    if ( $insertStatement->error !== "" ) {
        header( "Location: /shiny?error=insert" );
        die();
    }

    header( "Location: /shiny?message=success" );
}

if ( isset ( $_GET['delete'] ) && $_GET['delete'] === "true" ) {
    $id = $_GET['id'];

    $deleteStatement = $connection->prepare( "DELETE FROM shiny WHERE id = ?" );
    $deleteStatement->bind_param( "i", $id );
    $deleteStatement->execute();

    if ( $deleteStatement->error !== "" ) {
        header( "Location: /shiny?error=delete" );
        echo "" . $deleteStatement->error;
        die();
    }

    header( "Location: /shiny?message=delete" );
}
?>
<p class="info">
    My name is Jacey and welcome to my Shiny page! I am currently trying to complete a shiny living dex. You can
    join in with the journey while I'm streaming over at <u><a href="https://twitch.tv/JaceyB">Twitch</a></u>.
    Feel free to come say hi.
</p>
<div class="progress">
    <div style="width:<?php echo $shinyPercentage ?>; ">
        <p>
            <?php echo $shinyCount ?> / 1010 (<?php echo $shinyPercentage ?>)
        </p>
    </div>
</div>
<div class="shiny"><?php
    foreach ( $shinies as $shiny ) {
        $exists = isset( $shiny['id'] );

        $pokemonNumber = sprintf( '%03d', $shiny['pokemon_number'] );

        $date = null;
        $class = "item gray";

        if ( $exists ) {
            $date = $shiny["caught_date"];
            $class = "item";
        }

        if ( isset( $shiny['gender_suffix'] ) ) {
            $pokemonNumber .= $shiny['gender_suffix'];
        }

        if ( isset( $shiny['form_suffix'] ) ) {
            $pokemonNumber .= $shiny['form_suffix'];
        }

        echo "
        <a class='$class' href='javascript:void(0);'>
            <div style='background-image: url(https://www.serebii.net/Shiny/SV/new/$pokemonNumber.png); '></div>
            <p>$date</p>
        </a>";
    }
    ?>

</div><?php

if ( $loggedIn ) {
    include 'form.php';

    echo "
    <div class='table'>
        <table>
            <tr>
                <th>ID</th>
                <th>Pokemon</th>
                <th>Gender</th>
                <th>Form</th>
                <th colspan='2'>Actions</th>
            </tr>";

    foreach ( $shinies as $shiny ) {
        $id = $shiny['id'];
        $pokemon = $shiny["pokemon_name"];
        $gender = $shiny["gender_name"];
        $form = $shiny["form_name"];

        echo "
            <tr>
                <td>$id</td>
                <td>$pokemon</td>
                <td>$gender</td>
                <td>$form</td>";
//                <td style='width: 42px;'>
//                    <form action='/shiny/edit.php?id=$id' method='post'>
//                        <button type='submit' name='submit'>&#xe065;</button>
//                    </form>
//                </td>
        echo "
                <td style='width: 42px;'>
                    <form action='/shiny?delete=true&id=$id' method='post'>
                        <button type='submit' name='submit'>&times;</button>
                    </form>
                </td>
            </tr>";
    }

    echo "
        </table>
    </div>";
}

include_once "../common/footer.php";

$connection->close();

?>
