<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex by Jacey</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="/js/script.js<?php echo "?date=" . time() ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="/css/style.css<?php echo "?date=" . time() ?>"></link>
</head>
<?php

session_start();

if ( isset( $_GET['logout'] ) && $_GET['logout'] ) {
    $_SESSION["loggedin"] = false;
}

$loggedIn = isset( $_SESSION["loggedin"] ) && $_SESSION["loggedin"] === true;

class Page
{
    public $link;
    public $self;
    public $caption;
    public $requires_login;
    public $requires_logout;

    public function __construct( $link, $self, $caption, $requires_login = false, $requires_logout = false )
    {
        $this->link = $link;
        $this->self = $self;
        $this->caption = $caption;
        $this->requires_login = $requires_login;
        $this->requires_logout = $requires_logout;
    }
}

$pages = array(
    new Page( "/", "/index.php", "Gallery" ),
    new Page( "/shiny", "/shiny/index.php", "Shinies" ),
    new Page( "/pokemon", "/pokemon/index.php", "Pokémon", true ),
    new Page( "/pokemon?form=true", null, "Form", true ),
    new Page( "/pokemon?missing=true", null, "Missing", true ),

    new Page( "/admin/type", "/admin/type/index.php", "Type", true ),
    new Page( "/admin/gender", "/admin/gender/index.php", "Gender", true ),
    new Page( "/admin/formType", "/admin/formType/index.php", "Form Type", true ),
    new Page( "/admin/form", "/admin/form/index.php", "Form", true ),
    new Page( "/admin/formSuffix", "/admin/formSuffix/index.php", "Form Suffix", true ),
    new Page( "/main/login.php", "/main/login.php", "Login", false, true ),
    new Page( "?logout=true", null, "Logout", true )
);

?>
<body>
<header>
    <h1>Pokédex</h1>
    <h2>by Jacey</h2>
    <div class="menu">
        <div id="links"><?php
            foreach ( $pages as $page ) {
                if ( ( $page->requires_login && !$loggedIn ) || ( $page->requires_logout && $loggedIn ) ) {
                    continue;
                }

                $active = ( $page->self == $_SERVER['PHP_SELF'] ) ? " class='active'" : "";

                echo "
            <a href='$page->link'$active>$page->caption</a>";
            }
            ?>

        </div>
        <a href="javascript: void(0);" class="icon" onclick="toggleMenu()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
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
    echo "<div class='login'><a href='/main/login.php'>&nbsp;&nbsp;</a></div>";
}
?>

<div class="content">
