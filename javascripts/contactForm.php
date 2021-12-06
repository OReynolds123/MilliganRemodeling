<?php
    # THIS FILE IS FOR CREATING CONTACT MESSAGES FROM THE CONTACT FORM

    # Get the name, email, and message from the contact form
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    # Connect to the database
    $config = parse_ini_file(".ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
    
    if (mysqli_connect_error()) { # Error
        header("Location: ../pages/contact.html?success=0#contact");
        exit;
    } else {
        # Insert the contact message into the contact table
        $sql = "INSERT INTO contact (id, name, email, message) values ('0', '$name', '$email', '$message')";
        if ($conn->query($sql)){
            header("Location: ../pages/contact.html?success=1#contact"); # Redirect back with a success message
            exit;
        } else {
            header("Location: ../pages/contact.html?success=0#contact"); # Redirect back with an error message
            exit;
        }
        $conn->close();
    }
?>