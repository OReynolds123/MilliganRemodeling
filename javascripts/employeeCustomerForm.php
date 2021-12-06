<?php
    # THIS FILE IS FOR CREATING, UPDATING, AND DELETING CUSTOMERS FOR THE EMPLOYEE FORM ON THE CUSTOMERS SECTION

    # If the edit button is pressed
    if (isset($_POST['edit'])) {
        # Get the input information from the form
        $id = filter_var($_POST['editOrderID'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['editOrderName'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['editOrderDescription'], FILTER_SANITIZE_STRING);
        $prog = filter_var($_POST['editOrderProgress'], FILTER_SANITIZE_STRING);
        $cost = filter_var($_POST['editOrderCost'], FILTER_SANITIZE_STRING);
        $paid = filter_var($_POST['editOrderPaid'], FILTER_SANITIZE_STRING);

        # Connect to the database
        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) { # Error
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            # Get the customer order information from the order table
            $employeeCustomerFormSql = "SELECT `name`,`progress`,`description`,`cost`,`isPaid` FROM `orders` WHERE `id`='" . $id . "'";
            $employeeCustomerFormRslt = $conn->query($employeeCustomerFormSql);
            $employeeCustomerFormRow = mysqli_fetch_array($employeeCustomerFormRslt);
            # If any of the inputted information is empty, get it from the table row
            if ($name == "") {
                $name = $employeeCustomerFormRow["name"];
            }
            if ($desc == "") {
                $desc = $employeeCustomerFormRow["description"];
            }
            if ($prog == "") {
                $prog = $employeeCustomerFormRow["progress"];
            }
            if ($cost == "") {
                $cost = $employeeCustomerFormRow["cost"];
            }
            if ($paid == "") {
                $paid = $employeeCustomerFormRow["isPaid"];
            }
            # Update the table row with the new information
            $employeeCustomerFormUpdateSql = "UPDATE `orders` SET `name` = '" . $name . "', `progress` = '" . $prog . "', `description` = '" . $desc . "', `cost` = '" . $cost . "', `isPaid` = '" . $paid . "' WHERE `orders`.`id` = '" . $id . "'";
            if ($conn->query($employeeCustomerFormUpdateSql)) {
                header("Location: ../pages/employees.php?success=2");  # Redirect back with a success message
                exit;
            } else {
                header("Location: ../pages/employees.php?success=0");  # Redirect back with an error message
                exit;
            }
            $conn->close();
        }
    } else if (isset($_POST['delete'])) { # If the delete button is pressed
        # Get the id and order id from the form
        $id = filter_var($_POST['editID'], FILTER_SANITIZE_STRING);
        $id1 = filter_var($_POST['editOrderID'], FILTER_SANITIZE_STRING);

        # Connect to the database
        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) { # Error
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            # Delete the order row from the order table
            $employeeCustomerDeleteOrderSql = "DELETE FROM `orders` WHERE `orders`.`id` = '" . $id1 . "'";
            if ($conn->query($employeeCustomerDeleteOrderSql)) {
                # Delete the customer row from the customer table
                $employeeCustomerDeleteSql = "DELETE FROM `customer` WHERE `customer`.`id` = '" . $id . "'";
                if ($conn->query($employeeCustomerDeleteSql)) {
                    header("Location: ../pages/employees.php?success=3"); # Redirect back with a success message
                    exit;
                } else {
                    header("Location: ../pages/employees.php?success=0"); # Redirect back with an error message
                    exit;
                }
            } else {
                header("Location: ../pages/employees.php?success=0"); # Redirect back with an error message
                exit;
            }
            $conn->close();
        }
    } else if (isset($_POST['add'])) { # If the add button is pressed
        # Get the all of the inputted information from the form
        $oname = filter_var($_POST['editCustomerOrderName'], FILTER_SANITIZE_STRING);
        $prog = filter_var($_POST['editCustomerOrderProgress'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['editCustomerOrderDescription'], FILTER_SANITIZE_STRING);
        $cost = filter_var($_POST['editCustomerOrderCost'], FILTER_SANITIZE_STRING);
        $paid = filter_var($_POST['editCustomerOrderPaid'], FILTER_SANITIZE_STRING);

        $user = filter_var($_POST['editCustomerUsername'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['editCustomerPassword'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['editCustomerName'], FILTER_SANITIZE_STRING);
        $email = "";
        $phone = "";
        $card = "";
        $order = "";

        # Connect to the database
        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) { # Error
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            # Insert a new row into the orders table
            $employeeCustomerAddOrderSql = "INSERT INTO `orders` (`name`, `progress`, `description`, `cost`, `isPaid`) VALUES ('" . $oname . "', '" . $prog . "', '" . $desc . "', '" . $cost . "', '" . $paid . "');";
            if ($conn->query($employeeCustomerAddOrderSql)) {
                $employeeCustomerGetOrderSql = "SELECT `id` FROM `orders` WHERE `id`=(SELECT MAX(id) FROM `orders`);";
                $employeeCustomerGetOrderRslt = $conn->query($employeeCustomerGetOrderSql);
                $order = mysqli_fetch_array($employeeCustomerGetOrderRslt)['id'];
                # Insert a new row into the customer table
                $employeeCustomerAddUserSql = "INSERT INTO `customer` (`username`, `password`, `name`, `email`, `phone`, `card`, `orders`) VALUES ('" . $user . "', '" . $pass . "', '" . $name . "', '" . $email . "', '" . $phone . "', '" . $card . "', '" . $order . "');";
                if ($conn->query($employeeCustomerAddUserSql)) {
                    header("Location: ../pages/employees.php?success=4"); # Redirect back with a success message
                    exit;
                } else {
                    header("Location: ../pages/employees.php?success=0"); # Redirect back with an error message
                    exit;
                }
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