<?php
    # Starts sessions
    session_start();

    # Gets the username and password from the login form
    $usr = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $pas = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    
    # Connects to the database
    $config = parse_ini_file(".ht.ini"); # Hidden file with the database information
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);

    if (mysqli_connect_error()) { # Error
        header("Location: ../pages/login.html?success=-1");
        exit;
    } else {
        # Search through the customer database for the username and password
        $sql = "SELECT `username`,`password` FROM `customer` WHERE `username`='" . $usr . "' AND `password`='" . $pas . "'";
        if ($rslt = $conn->query($sql)) {
            if (mysqli_num_rows($rslt) > 0) {
                $_SESSION['username'] = $usr;
                $_SESSION['password'] = $pas;
                $_SESSION['employee'] = FALSE;
                header("Location: ../pages/customer.php"); # Redirect to the customer page if found
                exit;
            } else {
                # Search through the employee database for the username and password
                $sql1 = "SELECT `username`,`password` FROM `employees` WHERE `username`='" . $usr . "' AND `password`='" . $pas . "'";
                if ($rslt1 = $conn->query($sql1)) {
                    if (mysqli_num_rows($rslt1) > 0) {
                        $_SESSION['username'] = $usr;
                        $_SESSION['password'] = $pas;
                        $_SESSION['employee'] = TRUE;
                        header("Location: ../pages/customer.php"); # Redirect to the customer page with employee sessions if found
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