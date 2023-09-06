<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex by Jacey</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link type="text/css" rel="stylesheet" href="style.css<?php echo "?date=" . time() ?>"></link>
</head>
<body>
    <header>
        <h1>Pokédex</h1>
        <h2>by Jacey</h2>
        <nav>
            <a href="/">Gallery</a>
            <a href="/pokemon.php">Pokémon</a>
        </nav>
    </header>

<?php

session_start();

$loggedIn = isset( $_SESSION["loggedin"] ) && $_SESSION["loggedin"] === true;

if ( isset( $_GET['message'] ) ) {
    switch ( $_GET['message'] ) {
        case "success":
            $msg = "Successfully added record to database.";
            break;
        case "delete":
            $msg = "Successfully deleted record from database.";
            break;
        case "login":
            $msg = "Welcome, successfully logged in.";
            break;

        default:
            $msg = "Success";
            break;
    }

    echo "<div class='message'>
    <div class='green'>&check;</div>
    <div class='text'>$msg</div>
</div>";
}
else if ( isset( $_GET['error'] ) ) {
    switch ( $_GET['error'] ) {
        case "login":
            $error = "Something went wrong, please try again.";
            break;

        default:
            $error = "Error";
            break;
    }

    echo "<div class='message'>
    <div class='red'>&check;</div>
    <div class='text'>$error</div>
</div>";
}
?>

    <div class="content">