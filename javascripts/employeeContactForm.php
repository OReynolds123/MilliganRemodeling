<?php
    $id = filter_var($_POST['contactDeleteID'], FILTER_SANITIZE_STRING);
    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) {
        header("Location: ../pages/employees.php?success=0");
        exit;
    } else {
        $employeeCustomerDeleteSql = "DELETE FROM `contact` WHERE `contact`.`id` = '" . $id ."'";
        if ($conn->query($employeeCustomerDeleteSql)) {
            header("Location: ../pages/employees.php?success=5");
            exit;
        } else {
            header("Location: ../pages/employees.php?success=0");
            exit;
        }
        $conn->close();
    }
?>