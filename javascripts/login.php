<?php
    session_start();

    $usr = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $pas = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $config = parse_ini_file(".ht.ini");

    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
    if (mysqli_connect_error()) {
        header("Location: ../pages/login.html?success=-1");
        exit;
    } else {
        $sql = "SELECT `username`,`password` FROM `customer` WHERE `username`='" . $usr . "' AND `password`='" . $pas . "'";
        if ($rslt = $conn->query($sql)) {
            if (mysqli_num_rows($rslt) > 0) {
                $_SESSION['username'] = $usr;
                $_SESSION['password'] = $pas;
                $_SESSION['employee'] = FALSE;
                header("Location: ../pages/customer.php");
                exit;
            } else {
                $sql1 = "SELECT `username`,`password` FROM `employees` WHERE `username`='" . $usr . "' AND `password`='" . $pas . "'";
                if ($rslt1 = $conn->query($sql1)) {
                    if (mysqli_num_rows($rslt1) > 0) {
                        $_SESSION['username'] = $usr;
                        $_SESSION['password'] = $pas;
                        $_SESSION['employee'] = TRUE;
                        header("Location: ../pages/customer.php");
                        exit;
                    } else {
                        header("Location: ../pages/login.html?success=0");
                        exit;
                    }
                } else {
                    header("Location: ../pages/login.html?success=-1");
                    exit;
                }
            }
        } else {
            header("Location: ../pages/login.html?success=-1");
            exit;
        }
        $conn->close();
    }
?>