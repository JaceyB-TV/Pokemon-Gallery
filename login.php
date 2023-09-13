<?php

include_once "header.php";
include_once "secret.php";

if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginSql = "SELECT username, password, salt FROM user
                 WHERE username = ?";

    $loginStatement = $connection->prepare( $loginSql );
    $loginStatement->bind_param( "s", $username );
    $loginStatement->execute();
    $loginStatement->bind_result( $loginUsername, $loginPassword, $loginSalt );
    $loginStatement->fetch();

    if ( !isset( $loginUsername ) || !isset( $loginSalt ) ) {
        $_SESSION['loggedin'] = false;
        header( "Location: login.php?error=login" );
        die();
    }

    if ( hash_hmac( "md5", $password, $loginSalt ) !== $loginPassword ) {
        $_SESSION['loggedin'] = false;
        header( "Location: login.php?error=login" );
        die();
    }

    $_SESSION['loggedin'] = true;
    header( "Location: index.php?message=login" );
    die();
}

?>

<div class="upload">
    <form action="login.php" method='post'>
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Username"/>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password"/>
        </div>
        <div class="submit">
            <button type="submit" name="submit">LOG IN</button>
        </div>
    </form>
</div>

<?php

include "footer.php";

$connection->close();

?>
