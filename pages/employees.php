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

    $config = parse_ini_file("../javascripts/.ht.ini");
    $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
    if (mysqli_connect_error()) {
        header("Location: error.html?success=0");
        exit;
    } else {
        $sql1 = "SELECT `id`,`name` FROM `employees` WHERE `username`='" . $_SESSION["username"] . "' AND `password`='" . $_SESSION["password"] . "'";
        $rslt1 = $conn->query($sql1);
        $row1 = mysqli_fetch_array($rslt1);
        $_SESSION["id"] = $row1['id'];
        $_SESSION["name"] = $row1['name'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employees</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="Employees" />
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
                <a href="employees.php" onclick="menuOverlay_close()">Employees</a>
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
                    <a href="employees.php">Employees</a>
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
                <div class="employeesLandingSuccessBanner"></div>
            </div>

            <div class="pageLandingTitleDiv">
                <span class="pageLandingTitle">Employees</span>
            </div>
            
            <div class="employeesLandingDiv">
                <div class="employeesLandingLeftDiv">

                    <div class="employeesLandingLeft">
                        <div style="font-size:25px">Customers:</div>
                        <?php
                            $config = parse_ini_file("../javascripts/.ht.ini");
                            $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
                            if (mysqli_connect_error()) {
                                header("Location: error.html?success=0");
                                exit;
                            } else {
                                $customerSql = "SELECT `id`,`username`,`name`,`email`,`phone`,`orders` FROM `customer`";
                                $customerRslt = $conn->query($customerSql);
                                for ($i = 0; $i < mysqli_num_rows($customerRslt); $i++) {
                                    $customerRow = mysqli_fetch_array($customerRslt);

                                    $customerOrderSql = "SELECT `name`,`progress`,`description`,`cost`,`isPaid` FROM `orders` WHERE `id`='" . $customerRow['orders'] . "'";
                                    $customerOrderRslt = $conn->query($customerOrderSql);
                                    $customerOrderRow = mysqli_fetch_array($customerOrderRslt);
                                    if (is_null($customerOrderRow)) {
                                        $customerOrderRow['name'] = "";
                                        $customerOrderRow['description'] = "";
                                        $customerOrderRow['progress'] = "";
                                        $customerOrderRow['cost'] = "";
                                        $customerOrderRow['isPaid'] = "";
                                    }
                                    echo '<div class="employeesLandingCustomerParent">';
                                    echo '<div onmousedown="employeesLandingCustomerPress(' . $i. ');" class="employeesLandingCustomerTitle">' . $customerRow['name'] . '
                                            <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                            <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                                        </div>';
                                    echo '<div class="employeesLandingCustomerChild">
                                            Username: ' . $customerRow['username'] . '<br>
                                            Email: ' . $customerRow['email'] . '<br>
                                            Phone: ' . $customerRow['phone'] . '<br>
                                            Order #' . $customerRow['orders'] . ':<br>
                                            - Name: ' . $customerOrderRow['name'] . '<br>
                                            - Description: ' . $customerOrderRow['description'] . '<br>
                                            - Progress: ' . $customerOrderRow['progress'] . '%<br>
                                            - Cost: $' . $customerOrderRow['cost'] . '<br>
                                            - Paid: ' . $customerOrderRow['isPaid'] . 
                                            '<div onmousedown="employeesLandingCustomerEditHide(this)" class="employeesLandingCustomerChildBtn"><span>Edit Order</span>
                                                <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                            </div>
                                            <form method="post" action="../javascripts/employeeCustomerForm.php">
                                                <input type="hidden" id="customerDeleteID" name="customerDeleteID" value="' . $customerRow['id'] . '">
                                                <button type="submit" class="employeesLandingCustomerChildSubmitBtn">
                                                    <span>Delete</span>
                                                    <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                                </button>
                                            </form>
                                        </div>';
                                    echo '<form class="employeesLandingCustomerChild hide" method="POST" action="../javascripts/employeeCustomerForm.php">
                                            Username: ' . $customerRow['username'] . '<br>
                                            Email: ' . $customerRow['email'] . '<br>
                                            Phone: ' . $customerRow['phone'] . '<br>
                                            Order #' . $customerRow['orders'] . ':<br>
                                            <input type="hidden" id="editID" name="editID" value="' . $customerRow['id'] . '">
                                            <input type="hidden" id="editOrderID" name="editOrderID" value="' . $customerRow['orders'] . '">
                                            - Name: <input class="employeesLandingCustomerChildInput" type="text" name="editOrderName" placeholder="' . $customerOrderRow['name'] . '"><br>
                                            - Description: <input class="employeesLandingCustomerChildInput" type="text" name="editOrderDescription" placeholder="' . $customerOrderRow['description'] . '"><br>
                                            - Progress: <input class="employeesLandingCustomerChildInput" type="text" name="editOrderProgress" placeholder="' . $customerOrderRow['progress'] . '"><br>
                                            - Cost: $<input class="employeesLandingCustomerChildInput" type="text" name="editOrderCost" placeholder="' . $customerOrderRow['cost'] . '"><br>
                                            - Paid: <input class="employeesLandingCustomerChildInput" type="text" name="editOrderPaid" placeholder="' . $customerOrderRow['isPaid'] . '"><br>' . 
                                            '<button type="submit" name="edit" class="employeesLandingCustomerChildSubmitBtn">
                                                <span>Update Order</span>
                                                <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                            </button>
                                            <button type="submit" name="delete" class="employeesLandingCustomerChildSubmitBtn">
                                                <span>Delete</span>
                                                <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                            </button>
                                        </form>';
                                    echo '</div>';                    
                                }
                            }
                        ?>
                        <div class="employeesLandingCustomerParent">
                            <div onmousedown="employeesLandingCustomerPress(<?php echo mysqli_num_rows($customerRslt); ?>);" class="employeesLandingCustomerTitle">Add Customer
                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                            </div>
                            <form class="employeesLandingCustomerChild" method="POST" action="../javascripts/employeeCustomerForm.php">
                                Full Name: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerName" placeholder="John Doe" required><br>
                                Username: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerUsername" placeholder="jdoe" required><br>
                                Password: <input class="employeesLandingCustomerChildInput" type="password" name="editCustomerPassword" placeholder="jdoe123" required><br>
                                Order:
                                - Name: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerOrderName" placeholder="Project" required><br>
                                - Description: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerOrderDescription" placeholder="In Progress" required><br>
                                - Progress: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerOrderProgress" placeholder="0%" required><br>
                                - Cost: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerOrderCost" placeholder="0.00" required><br>
                                - Paid: <input class="employeesLandingCustomerChildInput" type="text" name="editCustomerOrderPaid" placeholder="No" required><br>
                                <button type="submit" name="add" class="employeesLandingCustomerChildSubmitBtn">
                                    <span>Create Customer</span>
                                    <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="employeesLandingLeft hide">
                        <div style="font-size:25px">Contact:</div>
                        <?php
                            $config = parse_ini_file("../javascripts/.ht.ini");
                            $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
                            if (mysqli_connect_error()) {
                                header("Location: error.html?success=0");
                                exit;
                            } else {
                                $contactSql = "SELECT `id`,`name`,`email`,`message` FROM `contact`";
                                $contactRslt = $conn->query($contactSql);
                                for ($i = 0; $i < mysqli_num_rows($contactRslt); $i++) {
                                    $contactMessageRow = mysqli_fetch_array($contactRslt);
                                    echo '<div class="employeesLandingContactParent">';
                                    echo '<div onmousedown="employeesLandingContactPress(' . $i. ');" class="employeesLandingContactTitle">Message ' . ($i+1) . '
                                            <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                            <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                                        </div>';
                                    echo '<div class="employeesLandingContactChild">
                                            Name: ' . $contactMessageRow['name'] . '<br>
                                            Email: ' . $contactMessageRow['email'] . '<br>
                                            Message: ' . $contactMessageRow['message'] . 
                                            '<form method="post" action="../javascripts/employeeContactForm.php">
                                                <input type="hidden" id="contactDeleteID" name="contactDeleteID" value="' . $contactMessageRow['id'] . '">
                                                <button type="submit" class="employeesLandingCustomerChildSubmitBtn">
                                                    <span>Delete</span>
                                                    <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                                </button>
                                            </form>
                                        </div>';
                                    echo '</div>';                    
                                }
                            }
                        ?>
                    </div>

                    <div class="employeesLandingLeft hide">
                        <div class="employeesLandingLeftEditBtnDiv">
                            <a onmousedown="employeesEditBtnToggle(this);" class="employeesLandingLeftEditBtn">
                                <div>Edit</div>
                                <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                            </a>
                        </div>
                        <div class="employeesLandingLeftEdit">
                            <div style="font-size:25px">Profile:</div>
                            <div style="font-size:22px"> - Full Name: <span class="employeesLandingLeftInfo"><?php echo $_SESSION["name"]; ?></span></div>
                            <div style="font-size:22px"> - Username: <span class="employeesLandingLeftInfo"><?php echo $_SESSION["username"]; ?></span></div>
                            <div style="font-size:22px"> - Password: <span class="employeesLandingLeftInfo"><?php echo str_repeat('*', strlen($_SESSION["password"])); ?></span></div>
                        </div>
                        <div class="employeesLandingLeftEdit hide">
                            <form action="../javascripts/employeesForm.php" method="post">
                                <div style="font-size:25px">Profile:</div>
                                <div style="font-size:22px"> - Full Name: <input class="employeesLandingFormInput" type="text" name="editName" placeholder="<?php echo $_SESSION["name"]; ?>"></div>
                                <div style="font-size:22px"> - Username: <input class="employeesLandingFormInput" type="text" name="editUser" placeholder="<?php echo $_SESSION["username"]; ?>"></div>
                                <div style="font-size:22px"> - Password: <input class="employeesLandingFormInput" type="password" name="editPass" placeholder="<?php echo $_SESSION["password"];?>"></div>
                                <div class="btnDiv" style="margin: 10px auto 0 auto; width:125px; height:30px;">
                                    <div class="btnDivWrap">
                                        <div class="btnBkg"></div>
                                        <input class="btn" onmouseover="moveBtnBkgRight(this);" onmouseleave="moveBtnBkgLeft(this);" type="submit">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="employeesLandingRightDiv">
                    <a onmousedown="employeesBtnPress(0); employeesEditBtnHide();" class="employeesLandingRightBtn active">
                        <svg viewBox="0 0 576 512"><path d="M573.65 200.92l-11.34-11.31c-3.13-3.12-8.21-3.12-11.34 0l-17.01 16.97-52.98-52.86c5.64-21.31.36-44.9-16.39-61.61l-45.36-45.25C387.92 15.62 346.88 0 305.84 0c-41.04 0-82.09 15.62-113.4 46.86l68.66 68.5c-4.42 16.71-1.88 34.7 7.4 49.8L21.47 395.26c-27.95 26.04-28.71 70.01-1.67 96.99C33.02 505.44 50.32 512 67.59 512c18.05 0 36.09-7.17 49.42-21.42l233.13-249.14c12.84 5.06 26.84 6.3 40.12 2.8l52.98 52.86-17.01 16.97a7.985 7.985 0 0 0 0 11.31l11.34 11.31c3.13 3.12 8.21 3.12 11.34 0l124.74-124.45a7.997 7.997 0 0 0 0-11.32zM93.57 468.74C86.78 476 77.55 480 67.59 480c-9.48 0-18.4-3.69-25.11-10.38-6.87-6.86-10.57-15.97-10.4-25.67.17-9.7 4.17-18.68 11.28-25.3l246.37-229.47 33.85 33.77L93.57 468.74zm372.35-194.28l-52.98-52.85-13.04-13.01-17.83 4.7c-11.3 2.98-22.84-.03-30.87-8.04l-51.03-50.91c-8.03-8.01-11.04-19.53-8.06-30.8l4.71-17.79-13.04-13.01-43.14-43.05c19.54-11.54 41.9-17.7 65.2-17.7 34.27 0 66.48 13.32 90.72 37.49l45.36 45.26c8.03 8.01 11.04 19.53 8.06 30.8l-4.71 17.79 13.04 13.01 52.98 52.86-45.37 45.25z"></path></svg>
                        <div>Customers</div>
                    </a>
                    <a onmousedown="employeesBtnPress(1); employeesEditBtnHide();" class="employeesLandingRightBtn">
                        <svg viewBox="0 0 512 512"><path d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 96h416c8.8 0 16 7.2 16 16v41.4c-21.9 18.5-53.2 44-150.6 121.3-16.9 13.4-50.2 45.7-73.4 45.3-23.2.4-56.6-31.9-73.4-45.3C85.2 197.4 53.9 171.9 32 153.4V112c0-8.8 7.2-16 16-16zm416 320H48c-8.8 0-16-7.2-16-16V195c22.8 18.7 58.8 47.6 130.7 104.7 20.5 16.4 56.7 52.5 93.3 52.3 36.4.3 72.3-35.5 93.3-52.3 71.9-57.1 107.9-86 130.7-104.7v205c0 8.8-7.2 16-16 16z"></path></svg>
                        <div>Contact</div>
                    </a>
                    <a onmousedown="employeesBtnPress(2); employeesEditBtnHide();" class="employeesLandingRightBtn">
                        <svg viewBox="0 0 448 512"><path d="M313.6 288c-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4zM416 464c0 8.8-7.2 16-16 16H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 56.5 0 102.4 45.9 102.4 102.4V464zM224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96z"></path></svg>
                        <div>Profile</div>
                    </a>
                    <a href="../javascripts/logout.php" class="employeesLandingRightBtn">
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
        if (window.location.href.includes("?")) {
            var checkurl = window.location.href.split("?")[1].split("#")[0];
            if (checkurl == "success=1") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].innerHTML = "Success! Employee Profile Updated.";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=2") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].innerHTML = "Success! User Order Updated.";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=3") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].innerHTML = "Success! User Deleted.";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=4") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].innerHTML = "Success! Contact Message Deleted.";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=0") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].style.backgroundColor = "#bb000080";
                document.getElementsByClassName("employeesLandingSuccessBanner")[0].innerHTML = "Error! Please Try Again.";
            }
        }
    }

    function employeesLandingCustomerPress(elem) {
        parentArr = document.getElementsByClassName("employeesLandingCustomerParent");
        for (var i = 0; i < parentArr.length; i++) {
            if (i != elem) {
                parentArr[i].classList.remove("selected");
            }
        }
        parentArr[elem].classList.toggle("selected");
    }
    function employeesLandingContactPress(elem) {
        parentArr = document.getElementsByClassName("employeesLandingContactParent");
        for (var i = 0; i < parentArr.length; i++) {
            if (i != elem) {
                parentArr[i].classList.remove("selected");
            }
        }
        parentArr[elem].classList.toggle("selected");
    }

    function employeesLandingCustomerEditHide(elem) {
        elem.parentElement.classList.add('hide');
        elem.parentElement.nextElementSibling.classList.remove('hide');
    }

    function employeesBtnPress(num) {
        temps1 = document.getElementsByClassName("employeesLandingRightBtn");
        temps2 = document.getElementsByClassName("employeesLandingLeft");
        for (var i = 0; i < temps1.length; i++) {
            temps1[i].classList.remove("active");
        }
        for (var i = 0; i < temps2.length; i++) {
            temps2[i].classList.add("hide");
        }
        temps1[num].classList.add("active");
        temps2[num].classList.remove("hide");
    }
    function employeesEditBtnToggle(elem) {
        if (elem.parentElement.parentElement.children[1].classList.contains("hide")) {
            employeesEditBtnHide();
        } else {
            employeesEditBtnShow(elem);
        }
    }
    function employeesEditBtnShow(elem) {
        elem1 = elem.parentElement.parentElement.children[1].classList.add("hide");
        elem2 = elem.parentElement.parentElement.children[2].classList.remove("hide");
        elem.lastElementChild.outerHTML = '<svg viewBox="0 0 512 512"><path d="M504 256C504 119 393 8 256 8S8 119 8 256s111 248 248 248 248-111 248-248zM256 472c-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216zm-12.5-92.5l-115.1-115c-4.7-4.7-4.7-12.3 0-17l115.1-115c4.7-4.7 12.3-4.7 17 0l6.9 6.9c4.7 4.7 4.7 12.5-.2 17.1L181.7 239H372c6.6 0 12 5.4 12 12v10c0 6.6-5.4 12-12 12H181.7l85.6 82.5c4.8 4.7 4.9 12.4.2 17.1l-6.9 6.9c-4.8 4.7-12.4 4.7-17.1 0z"></path></svg>';
        elem.firstElementChild.innerHTML = "Back";
    }
    function employeesEditBtnHide() {
        temp = document.getElementsByClassName("employeesLandingLeftEdit");
        for (var i = 0; i < temp.length; i++) {
            if (i % 2 == 0) {
                temp[i].classList.remove("hide");
            } else {
                temp[i].classList.add("hide");
            }
        }
        temp1 = document.getElementsByClassName("employeesLandingLeftEditBtn");
        for (var i = 0; i < temp1.length; i++) {
            temp1[i].lastElementChild.outerHTML = '<svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>';
            temp1[i].firstElementChild.innerHTML = "Edit";
        }
    }
</script>

</html>