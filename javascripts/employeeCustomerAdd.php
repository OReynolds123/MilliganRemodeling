<?php
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

    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) {
        header("Location: ../pages/employees.php?success=0");
        exit;
    } else {
        $employeeCustomerAddOrderSql = "INSERT INTO `orders` (`name`, `progress`, `description`, `cost`, `isPaid`) VALUES ('" . $oname . "', '" . $prog . "', '" . $desc . "', '" . $cost . "', '" . $paid . "');";
        if ($conn->query($employeeCustomerAddOrderSql)) {
            $employeeCustomerGetOrderSql = "SELECT `id` FROM `orders` WHERE `id`=(SELECT MAX(id) FROM `orders`);";
            $employeeCustomerGetOrderRslt = $conn->query($employeeCustomerGetOrderSql);
            $order = mysqli_fetch_array($employeeCustomerGetOrderRslt)['id'];
            $employeeCustomerAddUserSql = "INSERT INTO `customer` (`username`, `password`, `name`, `email`, `phone`, `card`, `orders`) VALUES ('" . $user . "', '" . $pass . "', '" . $name . "', '" . $email . "', '" . $phone . "', '" . $card . "', '" . $order . "');";
            if ($conn->query($employeeCustomerAddUserSql)) {
                header("Location: ../pages/employees.php?success=5");
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