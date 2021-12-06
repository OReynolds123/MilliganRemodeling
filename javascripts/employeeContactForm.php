<?php
    # THIS FILE IS FOR DELETING THE CONTACT MESSAGES FROM THE EMPLOYEE FORM IN THE CONTACT SECTION

    # Get the id from the form
    $id = filter_var($_POST['contactDeleteID'], FILTER_SANITIZE_STRING);

    # Connect to the database
    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) { # Error
        header("Location: ../pages/employees.php?success=0");
        exit;
    } else {
        # Delete the corresponding contact message from the contact table
        $employeeCustomerDeleteSql = "DELETE FROM `contact` WHERE `contact`.`id` = '" . $id ."'";
        if ($conn->query($employeeCustomerDeleteSql)) {
            header("Location: ../pages/employees.php?success=7"); # Redirect back with a success message
            exit;
        } else {
            header("Location: ../pages/employees.php?success=0"); # Redirect back with an error message
            exit;
        }
        $conn->close();
    }
?>