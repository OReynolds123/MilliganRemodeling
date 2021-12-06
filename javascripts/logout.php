<?php
    # Deletes the current sessions
    session_start();
    $_SESSION["username"] = "";
    $_SESSION["password"] = "";
    session_destroy();
    # Redirects to the login page with a success logout message
    header("Location: ../pages/login.html?success=1");
?>