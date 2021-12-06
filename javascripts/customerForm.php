<?php
    # THIS FILE IS FOR UPDATING THE CUSTOMER INFORMATION FROM THE CUSTOMER FORM

    session_start();
    # Get the customer information from the form
    $name = filter_var($_POST['editCustomerName'], FILTER_SANITIZE_STRING);
    $user = filter_var($_POST['editCustomerUser'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['editCustomerPass'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['editCustomerEmail'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['editCustomerPhone'], FILTER_SANITIZE_STRING);
    $card = filter_var($_POST['editCustomerCard'], FILTER_SANITIZE_STRING);

    # If any of the inputted information is empty, get it from sessions
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

    # Connect to the database
    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) { # Error
        header("Location: ../pages/customer.php?success=0");
        exit;
    } else {
        # Update the customer row in the customer table with the new information
        $sql = "UPDATE `customer` SET `name` = '" . $name . "', `username` = '" . $user . "', `password` = '" . $pass . "', `email` = '" . $email . "', `phone` = '" . $phone . "', `card` = '" . $card . "' WHERE `customer`.`id` = '" . $_SESSION["id"] . "'";
        if ($conn->query($sql)) {
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pass;
            header("Location: ../pages/customer.php?success=1"); # Redirect back with a success message
            exit;
        } else {
            header("Location: ../pages/customer.php?success=0"); # Redirect back with an error message
            exit;
        }
        $conn->close();
    }
?>