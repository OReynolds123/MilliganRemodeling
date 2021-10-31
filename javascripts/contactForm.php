<?php
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $config = parse_ini_file(".ht.ini");

    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
    if (mysqli_connect_error()) {
        header("Location: ../pages/contact.html?success=0#contact");
    } else {
        $sql = "INSERT INTO contact (id, name, email, message) values ('0', '$name', '$email', '$message')";
        if ($conn->query($sql)){
            header("Location: ../pages/contact.html?success=1#contact");
        } else {
            header("Location: ../pages/contact.html?success=0#contact");
        }
        $conn->close();
    }
?>