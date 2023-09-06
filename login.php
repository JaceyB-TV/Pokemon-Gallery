<?php

include_once "header.php";
include_once "secret.php";

if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginSql = "";

    $loginStatement = $connection->prepare($loginSql);
    $loginStatement->bind_param("ss", $username, $password);
    $loginStatement->execute();

    $loginResult = $loginStatement->get_result();

    if ( $loginResult->num_rows !== 1 ) {
        $_SESSION['loggedin'] = false;
        header( "Location: login.php?error=login" );
        die();
    }

    $_SESSION['loggedin'] = true;

    header( "Location: index.php?message=login" );
}

?>

<div class="upload">
    <form action="login.php" method='post'>
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Username"/>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password"/>
        </div>
        <div class="submit">
            <button type="submit" name="submit">UPLOAD</button>
        </div>
    </form>
</div>

<?php

include "footer.php";

$connection->close();

?>
