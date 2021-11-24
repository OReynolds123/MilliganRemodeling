<?php
    session_start();
    $name = filter_var($_POST['editName'], FILTER_SANITIZE_STRING);
    $user = filter_var($_POST['editUser'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['editPass'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['editEmail'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['editPhone'], FILTER_SANITIZE_STRING);
    $card = filter_var($_POST['editCard'], FILTER_SANITIZE_STRING);

    if ($name == "") {
        $name = $_SESSION["name"];
    }
    if ($user == "") {
        $user = $_SESSION["username"];
    }
    if ($pass == "") {
        $pass = $_SESSION["password"];
    }
    if ($email == "") {
        $email = $_SESSION["email"];
    }
    if ($phone == "") {
        $phone = $_SESSION["phone"];
    }
    if ($card == "") {
        $card = $_SESSION["card"];
    }


    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) {
        header("Location: ../pages/customer.php?success=0");
        exit;
    } else {
        $sql = "UPDATE `customer` SET `name` = '" . $name . "', `username` = '" . $user . "', `password` = '" . $pass . "', `email` = '" . $email . "', `phone` = '" . $phone . "', `card` = '" . $card . "' WHERE `customer`.`id` = '" . $_SESSION["id"] . "'";
        if ($conn->query($sql)) {
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pass;
            header("Location: ../pages/customer.php?success=1");
            exit;
        } else {
            header("Location: ../pages/customer.php?success=0");
            exit;
        }
        $conn->close();
    }
?>