<!DOCTYPE html>

<head lang="en">
    <link rel="stylesheet" href="css/register.css">
    <title>Register FoodTP</title>

    <!-- Bootstrap pre-requisite -->
    <?php include 'head.php' ?>
</head>

<?php
if(isset($_POST['userid'])){
    header("Location:profile.php");
}

if (isset($_POST['register'])) {
    include 'conn.php';

    #This set of variable are applied to every account types
    $role = $_POST['register_type'];
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $userid = md5(microtime(true) + rand());
    $sql1 = "INSERT INTO users (UserID, Email, Password, Role) VALUES ('$userid','$email','$password','$role')";

    if ($role == 1) {
        $name = mysqli_real_escape_string($con, $_POST['realname']);
        $nickname = mysqli_real_escape_string($con, $_POST['nickname']);
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $accom = $_POST['location'];

        $sql2 = "INSERT INTO customer (CUserID, CName, CNickname, CGender, CDoB, CAccom) VALUES ('$userid','$name','$nickname',$gender,'$dob',$accom)";
    } elseif ($role == 2) {
        $restaurant = mysqli_real_escape_string($con, $_POST['restaurant']);
        $nickname = mysqli_real_escape_string($con, $_POST['nickname']);
        $desc = mysqli_real_escape_string($con, $_POST['description']);
        $street = mysqli_real_escape_string($con, $_POST['street']);
        $area = mysqli_real_escape_string($con, $_POST['area']);
        $territory = $_POST['territory'];
        if ($territory == 1) {
            $territory = "Bukit Jalil, 57000";
        } elseif ($territory == 2) {
            $territory = "Sri Petaling, 57000";
        }
        $cuisine = mysqli_real_escape_string($con, $_POST['cuisine']);
        $fulladdress = $street . ", " . $area . ", " . $territory . " Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur";

        $sql2 = "INSERT INTO restaurant (RUserID, RName, RNickname, RDesc, RAddress, RCuisine) VALUES ('$userid','$restaurant','$nickname','$desc','$fulladdress','$cuisine')";
    } elseif ($role == 3) {
        $name = mysqli_real_escape_string($con, $_POST['realname']);
        $nickname = mysqli_real_escape_string($con, $_POST['nickname']);
        $IC = mysqli_real_escape_string($con, $_POST['IC']);
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $transport = $_POST['transport'];
        $license = mysqli_real_escape_string($con, $_POST['license']);

        $sql2 = "INSERT INTO delivery (DUserID, DName, DNickname, DICard, DGender, DDoB, DTransport, DLicense) VALUES ('$userid','$name','$nickname','$IC',$gender,'$dob','$transport','$license')";
    }


    $checkemail = "SELECT Email, Role FROM users WHERE Email = '$email'";
    $result = mysqli_query($con, $checkemail);
    if (mysqli_num_rows($result) == 0) {
        if (mysqli_query($con, $sql1) && mysqli_query($con, $sql2)) {
            $success = true;
        } else {
            $err_database = true;
            $err_desc = mysqli_error($con);
        }
    } else {
        $err_email_taken = true;
    }
    mysqli_close($con);
}
?>

<body oninput="checker()">
    <?php include 'navbar.php';
    ?>

    <div class="container" style="transform: translate(0px, 100px);">
        <div class="row">
            <div class="col-sm-10 col-lg-7 mx-auto">
                <div class="card card-register">
                    <div class="card-body">
                        <h5 class="card-title text-center">Register</h5>
                        <form class="form-register" action="register.php" method="post">
                            <?php
                            function inputGen($type, $id, $name, $placeholder, $value)  #To generate an input box with generic layout
                            {
                                echo '<div class="form-label-group"><input type="' . $type . '" id="' . $id . '" name="' . $name . '" class="form-control" placeholder="' . $placeholder . '" value="';
                                if (isset($_POST['register'])) {
                                    echo htmlspecialchars($_POST[$name]);
                                };
                                echo '" required><label for="' . $id . '">' . $value . '</label></div>';
                            }

                            function selectGen($id, $name, $value)  #To generate a select input with empty select set, use javascript to generate the list.
                            {
                                echo '<div class="form-group"><label for="' . $id . '">' . $value . '</label><select class="form-control" id="' . $id . '" name="' . $name . '" data-value="';
                                if (isset($_POST['register'])) {
                                    echo htmlspecialchars($_POST[$name]);
                                };
                                echo '"></select></div>';
                            }

                            function dob()  #Generate date time picker for dob
                            {
                                echo '<div class="form-group"><label for="dob">Date of Birth:</label><input type="date" class="form-control" id="dob" name="dob" max="" value="';
                                if (isset($_POST['register'])) {
                                    echo $_POST['dob'];
                                };
                                echo '"></div>';
                            }

                            function checkboxRegister() #Generate the TnC checkbox and Register button
                            {
                                echo '<div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" id="agreeterm" name="tnc"><label class="custom-control-label" for="agreeterm">I agree to terms and conditions.</label></div><button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="register" id="register" disabled>Register!</button>';
                            }

                            if (isset($success)) {
                                echo '<div class="alert alert-success" id="message">Successfully registered. Redirecting in 5 seconds.</div>';
                                echo '<script>var delay = 5000; setTimeout(function(){window.location = "login.php";},delay);</script>';
                            }

                            if (isset($_POST['register_type']) && !isset($success)) {
                                $role = $_POST['register_type'];

                                if (isset($err_email_taken)) {
                                    echo '<div class="alert alert-warning" id="message">Email is already registered. Please use another email.</div>';
                                } else {
                                    echo '<div id="message"></div>';
                                }

                                if (isset($err_database)) {
                                    echo '<div class="alert alert-warning" id="message">Uh-oh, something wrong happened. Error from database: ' . $err_desc . '</div>';
                                } else {
                                    echo '<div id="message"></div>';
                                }

                                if ($role == 1) {
                                    echo '<input type="hidden" id="register_type" name="register_type" value="1">';
                                    inputGen('text', 'inputRealname', 'realname', 'Real Name', 'Real Name (eg. Cheng Wei Lun)');
                                    inputGen('text', 'inputNickname', 'nickname', 'Nickname', 'Nickname (eg. Nicholas)');
                                    inputGen('email', 'inputEmail', 'email', 'Email address', 'Email address (eg. johndoe@apple.com)');
                                    inputGen('password', 'inputPassword', 'password', 'Password', 'Password (At least 8 characters)');
                                    echo '<div id="password_strength"></div>';  #For password strength infomation
                                    inputGen('password', 'inputPassword2', 'password2', 'Password Again', 'Type your password again');
                                    echo '<div id="password_match"></div>'; #For password matching infomation
                                    selectGen('gender', 'gender', 'Gender:');
                                    dob();
                                    selectGen('location', 'location', 'Accommodation:');
                                    checkboxRegister();
                                } else if ($role == 2) {
                                    echo '<input type="hidden" id="register_type" name="register_type" value="2">';
                                    inputGen('text', 'inputRestaurant', 'restaurant', 'Restaurant', 'Restaurant Name (eg. Sweet Taste Restaurant)');
                                    inputGen('text', 'inputNickname', 'nickname', 'Nickname', 'Account Nickname (eg. SweetTaste)');
                                    inputGen('email', 'inputEmail', 'email', 'Email address', 'Email address (eg. sweet_taste@food.tp)');
                                    inputGen('password', 'inputPassword', 'password', 'Password', 'Password (At least 8 characters)');
                                    echo '<div id="password_strength"></div>';
                                    inputGen('password', 'inputPassword2', 'password2', 'Password Again', 'Type your password again');
                                    echo '<div id="password_match"></div>';
                                    inputGen('description', 'inputDesc', 'description', 'Description', 'Short description of restaurant');
                                    echo '<p class="alert alert-warning">Note: Only restaurant in Bukit Jalil/Sri Petaling, 57000 Kuala Lumpur may register for FoodTP.</p>';
                                    inputGen('street', 'inputStreet', 'street', 'Street', 'Street (eg. 6, Jalan Radin Bagus 9)');
                                    inputGen('area', 'inputArea', 'area', 'Area', 'Area (eg. Esplanad Commercial Centre)');
                                    selectGen('territory', 'territory', 'Territory:');
                                    inputGen('cuisine', 'inputCuisine', 'cuisine', 'Cuisine', 'Cuisine');
                                    checkboxRegister();
                                } else if ($role == 3) {
                                    echo '<input type="hidden" id="register_type" name="register_type" value="3">';
                                    inputGen('text', 'inputRealname', 'realname', 'Real Name', 'Real Name (eg. Cheng Wei Lun)');
                                    inputGen('text', 'inputNickname', 'nickname', 'Nickname', 'Nickname (eg. Nicholas)');
                                    inputGen('text', 'inputIC', 'IC', 'IC" pattern="(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})" title="Please enter a valid IC"', 'IC (eg. 000321-08-0001)');
                                    inputGen('email', 'inputEmail', 'email', 'Email address', 'Email address (eg. johndoe@apple.com)');
                                    inputGen('password', 'inputPassword', 'password', 'Password', 'Password (At least 8 characters)');
                                    echo '<div id="password_strength"></div>';
                                    inputGen('password', 'inputPassword2', 'password2', 'Password Again', 'Type your password again');
                                    echo '<div id="password_match"></div>';
                                    selectGen('gender', 'gender', 'Gender:');
                                    dob();
                                    selectGen('transport', 'transport', 'Transport Type:');
                                    inputGen('text', 'inputLicense', 'license', 'License Plate', 'License Plate (eg. WWW0001)');
                                    checkboxRegister();
                                } else {
                                    echo 'Invalid user type. Please go to Sign In page and select type of user to register.';
                                }
                            } else if (!isset($_POST['register_type']) && !isset($success)) {
                                echo 'Invalid register method. Please go to Sign In page and select type of user to register.';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="transform: translate(0px, 150px);"><?php include 'footer.php'; ?></div>
</body>

<script>
    if (document.getElementById('dob')) { //Find out the max dob a customer/delivery person can select, limiting them to 18 y/o above.
        var date = new Date();

        document.getElementById('dob').max = formatDate(date);

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear() - 18;

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }
    }

    function generateSelect(list, id) { //Generate select options from array specified
        var html = "<option value=\"\" selected disabled>Please Select</option>";
        for (i = 0; i < list.length; i++) {
            html += "<option value=\"" + (i + 1) + "\">" + list[i] + "</option>";
        }
        document.getElementById(id).innerHTML = html;
        document.getElementById(id).selectedIndex = document.getElementById(id).getAttribute("data-value");
        console.log(document.getElementById(id).getAttribute("data-value"));
    }

    if (document.getElementById('gender')) {
        var gender = ["Male", "Female", "Others"];
        generateSelect(gender, 'gender');
    }

    if (document.getElementById('location')) {
        var accom = ["APU On Campus", "Vista Komenwel A", "Vista Komenwel B", "Vista Komenwel C", "Endah Promenade", "Endah Regal", "Fortune Park", "Academia", "Others"];
        generateSelect(accom, 'location');
    }

    if (document.getElementById('territory')) {
        var territory = ["Bukit Jalil", "Sri Petaling"];
        generateSelect(territory, 'territory');
    }

    if (document.getElementById('transport')) {
        var transport = ["Car", "Motorbike"];
        generateSelect(transport, 'transport');
    }
</script>
<script type="text/javascript" src="scripts/password_check.js"></script>
<script type="text/javascript" src="scripts/register_check.js"></script>