<?php
    $usr = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $pas = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $config = parse_ini_file(".ht.ini");

    if (($usr == $config['adminUsr']) && ($pas == $config['adminPas'])) {
        header("Location: ../pages/customer.html?tk=1234");
    } else {
        header("Location: ../pages/login.html?success=0");
    }
?>