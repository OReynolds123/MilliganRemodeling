<?php
    $id = filter_var($_POST['customerDeleteID'], FILTER_SANITIZE_STRING);
    $idOrder = filter_var($_POST['customerDeleteOrderID'], FILTER_SANITIZE_STRING);

    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) {
        header("Location: ../pages/employees.php?success=0");
        exit;
    } else {
        $employeeCustomerDeleteOrderSql = "DELETE FROM `orders` WHERE `orders`.`id` = '" . $idOrder . "'";
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
?>