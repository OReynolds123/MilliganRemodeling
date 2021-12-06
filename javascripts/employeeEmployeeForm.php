<?php
    # THIS FILE IS FOR THE EMPLOYEE FORM ON THE EMPLOYEES NAVBAR SECTION

    # If the delete button is pressed
    if (isset($_POST['delete'])) {
        # Get the id from the form
        $id = filter_var($_POST['editID'], FILTER_SANITIZE_STRING);

        # Connect to the database
        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) { # Error
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            # Delete the employee from the employee table
            $employeeEmployeeDeleteSql = "DELETE FROM `employees` WHERE `employees`.`id` = '" . $id . "'";
            if ($conn->query($employeeEmployeeDeleteSql)) {
                header("Location: ../pages/employees.php?success=5"); # Redirect back with a success message
                exit;
            } else {
                header("Location: ../pages/employees.php?success=0"); # Redirect back with an error message
                exit;
            }
            $conn->close();
        }
    } else if (isset($_POST['add'])) { # If the add button is pressed
        # Get the username, password, and name from the form
        $user = filter_var($_POST['editEmployeeUsername'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['editEmployeePassword'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['editEmployeeName'], FILTER_SANITIZE_STRING);

        # Connect to the database
        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) { # Error
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            # Insert a new row into the employee table
            $employeeEmployeeAddUserSql = "INSERT INTO `employees` (`username`, `password`, `name`) VALUES ('" . $user . "', '" . $pass . "', '" . $name . "');";
            if ($conn->query($employeeEmployeeAddUserSql)) {
                header("Location: ../pages/employees.php?success=6"); # Redirect back with a success message
                exit;
            } else {
                header("Location: ../pages/employees.php?success=0"); # Redirect back with an error message
                exit;
            }
            $conn->close();
        }
    } else { # Else
        header("Location: ../pages/employees.php"); # Redirect back
        exit;
    }
?>