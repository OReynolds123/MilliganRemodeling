<?php
    session_start();

    # Get the name, username, and password from the form
    $name = filter_var($_POST['editEmployeeName'], FILTER_SANITIZE_STRING);
    $user = filter_var($_POST['editEmployeeUser'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['editEmployeePass'], FILTER_SANITIZE_STRING);

    # If any of the form information is empty, get it from the sessions
    if ($name == "") {
        $name = $_SESSION["name"];
    }
    if ($user == "") {
        $user = $_SESSION["username"];
    }
    if ($pass == "") {
        $pass = $_SESSION["password"];
    }
    
    # Connect to the database 
    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) { # Error
        header("Location: ../pages/employees.php?success=0");
        exit;
    } else {
        # Update the employee table
        $sql = "UPDATE `employees` SET `name` = '" . $name . "', `username` = '" . $user . "', `password` = '" . $pass . "' WHERE `employees`.`id` = '" . $_SESSION["id"] . "'";
        if ($conn->query($sql)) {
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pass;
            header("Location: ../pages/employees.php?success=1"); # Redirect back with a success message
            exit;
        } else {
            header("Location: ../pages/employees.php?success=0"); # Redirect back with a fail message
            exit;
        }
        $conn->close();
    }
?>