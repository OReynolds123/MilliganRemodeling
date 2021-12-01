<?php
    session_start();
    $name = filter_var($_POST['editEmployeeName'], FILTER_SANITIZE_STRING);
    $user = filter_var($_POST['editEmployeeUser'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['editEmployeePass'], FILTER_SANITIZE_STRING);

    if ($name == "") {
        $name = $_SESSION["name"];
    }
    if ($user == "") {
        $user = $_SESSION["username"];
    }
    if ($pass == "") {
        $pass = $_SESSION["password"];
    }
    
    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) {
        header("Location: ../pages/employees.php?success=0");
        exit;
    } else {
        $sql = "UPDATE `employees` SET `name` = '" . $name . "', `username` = '" . $user . "', `password` = '" . $pass . "' WHERE `employees`.`id` = '" . $_SESSION["id"] . "'";
        if ($conn->query($sql)) {
            $_SESSION["username"] = $user;
            $_SESSION["password"] = $pass;
            header("Location: ../pages/employees.php?success=1");
            exit;
        } else {
            header("Location: ../pages/employees.php?success=0");
            exit;
        }
        $conn->close();
    }
?>