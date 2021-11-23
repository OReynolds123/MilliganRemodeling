<?php
    session_start();
    if (empty($_SESSION["username"])) {
        header("Location: ../pages/login.html");
        exit;
    }
    if ($_SESSION['employee'] != TRUE) {
        header("Location: ../pages/customer.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Milligan Remodeling</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="Milligan Remodeling" />
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <link rel="stylesheet" href="../stylesheets/stylesheet.css">
    <link rel="stylesheet" href="../stylesheets/employees.css">
</head>

<body>
    <!-- Background -->
    <a class="anchor" id="home"></a>
    <div class="homeBackground"></div>

    <!-- Navigation Bar -->
    <header class="nav">
        <div class="menuOverlay">
            <a href="javascript:void(0)" class="menuOverlay_closebtn" onclick="menuOverlay_close()">&#10006;</a>
            <div class="menuOverlay_optionDiv">
                <a href="../index.html" onclick="menuOverlay_close()">Home</a>
                <a href="info.html" onclick="menuOverlay_close()">What We Do</a>
                <a href="about.html" onclick="menuOverlay_close()">About Us</a>
                <a href="work.html" onclick="menuOverlay_close()">Our Work</a>
                <a href="contact.html" onclick="menuOverlay_close()">Contact Us</a>
                <a href="customer.php" onclick="menuOverlay_close()">Customer</a>
            </div>
        </div>
        <div class="navbar">
            <a href="../index.html" class="logo_div">
                <img class="logo" src="../images/logo_red.png" />
            </a>
            <div class="menuLarge_div">
                <div class="menuLarge_optionDiv">
                    <a href="../index.html#home">Home</a>
                    <a href="info.html">What We Do</a>
                    <a href="about.html">About Us</a>
                    <a href="work.html">Our Work</a>
                    <a href="contact.html">Contact Us</a>
                    <a href="customer.php">Customer</a>
                </div>
            </div>
            <div class="menuSmall_div" onclick="menuOverlay_open()">
                <div class="menu_bar"></div>
                <div class="menu_bar"></div>
                <div class="menu_bar"></div>
            </div>
        </div>
    </header>

    <!-- Home Landing -->
    <div class="homeLanding" style="height:40vh;">
        <div class="homeLandingDiv">
            <div class="landingInfoDiv">
                <div class="landingLogoDiv"><img class="landingLogo" src="../images/logo_white.png"></div>
            </div>
        </div>
    </div>

    <!-- Info Landing -->
    <div class="pageLanding">
        <div class="pageLandingDiv">
            <div class="pageLandingTitleDiv">
                <span class="pageLandingTitle">Employees</span>
            </div>
            
            <!-- ADD CONTENT HERE -->
            <div>Click to <a href="../javascripts/logout.php">logout</a>.</div>
        </div>
    </div>
</body>

<script src="../javascripts/navbar.js"></script>
</html>