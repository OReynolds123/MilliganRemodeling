<?php
    # Check for sessions
    session_start();
    if (empty($_SESSION["username"])) { # Redirect to login page if no sessions
        header("Location: ../pages/login.html");
        exit;
    }
    if ($_SESSION['employee'] == TRUE) { # Redirect to employee page if employee user
        header("Location: ../pages/employees.php");
        exit;
    }

    # Connect to the database
    $config = parse_ini_file("../javascripts/.ht.ini"); # Parse the secure information from this hidden file
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']); # Connect to the database
    if (mysqli_connect_error()) { # On error, redirect to an error page
        header("Location: error.html?success=0");
        exit;
    } else {
        # Get all of the current customer info from the database
        $sql1 = "SELECT `id`,`name`,`email`,`phone`,`card`,`orders` FROM `customer` WHERE `username`='" . $_SESSION["username"] . "' AND `password`='" . $_SESSION["password"] . "'";
        $rslt1 = $conn->query($sql1);
        # Store the information in session if not empty
        if (mysqli_num_rows($rslt1) < 1) {
            $_SESSION["id"] = "";
            $_SESSION["username"] = "";
            $_SESSION["password"] = "";
            $_SESSION["name"] = "";
            $_SESSION["email"] = "";
            $_SESSION["phone"] = "";
            $_SESSION["card"] = "";
            $_SESSION["orders"] = "";
            session_destroy();
            header("Location: ../pages/login.html");
        } else {
            $row1 = mysqli_fetch_array($rslt1);
            $_SESSION["id"] = $row1['id'];
            $_SESSION["name"] = $row1['name'];
            $_SESSION["email"] = $row1['email'];
            $_SESSION["phone"] = $row1['phone'];
            $_SESSION["card"] = $row1['card'];
            $_SESSION["orders"] = $row1['orders'];
        }

        # Get all of the current order info from the database as it relates to the customer
        $sql2 = "SELECT `name`,`progress`,`description`,`cost`,`isPaid` FROM `orders` WHERE `id`='" . $_SESSION["orders"] . "'";
        $rslt2 = $conn->query($sql2);
        # Store the information in session if not empty
        if (mysqli_num_rows($rslt2) < 1) {
            $_SESSION["orderName"] = "";
            $_SESSION["prog"] = "";
            $_SESSION["desc"] = "";
            $_SESSION["cost"] = "";
            $_SESSION["paid"] = "";
        } else {
            $row2 = mysqli_fetch_array($rslt2);
            $_SESSION["orderName"] = $row2['name'];
            $_SESSION["prog"] = $row2['progress'];
            $_SESSION["desc"] = $row2['description'];
            $_SESSION["cost"] = $row2['cost'];
            $_SESSION["paid"] = $row2['isPaid'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customers</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="Customers" />
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

    <!-- Page Landing -->
    <div class="pageLanding">
        <div class="pageLandingDiv">
            <div style="position:relative;">
                <div class="customerLandingSuccessBanner"></div> <!-- Banner to show response information -->
            </div>

            <div class="pageLandingTitleDiv">
                <span class="pageLandingTitle">Customer</span> <!-- Title -->
            </div>
            
            <div class="customerLandingDiv">
                <div class="customerLandingLeftDiv">
                    <!-- Project -->
                    <div class="customerLandingLeft">
                        <div style="font-size:25px">Project:</div>
                        <div class="customerLandingCustomerParent">
                            <div onmousedown="customerLandingProfilePress(this);" class="customerLandingCustomerTitle"><?php echo $_SESSION['orderName']; ?> <!-- Project expandable button -->
                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                            </div> 
                            <div class="customerLandingCustomerChild"> <!-- Customer order information -->
                                Description: <?php echo $_SESSION["desc"]; ?><br>
                                Progress: <?php echo $_SESSION["prog"]; ?>%<br>
                                Cost: $<?php echo $_SESSION["cost"]; ?><br>
                                Paid: <?php echo $_SESSION["paid"]; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Profile -->
                    <div class="customerLandingLeft hide">
                        <div style="font-size:25px">Profile:</div>
                        <div class="customerLandingCustomerParent">
                            <div onmousedown="customerLandingProfilePress(this);" class="customerLandingCustomerTitle"><?php echo $_SESSION['name']; ?> <!-- Profile expandable button -->
                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                            </div>
                            <form class="customerLandingCustomerChild" method="POST" action="../javascripts/customerForm.php"> <!-- Customer profile information (allows customer to read and update their information) -->
                                Full Name: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerName" placeholder="<?php echo $_SESSION["name"]; ?>"><br>
                                Username: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerUser" placeholder="<?php echo $_SESSION["username"]; ?>"><br>
                                Password: <input class="customerLandingCustomerChildInput" type="password" name="editCustomerPass" placeholder="<?php echo $_SESSION["password"];?>"><br>
                                Email: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerEmail" placeholder="<?php echo $_SESSION["email"];?>"><br>
                                Phone: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerPhone" placeholder="<?php echo $_SESSION["phone"];?>"><br>
                                Card: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerCard" placeholder="<?php echo $_SESSION["card"];?>"><br>
                                <button type="submit" name="add" class="customerLandingCustomerChildSubmitBtn green">
                                    <span>Update Profile</span>
                                    <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="customerLandingRightDiv"> <!-- Customer navigation bar -->
                    <a onmousedown="customerBtnPress(0);" class="customerLandingRightBtn active">
                        <svg viewBox="0 0 576 512"><path d="M573.65 200.92l-11.34-11.31c-3.13-3.12-8.21-3.12-11.34 0l-17.01 16.97-52.98-52.86c5.64-21.31.36-44.9-16.39-61.61l-45.36-45.25C387.92 15.62 346.88 0 305.84 0c-41.04 0-82.09 15.62-113.4 46.86l68.66 68.5c-4.42 16.71-1.88 34.7 7.4 49.8L21.47 395.26c-27.95 26.04-28.71 70.01-1.67 96.99C33.02 505.44 50.32 512 67.59 512c18.05 0 36.09-7.17 49.42-21.42l233.13-249.14c12.84 5.06 26.84 6.3 40.12 2.8l52.98 52.86-17.01 16.97a7.985 7.985 0 0 0 0 11.31l11.34 11.31c3.13 3.12 8.21 3.12 11.34 0l124.74-124.45a7.997 7.997 0 0 0 0-11.32zM93.57 468.74C86.78 476 77.55 480 67.59 480c-9.48 0-18.4-3.69-25.11-10.38-6.87-6.86-10.57-15.97-10.4-25.67.17-9.7 4.17-18.68 11.28-25.3l246.37-229.47 33.85 33.77L93.57 468.74zm372.35-194.28l-52.98-52.85-13.04-13.01-17.83 4.7c-11.3 2.98-22.84-.03-30.87-8.04l-51.03-50.91c-8.03-8.01-11.04-19.53-8.06-30.8l4.71-17.79-13.04-13.01-43.14-43.05c19.54-11.54 41.9-17.7 65.2-17.7 34.27 0 66.48 13.32 90.72 37.49l45.36 45.26c8.03 8.01 11.04 19.53 8.06 30.8l-4.71 17.79 13.04 13.01 52.98 52.86-45.37 45.25z"></path></svg>
                        <div>Project</div>
                    </a>
                    <a onmousedown="customerBtnPress(1);" class="customerLandingRightBtn">
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
    init();
    function init() {
        // If the url has a ? in it, the user was redirected here with some sort of message
        if (window.location.href.includes("?")) {
            var checkurl = window.location.href.split("?")[1].split("#")[0];
            if (checkurl == "success=1") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! User Profile Updated.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=0") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.backgroundColor = "#bb000080";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Error! Please Try Again.";
            }
        }
    }
    // These functions control the html customer button elements
    function customerLandingProfilePress(elem) {
        // Shows the hidden information
        elem.parentElement.classList.toggle("selected");
    }
    function customerBtnPress(num) {
        temps1 = document.getElementsByClassName("customerLandingRightBtn");
        temps2 = document.getElementsByClassName("customerLandingLeft");
        temps3 = document.getElementsByClassName("customerLandingCustomerParent");
        for (var i = 0; i < temps1.length; i++) {
            temps1[i].classList.remove("active");
        }
        for (var i = 0; i < temps2.length; i++) {
            temps2[i].classList.add("hide");
        }
        for (var i = 0; i < temps3.length; i++) {
            temps3[i].classList.remove("selected");
        }
        temps1[num].classList.add("active");
        temps2[num].classList.remove("hide");
    }
</script>

</html>