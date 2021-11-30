<?php
    if (isset($_POST['edit'])) {
        $id = filter_var($_POST['editOrderID'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['editOrderName'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['editOrderDescription'], FILTER_SANITIZE_STRING);
        $prog = filter_var($_POST['editOrderProgress'], FILTER_SANITIZE_STRING);
        $cost = filter_var($_POST['editOrderCost'], FILTER_SANITIZE_STRING);
        $paid = filter_var($_POST['editOrderPaid'], FILTER_SANITIZE_STRING);

        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) {
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            $employeeCustomerFormSql = "SELECT `name`,`progress`,`description`,`cost`,`isPaid` FROM `orders` WHERE `id`='" . $id . "'";
            $employeeCustomerFormRslt = $conn->query($employeeCustomerFormSql);
            $employeeCustomerFormRow = mysqli_fetch_array($employeeCustomerFormRslt);
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

            $employeeCustomerFormUpdateSql = "UPDATE `orders` SET `name` = '" . $name . "', `progress` = '" . $prog . "', `description` = '" . $desc . "', `cost` = '" . $cost . "', `isPaid` = '" . $paid . "' WHERE `orders`.`id` = '" . $id . "'";
            if ($conn->query($employeeCustomerFormUpdateSql)) {
                header("Location: ../pages/employees.php?success=2");
                exit;
            } else {
                header("Location: ../pages/employees.php?success=0");
                exit;
            }
            $conn->close();
        }
    } else if (isset($_POST['delete'])) {
        $id = filter_var($_POST['editID'], FILTER_SANITIZE_STRING);
        $id1 = filter_var($_POST['editOrderID'], FILTER_SANITIZE_STRING);

        $config = parse_ini_file(".ht.ini");
        $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

        if (mysqli_connect_error()) {
            header("Location: ../pages/employees.php?success=0");
            exit;
        } else {
            $employeeCustomerDeleteOrderSql = "DELETE FROM `orders` WHERE `id` = '" . $id1 . "'";
            if ($conn->query($employeeCustomerDeleteOrderSql)) {
                $employeeCustomerDeleteSql = "DELETE FROM `customer` WHERE `customer`.`id` = '" . $id . "'";
                if ($conn->query($employeeCustomerDeleteSql)) {
                    header("Location: ../pages/employees.php?success=3");
                    exit;
                } else {
                    header("Location: ../pages/employees.php?success=0");
                    exit;
                }
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