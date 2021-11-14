<?php
    session_start();
    $_SESSION["username"] = "";
    $_SESSION["password"] = "";
    session_destroy();
    header("Location: ../pages/login.html?success=1");
?>