<?php
    session_start();
    if (empty($_SESSION["username"])) {
        header("Location: ../pages/login.html");
        exit;
    }
    if ($_SESSION['employee'] == TRUE) {
        header("Location: ../pages/employees.php");
        exit;
    }

    $config = parse_ini_file("../javascripts/.ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
    if (mysqli_connect_error()) {
        header("Location: ./error.html?success=-1");
        exit;
    } else {
        $sql1 = "SELECT `orders` FROM `customer` WHERE `username`='" . $_SESSION["username"] . "' AND `password`='" . $_SESSION["password"] . "'";
        if ($rslt1 = $conn->query($sql1)) {
            $order = $rslt1->fetch_array()[0] ?? -1;
            if ($order == -1) {
                header("Location: ./error.html?success=-1");
                exit;
            } else {
                $sql2 = "SELECT `name`,`progress`,`description`,`cost`,`isPaid` FROM `orders` WHERE `id`='" . $order . "'";
                $rslt2 = $conn->query($sql2);
                $row = mysqli_fetch_array($rslt2);
                $_SESSION["name"] = $row['name'];
                $_SESSION["prog"] = $row['progress'];
                $_SESSION["desc"] = $row['description'];
                $_SESSION["cost"] = $row['cost'];
                $_SESSION["paid"] = $row['isPaid'];
            }
        } else {
            header("Location: ./error.html?success=-1");
            exit;
        }
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
    <link rel="stylesheet" href="../stylesheets/customer.css">
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
                <span class="pageLandingTitle">Customer</span>
            </div>
            
            <div class="customerLandingDiv">
                <div class="customerLandingLeftDiv">
                    <div class="customerLandingLeft">
                        <div style="font-size:25px">Project Title: <?php echo $_SESSION["name"]; ?></div>
                        <div style="font-size:20px">Description: <?php echo $_SESSION["desc"]; ?></div>
                        <div style="font-size:22px">Progress: <?php echo $_SESSION["prog"]; ?>%</div>
                        <div style="font-size:22px">Cost: $<?php echo $_SESSION["cost"]; ?></div>
                    </div>

                    <div class="customerLandingLeft hide">
                        <div class="">Username</div>
                        <div class="">Password</div>
                        <div class="">Email</div>
                        <div class="">Phone</div>
                        <div class="">Card</div>
                    </div>
                </div>
                <div class="customerLandingRightDiv">
                    <a onmousedown="customerBtnPress(0)" class="customerLandingRightBtn active">
                        <svg viewBox="0 0 576 512"><path d="M573.65 200.92l-11.34-11.31c-3.13-3.12-8.21-3.12-11.34 0l-17.01 16.97-52.98-52.86c5.64-21.31.36-44.9-16.39-61.61l-45.36-45.25C387.92 15.62 346.88 0 305.84 0c-41.04 0-82.09 15.62-113.4 46.86l68.66 68.5c-4.42 16.71-1.88 34.7 7.4 49.8L21.47 395.26c-27.95 26.04-28.71 70.01-1.67 96.99C33.02 505.44 50.32 512 67.59 512c18.05 0 36.09-7.17 49.42-21.42l233.13-249.14c12.84 5.06 26.84 6.3 40.12 2.8l52.98 52.86-17.01 16.97a7.985 7.985 0 0 0 0 11.31l11.34 11.31c3.13 3.12 8.21 3.12 11.34 0l124.74-124.45a7.997 7.997 0 0 0 0-11.32zM93.57 468.74C86.78 476 77.55 480 67.59 480c-9.48 0-18.4-3.69-25.11-10.38-6.87-6.86-10.57-15.97-10.4-25.67.17-9.7 4.17-18.68 11.28-25.3l246.37-229.47 33.85 33.77L93.57 468.74zm372.35-194.28l-52.98-52.85-13.04-13.01-17.83 4.7c-11.3 2.98-22.84-.03-30.87-8.04l-51.03-50.91c-8.03-8.01-11.04-19.53-8.06-30.8l4.71-17.79-13.04-13.01-43.14-43.05c19.54-11.54 41.9-17.7 65.2-17.7 34.27 0 66.48 13.32 90.72 37.49l45.36 45.26c8.03 8.01 11.04 19.53 8.06 30.8l-4.71 17.79 13.04 13.01 52.98 52.86-45.37 45.25z"></path></svg>
                        <div>Project</div>
                    </a>
                    <a onmousedown="customerBtnPress(1)" class="customerLandingRightBtn">
                        <svg viewBox="0 0 448 512"><path d="M313.6 288c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4zM416 464c0 8.8-7.2 16-16 16H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 56.5 0 102.4 45.9 102.4 102.4V464zM224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96z"></path></svg>
                        <div>Profile</div>
                    </a>
                    <a href="../javascripts/logout.php" class="customerLandingRightBtn">
                        <svg viewBox="0 0 512 512"><path d="M96 64h84c6.6 0 12 5.4 12 12v24c0 6.6-5.4 12-12 12H96c-26.5 0-48 21.5-48 48v192c0 26.5 21.5 48 48 48h84c6.6 0 12 5.4 12 12v24c0 6.6-5.4 12-12 12H96c-53 0-96-43-96-96V160c0-53 43-96 96-96zm231.1 19.5l-19.6 19.6c-4.8 4.8-4.7 12.5.2 17.1L420.8 230H172c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h248.8L307.7 391.7c-4.8 4.7-4.9 12.4-.2 17.1l19.6 19.6c4.7 4.7 12.3 4.7 17 0l164.4-164c4.7-4.7 4.7-12.3 0-17l-164.4-164c-4.7-4.6-12.3-4.6-17 .1z"></path></svg>
                        <div>Logout</div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>

<script src="../javascripts/navbar.js"></script>
<script>
    function customerBtnPress(num) {
        temps1 = document.getElementsByClassName("customerLandingRightBtn");
        temps2 = document.getElementsByClassName("customerLandingLeft");
        for (var i = 0; i < temps1.length; i++) {
            temps1[i].classList.remove("active");
        }
        for (var i = 0; i < temps2.length; i++) {
            temps2[i].classList.add("hide");
        }
        temps1[num].classList.add("active");
        temps2[num].classList.remove("hide");
    }
</script>

</html>