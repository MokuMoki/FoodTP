<!DOCTYPE html>

<head lang="en">
    <link rel="stylesheet" href="css/login.css">
    <title>Login FoodTP</title>

    <!-- Bootstrap pre-requisite -->
    <?php include 'head.php' ?>
</head>

<body>
    <?php
    include 'navbar.php';

    if (isset($_SESSION['userid'])){
        header("Location: profile.php");
    }

    #Check if user pressed the Login button.
    if (isset($_POST['login'])) {
        include 'conn.php';
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        #Fetch UserID and role of user.
        $sql = "SELECT * FROM users WHERE email = '$email'";

        if ($result = mysqli_query($con, $sql)) {
            $row = mysqli_fetch_assoc($result);
            $rowcount = mysqli_num_rows($result);

            if (password_verify($password, $row['Password'])) {
                if (isset($_POST['rememberme']) && $_POST['rememberme'] == 'remember') {
                    $_SESSION['keepsession'] = 'True';
                } else {
                    $_SESSION['keepsession'] = 'False';
                    $_SESSION['session_expire'] = time() + 3600;
                }
                $_SESSION['userid'] = $row['UserID'];   #Keep UserID and Role into session.
                $_SESSION['role'] = (int) $row['Role'];
                $id = $_SESSION['userid'];

                #Look for username according to role of user. 1 = Customer, 2 = Resturant, 3 = Delivery
                if ($_SESSION['role'] == 1) {
                    $sql = "SELECT cnickname FROM customer WHERE cuserid = '$id'";    #Use the UserID given to lookup Customer name.
                    $result2 = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result2);
                    $_SESSION['username'] = $row['cnickname'];  #Set Customer name into sesison.
                } elseif ($row['Role'] == 2) {
                    $sql = "SELECT rnickname FROM restaurant WHERE ruserid = '$id'";
                    $result2 = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result2);
                    $_SESSION['username'] = $row['rnickname'];
                } elseif ($row['Role'] == 3) {
                    $sql = "SELECT dnickname FROM delivery WHERE duserid = '$id'";
                    $result2 = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result2);
                    $_SESSION['username'] = $row['dnickname'];
                } 
                header("location: about.php");
            } else {
                $login_err = 1;
            }
        }
        mysqli_close($con);
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card card-signin my-5" style="transform: translate(0%, 15%);">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form class="form-signin" action="login.php" method="post">

                            <?php
                            if (isset($login_err)) {
                                if ($login_err == 1) {
                                    echo '<div class="alert alert-warning" role="alert"> Your email or password is invalid. </div>';
                                }
                            }
                            ?>

                            <div class="form-label-group">
                                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" value=<?php if (isset($login_err)) {
                                                                                                                                            echo '"' . htmlspecialchars($_POST['email']) . '"';
                                                                                                                                        } else {
                                                                                                                                            echo '"" autofocus ';
                                                                                                                                        } ?> required>
                                <label for="inputEmail">Email address</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
                                <label for="inputPassword">Password</label>
                            </div>

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="rememberme" name="rememberme" value="remember">
                                <label class="custom-control-label" for="rememberme">Remember Me</label>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="login">Sign in</button>

                        </form>
                    </div>
                </div>
            </div>
            <div class="mx-auto vl" style="transform: translate(0%, 100px);">

            </div>
            <div class="col-lg-6 mx-auto">
                <div class="card card-signin my-5" style="transform: translate(0%, 25%);">
                    <div class="card-body">
                        <h5 class="card-title text-center">Register</h5>
                        <form class="form-register" action="register.php" method="post">
                            <input type="hidden" id="register_type" name="register_type" value="">
                            <button onclick="register1()" class="btn btn-lg btn-block btn-register" type="submit" name="customer_register">Customer</button>
                            <button onclick="register3()" class="btn btn-lg btn-block btn-register" type="submit" name="delivery_register">Delivery</button>
                            <button onclick="register2()" class="btn btn-lg btn-block btn-register" type="submit" name="restaurant_register">Restaurant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="transform: translate(0px, 150px);"><?php include 'footer.php'; ?></div>
</body>

<script>
    function register1() {
        document.getElementById('register_type').value = 1;
    }

    function register2() {
        document.getElementById('register_type').value = 2;
    }

    function register3() {
        document.getElementById('register_type').value = 3;
    }
</script>