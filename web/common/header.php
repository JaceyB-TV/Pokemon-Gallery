<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex by Jacey</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="script.js<?php echo "?date=" . time() ?>"></script>
    <link type="text/css" rel="stylesheet" href="../css/style.css<?php echo "?date=" . time() ?>"></link>
</head>
<?php

session_start();

if ( isset( $_GET['logout'] ) && $_GET['logout'] ) {
    $_SESSION["loggedin"] = false;
}

$loggedIn = isset( $_SESSION["loggedin"] ) && $_SESSION["loggedin"] === true;

$page = 0;

switch ( $_SERVER['PHP_SELF'] ) {
    case "/index.php":
        $page = 1;
        break;
    case "/shiny/index.php":
        $page = 2;
        break;
    case "/pokemon/index.php":
        $page = 3;
        break;
}

?>
<body>
<header>
    <h1>Pokédex</h1>
    <h2>by Jacey</h2>
    <nav>
        <a href="/"<?php echo $page === 1 ? " class='current'" : "" ?>>Gallery</a>
        <a href="/shiny"<?php echo $page === 2 ? " class='current'" : "" ?>>Shinies</a>
        <?php
        if ( $loggedIn || $page === 3 ) {
            $link = "<a href='/pokemon'";
            
            if ( $page === 3 ) {
                $link .= " class='current'";
            }

            $link .= ">Pokémon</a>";

            echo $link;
        }

        if ( $loggedIn ) {
            echo "
        <a href='" . $_SERVER['PHP_SELF'] . "?logout=true'>Logout</a>";
        }
        ?>

    </nav>
</header>

<?php

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
        case "fields":
            $error = "Missing fields, please try again.";
            break;
        case "prepare":
            $error = "Error preparing database, please try again.";
            break;
        case "insert":
            $error = "Error inserting into database, please try again.";
            break;
        case "delete":
            $error = "Error deleting from database, please try again.";
            break;

        default:
            $error = "Error";
            break;
    }

    echo "<div class='error'>
    <div class='red'>&#8416;</div>
    <div class='text'>$error</div>
</div>";
}

if ( !$loggedIn ) {
    echo "<div class='login'><a href='/login.php'>&nbsp;&nbsp;</a></div>";
}
?>

<div class="content">
