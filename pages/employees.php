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
        if (mysqli_num_rows($rslt1) < 1) {
            $_SESSION["username"] = "";
            $_SESSION["password"] = "";
            session_destroy();
            header("Location: ../pages/login.html");
        } else {
            $row1 = mysqli_fetch_array($rslt1);
            $_SESSION["id"] = $row1['id'];
            $_SESSION["name"] = $row1['name'];
        }
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
                <div class="customerLandingSuccessBanner"></div>
            </div>

            <div class="pageLandingTitleDiv">
                <span class="pageLandingTitle">Employees</span>
            </div>
            
            <div class="customerLandingDiv">
                <div class="customerLandingLeftDiv">
                    <!-- Customers -->
                    <div class="customerLandingLeft">
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
                                    echo '<div class="customerLandingCustomerParent">';
                                    echo '<div onmousedown="employeesLandingCustomerPress(' . $i. ');" class="customerLandingCustomerTitle">' . $customerRow['name'] . '
                                            <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                            <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                                        </div>';
                                    echo '<div class="customerLandingCustomerChild">
                                            Username: ' . $customerRow['username'] . '<br>
                                            Email: <a style="text-decoration: underline;" href="mailto:' . $customerRow['email'] . '">' . $customerRow['email'] . '</a><br>
                                            Phone: ' . $customerRow['phone'] . '<br>
                                            Order #' . $customerRow['orders'] . ':<br>
                                            - Name: ' . $customerOrderRow['name'] . '<br>
                                            - Description: ' . $customerOrderRow['description'] . '<br>
                                            - Progress: ' . $customerOrderRow['progress'] . '%<br>
                                            - Cost: $' . $customerOrderRow['cost'] . '<br>
                                            - Paid: ' . $customerOrderRow['isPaid'] . 
                                            '<div onmousedown="employeesLandingCustomerEditHide(this)" class="customerLandingCustomerChildBtn green"><span>Edit Order</span>
                                                <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                            </div>
                                            <form method="post" action="../javascripts/employeeCustomerForm.php">
                                                <input type="hidden" id="editID" name="editID" value="' . $customerRow['id'] . '">
                                                <input type="hidden" id="editOrderID" name="editOrderID" value="' . $customerRow['orders'] . '">
                                                <button type="submit" name="delete" class="customerLandingCustomerChildSubmitBtn red">
                                                    <span>Delete</span>
                                                    <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                                </button>
                                            </form>
                                        </div>';
                                    echo '<form class="customerLandingCustomerChild hide" method="POST" action="../javascripts/employeeCustomerForm.php">
                                            Username: ' . $customerRow['username'] . '<br>
                                            Email: ' . $customerRow['email'] . '<br>
                                            Phone: ' . $customerRow['phone'] . '<br>
                                            Order #' . $customerRow['orders'] . ':<br>
                                            <input type="hidden" id="editID" name="editID" value="' . $customerRow['id'] . '">
                                            <input type="hidden" id="editOrderID" name="editOrderID" value="' . $customerRow['orders'] . '">
                                            - Name: <input class="customerLandingCustomerChildInput" type="text" name="editOrderName" placeholder="' . $customerOrderRow['name'] . '"><br>
                                            - Description: <input class="customerLandingCustomerChildInput" type="text" name="editOrderDescription" placeholder="' . $customerOrderRow['description'] . '"><br>
                                            - Progress: <input class="customerLandingCustomerChildInput" type="text" name="editOrderProgress" placeholder="' . $customerOrderRow['progress'] . '"><br>
                                            - Cost: $<input class="customerLandingCustomerChildInput" type="text" name="editOrderCost" placeholder="' . $customerOrderRow['cost'] . '"><br>
                                            - Paid: <input class="customerLandingCustomerChildInput" type="text" name="editOrderPaid" placeholder="' . $customerOrderRow['isPaid'] . '"><br>' . 
                                            '<button type="submit" name="edit" class="customerLandingCustomerChildSubmitBtn green">
                                                <span>Update Order</span>
                                                <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                            </button>
                                            <button type="submit" name="delete" class="customerLandingCustomerChildSubmitBtn red">
                                                <span>Delete</span>
                                                <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                            </button>
                                        </form>';
                                    echo '</div>';                    
                                }
                            }
                        ?>
                        <div class="customerLandingCustomerParent">
                            <div onmousedown="employeesLandingCustomerPress(<?php echo mysqli_num_rows($customerRslt); ?>);" class="customerLandingCustomerTitle">Add Customer
                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                            </div>
                            <form class="customerLandingCustomerChild" method="POST" action="../javascripts/employeeCustomerForm.php">
                                Full Name: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerName" placeholder="John Doe" required><br>
                                Username: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerUsername" placeholder="jdoe" required><br>
                                Password: <input class="customerLandingCustomerChildInput" type="password" name="editCustomerPassword" placeholder="jdoe123" required><br>
                                Order:<br>
                                - Name: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerOrderName" placeholder="Project" required><br>
                                - Description: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerOrderDescription" placeholder="In Progress" required><br>
                                - Progress: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerOrderProgress" placeholder="0%" required><br>
                                - Cost: $<input class="customerLandingCustomerChildInput" type="text" name="editCustomerOrderCost" placeholder="0.00" required><br>
                                - Paid: <input class="customerLandingCustomerChildInput" type="text" name="editCustomerOrderPaid" placeholder="No" required><br>
                                <button type="submit" name="add" class="customerLandingCustomerChildSubmitBtn green">
                                    <span>Create Customer</span>
                                    <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- Employees -->
                    <div class="customerLandingLeft hide">
                        <div style="font-size:25px">Employees:</div>
                        <?php
                            $config = parse_ini_file("../javascripts/.ht.ini");
                            $conn = new mysqli($config['srvr'], $config['user'], $config['pass'], $config['data']);
                            if (mysqli_connect_error()) {
                                header("Location: error.html?success=0");
                                exit;
                            } else {
                                $employeeSql = "SELECT `id`,`username`,`name` FROM `employees`";
                                $employeeRslt = $conn->query($employeeSql);
                                for ($i = 0; $i < mysqli_num_rows($employeeRslt); $i++) {
                                    $employeeRow = mysqli_fetch_array($employeeRslt);
                                    echo '<div class="customerLandingEmployeeParent">';
                                    echo '<div onmousedown="employeesLandingEmployeePress(' . $i. ');" class="customerLandingCustomerTitle">' . $employeeRow['name'] . '
                                            <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                            <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                                        </div>';
                                    echo '<div class="customerLandingCustomerChild">
                                            Username: ' . $employeeRow['username'] . '
                                            <form method="post" action="../javascripts/employeeEmployeeForm.php">
                                                <input type="hidden" id="editID" name="editID" value="' . $employeeRow['id'] . '">
                                                <button type="submit" name="delete" class="customerLandingCustomerChildSubmitBtn red">
                                                    <span>Delete</span>
                                                    <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                                </button>
                                            </form>
                                        </div>';
                                    echo '</div>';
                                }
                            }
                        ?>
                        <div class="customerLandingEmployeeParent">
                            <div onmousedown="employeesLandingEmployeePress(<?php echo mysqli_num_rows($employeeRslt); ?>);" class="customerLandingCustomerTitle">Add Employee
                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                            </div>
                            <form class="customerLandingCustomerChild" method="POST" action="../javascripts/employeeEmployeeForm.php">
                                Full Name: <input class="customerLandingCustomerChildInput" type="text" name="editEmployeeName" placeholder="John Doe" required><br>
                                Username: <input class="customerLandingCustomerChildInput" type="text" name="editEmployeeUsername" placeholder="jdoe" required><br>
                                Password: <input class="customerLandingCustomerChildInput" type="password" name="editEmployeePassword" placeholder="jdoe123" required><br>
                                <button type="submit" name="add" class="customerLandingCustomerChildSubmitBtn green">
                                    <span>Create Employee</span>
                                    <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- Contact -->
                    <div class="customerLandingLeft hide">
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
                                if (mysqli_num_rows($contactRslt) < 1) {
                                    echo '<div class="customerLandingContactParent">';
                                    echo '<div class="customerLandingCustomerTitle">No Messages
                                            <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                                        </div>';
                                    echo '</div>';
                                } else {
                                    for ($i = 0; $i < mysqli_num_rows($contactRslt); $i++) {
                                        $contactMessageRow = mysqli_fetch_array($contactRslt);
                                        echo '<div class="customerLandingContactParent">';
                                        echo '<div onmousedown="employeesLandingContactPress(' . $i. ');" class="customerLandingCustomerTitle">Message ' . ($i+1) . '
                                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                                            </div>';
                                        echo '<div class="customerLandingCustomerChild">
                                                Name: ' . $contactMessageRow['name'] . '<br>
                                                Email: <a style="text-decoration: underline;" href="mailto:' . $contactMessageRow['email'] . '">' . $contactMessageRow['email'] . '</a><br>
                                                Message: ' . $contactMessageRow['message'] . 
                                                '<form method="post" action="../javascripts/employeeContactForm.php">
                                                    <input type="hidden" id="contactDeleteID" name="contactDeleteID" value="' . $contactMessageRow['id'] . '">
                                                    <button type="submit" class="customerLandingCustomerChildSubmitBtn red">
                                                        <span>Delete</span>
                                                        <svg viewBox="0 0 640 512"><path d="M469.66 181.65l-11.31-11.31c-3.12-3.12-8.19-3.12-11.31 0L384 233.37l-63.03-63.03c-3.12-3.12-8.19-3.12-11.31 0l-11.31 11.31c-3.12 3.12-3.12 8.19 0 11.31L361.38 256l-63.03 63.03c-3.12 3.12-3.12 8.19 0 11.31l11.31 11.31c3.12 3.12 8.19 3.12 11.31 0L384 278.63l63.03 63.03c3.12 3.12 8.19 3.12 11.31 0l11.31-11.31c3.12-3.12 3.12-8.19 0-11.31L406.63 256l63.03-63.03a8.015 8.015 0 0 0 0-11.32zM576 64H205.26C188.28 64 172 70.74 160 82.74L9.37 233.37c-12.5 12.5-12.5 32.76 0 45.25L160 429.25c12 12 28.28 18.75 45.25 18.75H576c35.35 0 64-28.65 64-64V128c0-35.35-28.65-64-64-64zm32 320c0 17.64-14.36 32-32 32H205.26c-8.55 0-16.58-3.33-22.63-9.37L32 256l150.63-150.63c6.04-6.04 14.08-9.37 22.63-9.37H576c17.64 0 32 14.36 32 32v256z"></path></svg>
                                                    </button>
                                                </form>
                                            </div>';
                                        echo '</div>';                    
                                    }
                                }
                            }
                        ?>
                    </div>
                    <!-- Profile -->
                    <div class="customerLandingLeft hide">
                        <div style="font-size:25px">Profile:</div>
                        <div class="customerLandingCustomerParent">
                            <div onmousedown="employeesLandingProfilePress(this);" class="customerLandingCustomerTitle"><?php echo $_SESSION['name']; ?>
                                <svg class="up" viewBox="0 0 24 24"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path></svg>
                                <svg class="down" viewBox="0 0 24 24"><path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path></svg>
                            </div>
                            <form class="customerLandingCustomerChild" method="POST" action="../javascripts/employeeForm.php">
                                Full Name: <input class="customerLandingCustomerChildInput" type="text" name="editEmployeeName" placeholder="<?php echo $_SESSION["name"]; ?>"><br>
                                Username: <input class="customerLandingCustomerChildInput" type="text" name="editEmployeeUser" placeholder="<?php echo $_SESSION["username"]; ?>"><br>
                                Password: <input class="customerLandingCustomerChildInput" type="password" name="editEmployeePass" placeholder="<?php echo $_SESSION["password"];?>"><br>
                                <button type="submit" name="add" class="customerLandingCustomerChildSubmitBtn green">
                                    <span>Update Profile</span>
                                    <svg viewBox="0 0 640 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm0-224c52.9 0 96 43.1 96 96s-43.1 96-96 96-96-43.1-96-96 43.1-96 96-96zm406.6 204.1l-34.7-34.7c-6.3-6.3-14.5-9.4-22.8-9.4-8.2 0-16.5 3.1-22.8 9.4L327.8 424l-7.6 68.2c-1.2 10.7 7.2 19.8 17.7 19.8.7 0 1.3 0 2-.1l68.2-7.6 222.5-222.5c12.5-12.7 12.5-33.1 0-45.7zM393.3 473.7l-39.4 4.5 4.4-39.5 156.9-156.9 35 35-156.9 156.9zm179.5-179.5l-35-35L573 224h.1l.2.1 34.7 35-35.2 35.1zM134.4 320c19.6 0 39.1 16 89.6 16 50.4 0 70-16 89.6-16 20.7 0 39.9 6.3 56 16.9l22.8-22.8c-22.2-16.2-49.3-26-78.8-26-28.7 0-42.5 16-89.6 16-47.1 0-60.8-16-89.6-16C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h243.5c-2.8-7.4-4.1-15.4-3.2-23.4l1-8.6H48c-8.8 0-16-7.2-16-16v-41.6C32 365.9 77.9 320 134.4 320z" ></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="customerLandingRightDiv">
                    <a onmousedown="employeesBtnPress(0);" class="customerLandingRightBtn active">
                        <svg viewBox="0 0 640 512"><path d="M544 224c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80 35.8 80 80 80zm0-128c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zM320 256c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm0-192c44.1 0 80 35.9 80 80s-35.9 80-80 80-80-35.9-80-80 35.9-80 80-80zm244 192h-40c-15.2 0-29.3 4.8-41.1 12.9 9.4 6.4 17.9 13.9 25.4 22.4 4.9-2.1 10.2-3.3 15.7-3.3h40c24.2 0 44 21.5 44 48 0 8.8 7.2 16 16 16s16-7.2 16-16c0-44.1-34.1-80-76-80zM96 224c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80 35.8 80 80 80zm0-128c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zm304.1 180c-33.4 0-41.7 12-80.1 12-38.4 0-46.7-12-80.1-12-36.3 0-71.6 16.2-92.3 46.9-12.4 18.4-19.6 40.5-19.6 64.3V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-44.8c0-23.8-7.2-45.9-19.6-64.3-20.7-30.7-56-46.9-92.3-46.9zM480 432c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16v-44.8c0-16.6 4.9-32.7 14.1-46.4 13.8-20.5 38.4-32.8 65.7-32.8 27.4 0 37.2 12 80.2 12s52.8-12 80.1-12c27.3 0 51.9 12.3 65.7 32.8 9.2 13.7 14.1 29.8 14.1 46.4V432zM157.1 268.9c-11.9-8.1-26-12.9-41.1-12.9H76c-41.9 0-76 35.9-76 80 0 8.8 7.2 16 16 16s16-7.2 16-16c0-26.5 19.8-48 44-48h40c5.5 0 10.8 1.2 15.7 3.3 7.5-8.5 16.1-16 25.4-22.4z"></path></svg>
                        <div>Customers</div>
                    </a>
                    <a onmousedown="employeesBtnPress(1);" class="customerLandingRightBtn">
                        <svg viewBox="0 0 640 512"><path d="M544 224c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80 35.8 80 80 80zm0-128c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zM320 256c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm0-192c44.1 0 80 35.9 80 80s-35.9 80-80 80-80-35.9-80-80 35.9-80 80-80zm244 192h-40c-15.2 0-29.3 4.8-41.1 12.9 9.4 6.4 17.9 13.9 25.4 22.4 4.9-2.1 10.2-3.3 15.7-3.3h40c24.2 0 44 21.5 44 48 0 8.8 7.2 16 16 16s16-7.2 16-16c0-44.1-34.1-80-76-80zM96 224c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80 35.8 80 80 80zm0-128c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zm304.1 180c-33.4 0-41.7 12-80.1 12-38.4 0-46.7-12-80.1-12-36.3 0-71.6 16.2-92.3 46.9-12.4 18.4-19.6 40.5-19.6 64.3V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-44.8c0-23.8-7.2-45.9-19.6-64.3-20.7-30.7-56-46.9-92.3-46.9zM480 432c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16v-44.8c0-16.6 4.9-32.7 14.1-46.4 13.8-20.5 38.4-32.8 65.7-32.8 27.4 0 37.2 12 80.2 12s52.8-12 80.1-12c27.3 0 51.9 12.3 65.7 32.8 9.2 13.7 14.1 29.8 14.1 46.4V432zM157.1 268.9c-11.9-8.1-26-12.9-41.1-12.9H76c-41.9 0-76 35.9-76 80 0 8.8 7.2 16 16 16s16-7.2 16-16c0-26.5 19.8-48 44-48h40c5.5 0 10.8 1.2 15.7 3.3 7.5-8.5 16.1-16 25.4-22.4z"></path></svg>
                        <div>Employees</div>
                    </a>
                    <a onmousedown="employeesBtnPress(2);" class="customerLandingRightBtn">
                        <svg viewBox="0 0 512 512"><path d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 96h416c8.8 0 16 7.2 16 16v41.4c-21.9 18.5-53.2 44-150.6 121.3-16.9 13.4-50.2 45.7-73.4 45.3-23.2.4-56.6-31.9-73.4-45.3C85.2 197.4 53.9 171.9 32 153.4V112c0-8.8 7.2-16 16-16zm416 320H48c-8.8 0-16-7.2-16-16V195c22.8 18.7 58.8 47.6 130.7 104.7 20.5 16.4 56.7 52.5 93.3 52.3 36.4.3 72.3-35.5 93.3-52.3 71.9-57.1 107.9-86 130.7-104.7v205c0 8.8-7.2 16-16 16z"></path></svg>
                        <div>Contact</div>
                    </a>
                    <a onmousedown="employeesBtnPress(3);" class="customerLandingRightBtn">
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
        if (window.location.href.includes("?")) {
            var checkurl = window.location.href.split("?")[1].split("#")[0];
            if (checkurl == "success=1") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! Employee Profile Updated.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=2") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! Order Updated.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=3") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! User/Order Deleted.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=4") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! User/Order Added.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=5") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! Employee Deleted.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=6") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! Employee Added.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=7") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Success! Contact Message Deleted.";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.color = "#000000";
            } else if (checkurl == "success=0") {
                document.getElementsByClassName("pageLandingTitle")[0].style.paddingTop = "0px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.height = "50px";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].style.backgroundColor = "#bb000080";
                document.getElementsByClassName("customerLandingSuccessBanner")[0].innerHTML = "Error! Please Try Again.";
            }
        }
    }
    function employeesLandingCustomerPress(elem) {
        parentArr = document.getElementsByClassName("customerLandingCustomerParent");
        for (var i = 0; i < parentArr.length; i++) {
            if (i != elem) {
                parentArr[i].classList.remove("selected");
            }
        }
        parentArr[elem].classList.toggle("selected");
    }
    function employeesLandingEmployeePress(elem) {
        parentArr = document.getElementsByClassName("customerLandingEmployeeParent");
        for (var i = 0; i < parentArr.length; i++) {
            if (i != elem) {
                parentArr[i].classList.remove("selected");
            }
        }
        parentArr[elem].classList.toggle("selected");
    }
    function employeesLandingContactPress(elem) {
        parentArr = document.getElementsByClassName("customerLandingContactParent");
        for (var i = 0; i < parentArr.length; i++) {
            if (i != elem) {
                parentArr[i].classList.remove("selected");
            }
        }
        parentArr[elem].classList.toggle("selected");
    }
    function employeesLandingProfilePress(elem) {
        elem.parentElement.classList.toggle("selected");
    }
    function employeesLandingCustomerEditHide(elem) {
        elem.parentElement.classList.add('hide');
        elem.parentElement.nextElementSibling.classList.remove('hide');
    }
    function employeesBtnPress(num) {
        temps1 = document.getElementsByClassName("customerLandingRightBtn");
        temps2 = document.getElementsByClassName("customerLandingLeft");
        temps3 = document.getElementsByClassName("customerLandingCustomerParent");
        temps4 = document.getElementsByClassName("customerLandingEmployeeParent");
        temps5 = document.getElementsByClassName("customerLandingContactParent");
        for (var i = 0; i < temps1.length; i++) {
            temps1[i].classList.remove("active");
        }
        for (var i = 0; i < temps2.length; i++) {
            temps2[i].classList.add("hide");
        }
        for (var i = 0; i < temps3.length; i++) {
            temps3[i].classList.remove("selected");
        }
        for (var i = 0; i < temps4.length; i++) {
            temps4[i].classList.remove("selected");
        }
        for (var i = 0; i < temps5.length; i++) {
            temps5[i].classList.remove("selected");
        }
        temps1[num].classList.add("active");
        temps2[num].classList.remove("hide");
    }
</script>

</html>