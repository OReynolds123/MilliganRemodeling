<?php
    if (isset($_POST['delete'])) {
        $id = filter_var($_POST['editID'], FILTER_SANITIZE_STRING);

        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) {
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            $employeeEmployeeDeleteSql = "DELETE FROM `employees` WHERE `employees`.`id` = '" . $id . "'";
            if ($conn->query($employeeEmployeeDeleteSql)) {
                header("Location: ../pages/employees.php?success=5");
                exit;
            } else {
                header("Location: ../pages/employees.php?success=0");
                exit;
            }
            $conn->close();
        }
    } else if (isset($_POST['add'])) {
        $user = filter_var($_POST['editEmployeeUsername'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['editEmployeePassword'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['editEmployeeName'], FILTER_SANITIZE_STRING);

        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) {
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            $employeeEmployeeAddUserSql = "INSERT INTO `employees` (`username`, `password`, `name`) VALUES ('" . $user . "', '" . $pass . "', '" . $name . "');";
            if ($conn->query($employeeEmployeeAddUserSql)) {
                header("Location: ../pages/employees.php?success=6");
                exit;
            } else {
                header("Location: ../pages/employees.php?success=0");
                exit;
            }
            $conn->close();
        }
    } else {
        header("Location: ../pages/employees.php");
        exit;
    }
?>