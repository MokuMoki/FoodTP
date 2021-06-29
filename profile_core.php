<?php
include("conn.php");
include("session_check.php");
# Generate Profile side tabs
# PARAM: role (From $_SESSION['role']) 
function sideTabsAndTop($role)
{
    if ($role == 1) {
        echo '<div class="col-md-3"><div class="list-group" id="tabs"><a href="#orderHistory" class="list-group-item list-group-item-action">Order History</a><a href="#editProfile" class="list-group-item list-group-item-action">Edit Profile</a></div><a href="logout.php" class="list-group-item list-group-item-action" id="logout_button">Logout</a></div>';
        echo '<div class="col-md-9"><div class="card"><div class="card-body toggle" id="orderHistory" style="display:inline"><div class="row"><div class="col-md-12"><h3 style="text-align:center">Order History</h3><hr></div>';
    } elseif ($role == 2) {
        echo '<div class="col-md-3"><div class="list-group" id="tabs"><a href="#orders" class="list-group-item list-group-item-action">Orders Received</a><a href="#fooditems" class="list-group-item list-group-item-action">Food Items</a><a href="#editProfile" class="list-group-item list-group-item-action">Edit Profile</a></div><a href="logout.php" class="list-group-item list-group-item-action" id="logout_button">Logout</a></div>';
        echo '<div class="col-md-9"><div class="card"><div class="card-body toggle" id="orders" style="display:inline"><div class="row"><div class="col-md-12"><h3 style="text-align:center">Orders Received</h3><hr></div>';
    } elseif ($role == 3) {
        echo '<div class="col-md-3"><div class="list-group" id="tabs"><a href="#currentDelivery" class="list-group-item list-group-item-action">Current Delivery</a><a href="#profile" class="list-group-item list-group-item-action">Profile</a><a href="#editProfile" class="list-group-item list-group-item-action">Edit Profile</a></div><a href="logout.php" class="list-group-item list-group-item-action" id="logout_button">Logout</a></div>';
        echo '<div class="col-md-9"><div class="card"><div class="card-body toggle" id="currentDelivery" style="display:inline"><div class="row"><div class="col-md-12"><h3 style="text-align:center">Current Delivery</h3><hr></div>';
    }
}

# FOR ORDER HISTORY
# Generate the Ordered Food table
# PARAMS:   food (Food name)
#           price (Food price)
#           amount (Amount ordered)
function generateOrderTable($food, $price, $amount)
{
    echo '<tr><td>' . $food . '</td><td>RM' . $price . '</td><td>' . $amount . '</td></tr>';
}

# Generate the Orders
# PARAMS:   type (1 = Current Order, 2 = History Orders)
#           orderCount (The order that is currently generating, for History Orders to differentiate between different orders, Current Order put 0)
#           progress (To show using badge what progress the order is in, by default History Orders will be 4, meanwhile Current Order will vary)
#           ODate (Date of the OrderID)
#           OrderID (OrderID of the order currently generating)
#           RUserID (UserID of Restaurant, to get the restaurant name)
#           DUserID (UserID of Delivery Person, to get the delivery person name)
function generateOrderEntry($type, $orderCount, $progress, $ODate, $OrderID, $RUserID, $DUserID)
{
    # Progress bar/pill values
    switch ($progress) {
        case 0:
            $progresspercent = 15;
            $progressverbose = "Preparing";
            $progresscolor = "secondary";
            break;
        case 1:
            $progresspercent = 25;
            $progressverbose = "Awaiting delivery";
            $progresscolor = "secondary";
            break;
        case 2:
            $progresspercent = 50;
            $progressverbose = "Food Picked Up";
            $progresscolor = "info";
            break;
        case 3:
            $progresspercent = 75;
            $progressverbose = "On Transit";
            $progresscolor = "primary";
            break;
        case 4:
            $progresspercent = 100;
            $progressverbose = "Delivered";
            $progresscolor = "success";
            break;
    }

    #Get order details from database
    include "conn.php";

    $sqlcurrentorder = "SELECT DName, DLicense, RName FROM COrderDetails JOIN Delivery ON COrderDetails.DUserID = Delivery.DUserID JOIN Restaurant ON COrderDetails.RUserID = Restaurant.RUserID WHERE OrderID = '" . $OrderID . "'";
    $sqlcurrentorderdetails = "SELECT * FROM COrder JOIN Food ON COrder.FoodID = Food.FoodID JOIN Restaurant ON Food.RUserID = Restaurant.RUserID WHERE OrderID = '" . $OrderID . "'";
    $getresultA = mysqli_query($con, $sqlcurrentorder);
    $getresultB = mysqli_query($con, $sqlcurrentorderdetails);
    $getdataA = mysqli_fetch_array($getresultA);

    $DUsername = $getdataA['DName'];
    $DLicense = $getdataA['DLicense'];
    $RUsername = $getdataA['RName'];
    $total = 0;

    if ($type == 1) {
        echo '<div class="col-md-12" style="margin:10px 0px 30px 0px"><h4>Current Order</h4>';  #Title Current Order
        echo '<div class="card col-md-12" style="padding:10px 0px 20px 0px"><a class="btn" data-toggle="collapse" href="#customercurrentorder" role="button" aria-expanded="false" aria-controls="customercurrentorder" style="align:left;"><h5>';
        echo $RUsername . " (", $ODate, ")";
        echo '</h5><div class="progress"><div class="progress-bar progress-bar-striped bg-' . $progresscolor . ' progress-bar-animated" role="progressbar" style="width: ' . $progresspercent . '%;" aria-valuenow="' . $progresspercent . '" aria-valuemin="0" aria-valuemax="100">' . $progressverbose . '</div></div><hr class="collapse" id="customercurrentorder"></a>';
        echo '<div class="collapse" id="customercurrentorder"><div class="card-body"><div class="col-md-12">'; #Hidden sections
        echo '<p>Order Status: <span class="badge badge-pill badge-' . $progresscolor . '">' . $progressverbose . '</span></p>'; #Order status
        echo '<p>Delivery Person: ' . $DUsername . '</p>';
        echo '<p>License Plate: ' . $DLicense . '</p>';
        #Ordered item table
        echo '<table id="orderFoodtable"><tr><th colspan="3" style="text-align:center"><h5>Ordered Food List</h5></th></tr><tr><th>Food Ordered</th><th>Price</th><th>Amount</th></tr>';

        while ($row = mysqli_fetch_array($getresultB)) {
            $food = $row['FoodName'];
            $price = $row['Price'];
            $amount = $row['Amount'];
            generateOrderTable($food, $price / 100, $amount);

            $total += ($price * $amount);
        }

        echo '<tr><td colspan="2" style="text-align:right">Total Price</td><td><b>RM' . number_format((float) $total / 100, 2, '.', '') . '</b></td></tr>';
        echo '</table></div></div></div></div></div>';
        mysqli_close($con);
    } elseif ($type == 2) {
        echo '<div class="card col-md-12" style="padding:10px 0px 20px 0px; margin:10px 0px 10px 0px"><a class="btn" data-toggle="collapse" href="#customerorder' . $orderCount . '" role="button" aria-expanded="false" aria-controls="customerorder' . $orderCount . '" style="align:left;"><h5>';
        echo $RUsername . " (", $ODate, ")";
        echo '</h5><hr class="collapse" id="customerorder' . $orderCount . '"></a>';
        echo '<div class="collapse" id="customerorder' . $orderCount . '"><div class="card-body"><div class="col-md-12">'; #Hidden sections
        echo '<p>Order Status: <span class="badge badge-pill badge-' . $progresscolor . '">' . $progressverbose . '</span></p>'; #Order status
        echo '<div style="margin: 10px 0px 10px 0px">Delivery Person: ' . $DUsername . ' </div>';
        echo '<table class="col-sm-12 col-lg-6" id="ratingtable">';
        rating($orderCount, 1, $OrderID);
        rating($orderCount, 2, $RUserID);
        rating($orderCount, 3, $DUserID);
        #Ordered item table
        echo '</table><br /><table id="orderFoodtable"><tr><th colspan="3" style="text-align:center"><h5>Ordered Food List</h5></th></tr><tr><th>Food Ordered</th><th>Price</th><th>Amount</th></tr>';

        while ($row = mysqli_fetch_array($getresultB)) {
            $food = $row['FoodName'];
            $price = $row['Price'];
            $amount = $row['Amount'];
            generateOrderTable($food, $price / 100, $amount);

            $total += ($price * $amount);
        }

        echo '<tr><td colspan="2" style="text-align:right">Total Price</td><td><b>RM' . number_format((float) $total / 100, 2, '.', '') . '</b></td></tr>';
        echo '</table></div></div></div></div>';
        mysqli_close($con);
    }
}

# Generate rating for user to rate particular item
# PARAMS:   pastordercount (Order number, to generate unique ID)
#           type (Rating type, to generate unique ID)
#           id (UserID of different user types)
# Generated unique ID and userID are used for jquery to pass POST value to rate.php
function rating($pastordercount, $type, $id)
{
    switch ($type) {
        case 1:
            $usertype = "this order";
            $sql = "SELECT ORating FROM COrderDetails WHERE OrderID = '$id'";
            break;
        case 2:
            $usertype = "restaurant";
            $sql = "SELECT CRRating FROM CRateR WHERE RUserID = '$id' AND CUserID = '" . $_SESSION['userid'] . "'";
            break;
        case 3:
            $usertype = "delivery person";
            $sql = "SELECT CDRating FROM CRateD WHERE DUserID = '$id' AND CUserID = '" . $_SESSION['userid'] . "'";
            break;
    }

    include "conn.php";
    $data = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($data);

    echo '<tr><td><div>Rate ' . $usertype . ' </div></td><td><div class="rating" data-userid="' . $id . '" data-rated="';
    if (mysqli_num_rows($data) == 0) {
        echo '0';
    } else {
        echo '1';
    }
    echo '">';
    for ($i = 5; $i > 0; $i--) {
        echo '<span class="fa fa-star fa-lg ';
        if ($i == $row[0]) {
            echo 'checked';
        }
        echo '"><input type="radio" name="rating" id="' . $type . 'str' . $i . $pastordercount . '" value="' . $i . '"><label for="' . $type . 'str' . $i . $pastordercount . '"></label></span>';
    }
    echo '</div></td></tr>';
    mysqli_close($con);
}

# FOR EDIT PROFILE
# Generate the Edit Profile section
# PARAM: role (From $_SESSION['role']) 
function editProfile($type)
{
    echo '<div class="card-body toggle" id="editProfile" style="display:none"><div class="row"><div class="col-md-12"><h4>Edit Profile</h4><hr></div></div><div class="row"><div class="col-md-12"><form method="post">';

    #Depend on role, query differently
    switch ($_SESSION['role']) {
        case 1:
            $typeSQL = "Customer";
            $typeSQLCol = "CUserID";
            break;
        case 2:
            $typeSQL = "Restaurant";
            $typeSQLCol = "RUserID";
            break;
        case 3:
            $typeSQL = "Delivery";
            $typeSQLCol = "DUserID";
            break;
    }

    include "conn.php";
    #Depend on role, query differently
    $sqlUserDetails = "SELECT * FROM $typeSQL JOIN Users ON $typeSQL.$typeSQLCol = Users.UserID WHERE $typeSQLCol = '" . $_SESSION['userid'] . "'";
    $getresult = mysqli_query($con, $sqlUserDetails);
    $rows = mysqli_fetch_array($getresult);
    mysqli_close($con);

    #Depend on role, generate form differently
    if ($type == 1) {
        inputGen('text', 'inputRealname', 'realname', 'Real Name', 'Real Name', $rows['CName']);
        inputGen('text', 'inputUsername', 'nickname', 'Nickname', 'Nickname', $rows['CNickname']);
        inputGen('email', 'inputEmail', 'email', 'Email', 'Email', $rows['Email']);
        dob($rows['CDoB']);
        selectGen('gender', 'gender', 'Gender', $rows['CGender']);
        selectGen('location', 'location', 'Accommodation', $rows['CAccom']);
    } elseif ($type == 2) {
        inputGen('text', 'inputRestaurant', 'restaurant', 'Restaurant Name', 'Restaurant Name', $rows['RName']);
        inputGen('text', 'inputUsername', 'nickname', 'Nickname', 'Nickname', $rows['RNickname']);
        inputGen('email', 'inputEmail', 'email', 'Email', 'Email', $rows['Email']);
        inputGen('text', 'inputDescription', 'desc', 'Description', 'Description', $rows['RDesc']);
        echo '<div class="form-group row" style="margin:10px 0 10px 0" ><label for="inputAddress" class="col-4 col-form-label">Address</label><div class="col-8"><input type="text" id="inputAddress" name="address" placeholder="Address" class="form-control" value="' . $rows['RAddress'] . '"disabled></div></div>';
        inputGen('text', 'inputCuisine', 'cuisine', 'Cuisine', 'Cuisine', $rows['RCuisine']);
    } elseif ($type == 3) {
        inputGen('text', 'inputRealname', 'realname', 'Real Name', 'Real Name', $rows['DName']);
        inputGen('text', 'inputUsername', 'nickname', 'Nickname', 'Nickname', $rows['DNickname']);
        inputGen('email', 'inputEmail', 'email', 'Email', 'Email', $rows['Email']);
        echo '<div class="form-group row" style="margin:10px 0 10px 0" ><label for="inputIC" class="col-4 col-form-label">IC</label><div class="col-8"><input type="text" id="inputIC" name="IC" placeholder="IC" class="form-control" value="' . $rows['DICard'] . '"disabled></div></div>';
        selectGen('gender', 'gender', 'Gender', $rows['DGender']);
        dob($rows['DDoB']);
        selectGen('transport', 'transport', 'Transport Type', $rows['DTransport']);
        inputGen('text', 'inputLicense', 'license', 'License Plate', 'License Plate', $rows['DLicense']);
    }

    #Static form for Update Profile button and Change Password
    echo '<div class="offset-4 col-8"><button name="update" type="submit" class="btn btn-primary col-sm-5" style="float:right">Update Profile</button></div></form></div></div>';
    echo '<div class="row"><div class="col-md-12"><h4>Change Password</h4><hr></div></div><div class="row"><div class="col-md-12"><form method="post" oninput="enableChangePassword()">';
    inputGen('password', 'inputOldPassword', 'oldpassword', 'Old Password', 'Old Password', "");
    inputGen('password', 'inputNewPassword', 'newpassword', 'New Password', 'New Password', "");
    echo '<div class="offset-4 col-8" id="password_strength"></div>';
    inputGen('password', 'inputNewPassword2', 'newpassword2', 'Confirm New Password', 'Confirm New Password', "");
    echo '<div class="offset-4 col-8" id="password_match"></div>';
    echo '<div class="offset-4 col-8"><button id="changepassword" name="changepassword" type="submit" class="btn btn-primary col-sm-5" style="float:right" disabled>Change Password</button></div></form></div></div></div>';
}

# Generate generic input box
# PARAMS:   type (Input type, such as text, email, password)
#           id (ID of input, for label)
#           name (Name of input, for $_POST)
#           placeholder (Placeholder to show inside input box when no value is specified)
#           label (Front end label to show user what the input box for)
#           value (Value of input box, get from SQL query)
function inputGen($type, $id, $name, $placeholder, $label, $value)
{
    echo '<div class="form-group row" style="margin:10px 0 10px 0" ><label for="' . $id . '" class="col-4 col-form-label">' . $label . '</label><div class="col-8"><input type="' . $type . '" id="' . $id . '" name="' . $name . '" placeholder="' . $placeholder . '" class="form-control" value="' . $value . '"required></div></div>';
}

#Generate a Select input with empty Select set, use javascript to generate the list
# PARAMS:   id (ID of input, for label)
#           name (Name of input, for $_POST)
#           label (Front end label to show user what the select box for)
#           value (Value of selected item, get from SQL query)
function selectGen($id, $name, $label, $value)
{
    echo '<div class="form-group row" style="margin:10px 0 10px 0"><label for="' . $id . '" class="col-4 col-form-label">' . $label . '</label><div class="col-8"><select class="form-control" id="' . $id . '" name="' . $name . '" data-value="' . $value . '"></select></div></div>';
}

#Generate dtp for dob
# PARAM: value (Value of user current dob, get from SQL query)
function dob($value)
{
    echo '<div class="form-group row" style="margin:10px 0 10px 0"><label for="dob" class="col-4 col-form-label">Date of Birth</label><div class="col-8"><input type="date" class="form-control" id="dob" name="dob" max="" value="' . $value . '" required disabled></div></div>';
}

# FOR FOOD LIST
function foodItems($RUserID)
{
    echo '<div class="card-body toggle" id="fooditems" style="display:none"><div class="row"><div class="col-md-12"><h4>Food Items<button id="openCreateFood" data-toggle="modal" data-target="#createModal" class="btn btn-primary" style="float:right">Add New Food</button></h4><hr></div></div><div class="row"><div class="col-md-12">';
    include("conn.php");
    $sqlFoods = "SELECT FoodID, FoodImage, FoodName, Price FROM Food WHERE RUserID = '$RUserID' AND Deleted = 0";
    $result = mysqli_query($con, $sqlFoods);
    $itemcount = mysqli_num_rows($result);

    $count = 0;
    if ($itemcount != 0) {
        echo '<table id="foodItemtable">';
        echo '<tr><th>Image</th><th>Name</th><th>Price</th><th>Edit</th></tr>';
        while ($row = mysqli_fetch_array($result)) {
            if ($row['FoodImage'] == "") {
                echo '<tr><td style="text-align:center"><img src="assets\default_image.png" width="300px" data-toggle="modal" data-target="#uploadModal"></td>';
            } else {
                echo '<tr><td style="text-align:center"><img src="' . $row['FoodImage'] . '" width="300px" data-toggle="modal" data-target="#uploadModal"></td>';
            }
            echo '<td>' . $row['FoodName'] . '</td>';
            echo '<td>' . (($row['Price']) / 100) . '</td>';
            echo '<td><button id="openEditFood' . $count . '" class="btn btn-primary edit_button" data-toggle="modal" data-target="#editModal" data-name="' . $row['FoodName'] . '" data-price="' . $row['Price'] . '" data-id="' . $row['FoodID'] . '">Edit Food</button></td></tr>';
            $count++;
        }
    } else {
        echo '<div class="col-md-12" style="margin:10px 0px 30px 0px"><div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><img src="assets/no_item.svg" alt="No order placed today."width="400px" /><p style="padding: 20px 0px 0px 0px;">I don\'t see food!</p></div></div>';
    }

    echo '</table>';
}

# Upload Modal
function modal()
{
    echo '<div id="createModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Add New Food Item</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><form method="post" action="" enctype="multipart/form-data">Food Name: <input type="text" class="col-sm-12 form-control" name="foodname" id="addFoodName" placeholder="Food name (eg. Nasi Lemak)" value="" required><br>Price: <input type="text" class="col-sm-12 form-control" name="foodprice" id="addFoodPrice" placeholder="Price (eg. 5.99)" value="" required><br><label class="custom-upload">Image:<input class="col-sm-12" type="file" name="file" id="addfile" class="form-control" required></label><br><input type="button" class="col-sm-12 btn btn-info" value="Add New Food" id="addFood"></form></div></div></div></div>';
    echo '<div id="editModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Edit Food Item</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><form method="post" action="" enctype="multipart/form-data"><input type="hidden" id="editFoodID" value="">Food Name: <input type="text" class="col-sm-12 form-control" name="foodname" id="editFoodName" placeholder="Food name (eg. Nasi Lemak)" value="" required><br>Price: <input type="text" class="col-sm-12 form-control" name="foodprice" id="editFoodPrice" placeholder="Price (eg. 5.99)" value="" required><br><label class="custom-upload">Image:<input class="col-sm-12" type="file" name="file" id="editfile" class="form-control" required></label><br><input type="button" class="col-sm-12 btn btn-primary" style="margin:10px 0px" value="Edit" id="editFood"><input type="button" class="col-sm-12 btn btn-danger" value="Remove this Food Item" id="deleteFood"></form></div></div></div></div>';
    echo '<div id="uploadModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Upload Food Image</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><form method="post" action="" enctype="multipart/form-data"><label class="custom-upload"><input class="col-sm-12" type="file" name="file" id="uploadfile" class="form-control" required></label><br><input type="button" class="col-sm-12 btn btn-info" value="Upload" id="upload2"></form></div></div></div></div>';
}

# FOR DELIVERY PROFILE
function delivery_profile()
{
    echo '<div class="card-body toggle" id="profile" style="display:none"><div class="row"><div class="col-md-12"><h4>Your Statistics</h4><hr></div></div>';
    include("conn.php");
    $sqlstatistics = "SELECT COUNT(OrderID) As OrderCompleted, AVG(ORating) As AverageOrderRating FROM COrderDetails WHERE COrderDetails.DUserID = '" . $_SESSION['userid'] . "' AND OStatus = 4";
    $result = mysqli_query($con, $sqlstatistics);

    $text = ["Orders completed: ", "Average order ratings: "];
    while ($row = mysqli_fetch_array($result)) {
        for ($i = 0; $i < 2; $i++) {
            if ($row[$i] == 0 || $row[$i] == null) {
                echo "<p>" . $text[$i] . "0</p>";
            } else {
                echo "<p>" . $text[$i] . round($row[$i], 0);
                for ($j = 1; $j <= 5; $j++) {
                    if ($i != 0 && round($row[$i]) >= $j) {
                        echo '<span class="fa fa-star checked"></span>';
                    } elseif ($i != 0 && $row[$i] <= $j) {
                        echo '<span class="fa fa-star"></span>';
                    }
                }
                echo "</p>";
            }
        }
    }

    $sqldstatistics = "SELECT AVG(CDRating) As AverageRating FROM CRateD WHERE DUserID = '" . $_SESSION['userid'] . "'";
    $result2 = mysqli_query($con, $sqldstatistics);
    $row2 = mysqli_fetch_array($result2);

    if ($row2[0] == 0 || $row2[0] == null) {
        echo "<p>Average rating: No rating yet</p>";
    } else {
        echo "<p>Average rating: " . round($row2[0], 0);
        for ($i = 1; $i <= 5; $i++) {
            if (round($row2[0]) >= $i) {
                echo '<span class="fa fa-star checked"></span>';
            } elseif ($row2[0] <= $i) {
                echo '<span class="fa fa-star"></span>';
            }
        }
        echo "</p>";
    }
}


# Short functions for updating user details or changing password
if (isset($_POST['update'])) {
    if ($_SESSION['role'] == 1) {
        $updateProfile = $con->prepare("UPDATE Customer SET CName = ? , CNickname = ? , CGender = ? , CAccom = ? WHERE CUserID = ?");
        $updateProfile->bind_param("ssiis", $_POST['realname'], $_POST['nickname'], $_POST['gender'], $_POST['location'], $_SESSION['userid']);
        $updateProfile->execute();
    } elseif ($_SESSION['role'] == 2) {
        $updateProfile = $con->prepare("UPDATE Restaurant SET RName = ? , RNickname = ? , RDesc = ? , RCuisine = ? WHERE RUserID = ?");
        $updateProfile->bind_param("sssss", $_POST['restaurant'], $_POST['nickname'], $_POST['desc'], $_POST['cuisine'], $_SESSION['userid']);
        $updateProfile->execute();
    } elseif ($_SESSION['role'] == 3) {
        $updateProfile = $con->prepare("UPDATE Delivery SET DName = ? , DNickname = ? , DGender = ? , DTransport = ?, DLicense = ? WHERE DUserID = ?");
        $updateProfile->bind_param("ssiiss", $_POST['realname'], $_POST['nickname'], $_POST['gender'], $_POST['transport'], $_POST['license'], $_SESSION['userid']);
        $updateProfile->execute();
    }

    $updateEmail = $con->prepare("UPDATE Users SET Email = ? WHERE UserID = ?");
    $updateEmail->bind_param("ss", $_POST['email'], $_SESSION['userid']);
    $updateEmail->execute();

    echo "<script>alert('Profile edited.')</script>";
}

if (isset($_POST['changepassword'])) {
    $queryPassword = "SELECT Password FROM Users WHERE UserID = '" . $_SESSION['userid'] . "'";
    $queryPassword = $con->query($queryPassword);
    $hash = mysqli_fetch_array($queryPassword);

    if (password_verify($_POST['oldpassword'], $hash['Password'])) {
        $newPassword = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);

        $changePassword = $con->prepare("UPDATE Users SET Password = ? WHERE UserID = ?");
        $changePassword->bind_param("ss", $newPassword, $_SESSION['userid']);
        $changePassword->execute();

        echo "<script>alert('Password changed successfully.')</script>";
    } else {
        echo "<script>alert('Old password did not match.')</script>";
    }
}

if (isset($_POST['foodname']) && isset($_POST['foodprice'])) {
    include("conn.php");
    $editfood = $con->prepare("UPDATE Food SET FoodName = ? , Price = ? WHERE FoodID = ? AND RUserID = ?");
    $editfood->bind_param("siss", $_POST['foodname'], $_POST['foodprice'], $_POST['foodid'], $_SESSION['userid']);
    $editfood->execute();

    if (isset($_POST['add'])) {
        echo "Added food!";
    } else {
        echo "Updated food info!";
    }
}

if (isset($_POST['remove']) && isset($_POST['foodid'])) {
    try {
        #Hard delete won't work if it's foodID is referenced.
        include("conn.php");
        $deletefood = $con->prepare("DELETE FROM Food WHERE FoodID = ? AND RUserID = ?");
        $deletefood->bind_param("ss", $_POST['foodid'], $_SESSION['userid']);
        $deletefood->execute();
        throw new Exception();
    } catch (exception $e) {
        #Soft delete to mark it as deleted if hard delete doesn't work.
        $deletefood = $con->prepare("UPDATE Food SET Deleted = 1 WHERE FoodID = ? AND RUserID = ?");
        $deletefood->bind_param("ss", $_POST['foodid'], $_SESSION['userid']);
        $deletefood->execute();
    } finally {
        echo "Deleted ";
    }
}

#For delivery person get orders.
if (isset($_POST['getorders'])) {
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $today = date("Y-m-d");

    #Check which restaurant order is not assigned to anyone.
    $sqlgetrestaurant = "SELECT RUserID FROM COrderDetails WHERE ODate = '$today' AND (OStatus = 0 OR OStatus = 1) AND DUserID = '0' GROUP BY RUserID LIMIT 1";
    $result = mysqli_query($con, $sqlgetrestaurant);
    $count = mysqli_num_rows($result);

    if ($count == 0) {
        header("location:profile.php");
    } else {
        #Check the order(s) from first restaurant.
        $ruserid = mysqli_fetch_array($result)['RUserID'];

        $sqlgetorder = "SELECT OrderID FROM COrderDetails WHERE ODate = '$today' AND (OStatus = 0 OR OStatus = 1) AND DUserID = '0' AND RUserID = '$ruserid'";
        $result1 = mysqli_query($con, $sqlgetorder);
        $count = mysqli_num_rows($result1);

        while ($row = mysqli_fetch_array($result1)) {
            $sqlassignorder = $con->prepare("UPDATE COrderDetails SET DUserID = ? WHERE OrderID = ?");
            $sqlassignorder->bind_param("ss", $_SESSION['userid'], $row['OrderID']);
            $sqlassignorder->execute();
            header("location:profile.php");
        }
    }
}

#For delivery person to get more orders from same restaurant.
if (isset($_POST['getmoreorders'])) {
    include("conn.php");
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $today = date("Y-m-d");

    #Check which restaurant delivery person is assigned to
    $sqlgetrestaurant = "SELECT RUserID FROM COrderDetails WHERE ODate = '$today' AND DUserID = '" . $_SESSION['userid'] . "'";
    $result = mysqli_query($con, $sqlgetrestaurant);
    $ruserid = mysqli_fetch_array($result)['RUserID'];

    $sqlgetorder = "SELECT OrderID FROM COrderDetails WHERE ODate = '$today' AND (OStatus = 0 OR OStatus = 1) AND DUserID = '0' AND RUserID = '$ruserid'";
    $result1 = mysqli_query($con, $sqlgetorder);

    while ($row = mysqli_fetch_array($result1)) {
        $sqlassignorder = $con->prepare("UPDATE COrderDetails SET DUserID = ? WHERE OrderID = ?");
        $sqlassignorder->bind_param("ss", $_SESSION['userid'], $row['OrderID']);
        $sqlassignorder->execute();
        header("location:profile.php");
    }
}

#For delivery person to update delivery status to Picked Up.
if (isset($_POST['updatedeliver'])) {
    include("conn.php");
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $today = date("Y-m-d");

    #Check which restaurant delivery person is assigned to
    $sqlgetorder = "SELECT OrderID FROM COrderDetails WHERE ODate = '$today' AND OStatus = 1 AND DUserID = '" . $_SESSION['userid'] . "'";
    $result = mysqli_query($con, $sqlgetorder);

    while ($row = mysqli_fetch_array($result)) {
        $sqlassignorder = $con->prepare("UPDATE COrderDetails SET OStatus = 2 WHERE OrderID = ?");
        $sqlassignorder->bind_param("s", $row['OrderID']);
        $sqlassignorder->execute();
        header("location:profile.php");
    }
}

#For delivery person to update delivery status to On Transit.
if (isset($_POST['updatetransit'])) {
    include("conn.php");
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $today = date("Y-m-d");

    #Check which restaurant delivery person is assigned to
    $sqlgetorder = "SELECT OrderID FROM COrderDetails WHERE ODate = '$today' AND OStatus = 2 AND DUserID = '" . $_SESSION['userid'] . "'";
    $result = mysqli_query($con, $sqlgetorder);

    while ($row = mysqli_fetch_array($result)) {
        $sqlassignorder = $con->prepare("UPDATE COrderDetails SET OStatus = 3 WHERE OrderID = ?");
        $sqlassignorder->bind_param("s", $row['OrderID']);
        $sqlassignorder->execute();
        header("location:profile.php");
    }
}


# VERY BIG BRO THAT RUNS A LOT OF FUNCTIONS #

function bigBro($user_type)
{
    include("conn.php");

    date_default_timezone_set("Asia/Kuala_Lumpur");
    $today = date("Y-m-d");

    if ($user_type == 1) {  #Customer
        sideTabsAndTop($user_type);

        # Part 1
        $sqlcurrentorder = "SELECT OrderID, OStatus, DUserID FROM COrderDetails WHERE CUserID = '" . $_SESSION['userid'] . "' AND ODate = '" . $today . "' AND OStatus != 4";
        $getresult = mysqli_query($con, $sqlcurrentorder);
        $getdata = mysqli_fetch_assoc($getresult);
        $count = mysqli_num_rows($getresult);

        if ($count >= 1) {
            $currentStatus = $getdata['OStatus'];
            $currentOrderID = $getdata['OrderID'];
            $currentDeliver = $getdata['DUserID'];

            generateOrderEntry(1, 0, $currentStatus, $today, $currentOrderID, 0, $currentDeliver);
        } elseif ($count == 0) {
            echo '<div class="col-md-12" style="margin:10px 0px 30px 0px"><h4>Current Order</h4><div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><img src="assets/no_current_order.svg" alt="No order placed today."width="400px" /><p style="padding: 20px;">Hey, there\'s no current order placed today. (' . $today . ')</p></div></div>';
        }

        mysqli_close($con);

        echo '<div class="col-md-12"><h4>Past Orders</h4>';

        include("conn.php");
        $sqlpastorders = "SELECT OStatus, ODate, OrderID, RUserID, DUserID FROM COrderDetails WHERE CUserID = '" . $_SESSION['userid'] . "' AND OStatus = 4";
        $getresultC = mysqli_query($con, $sqlpastorders);
        $count = mysqli_num_rows($getresultC);
        $currentpastorder = 0;

        if ($count == 0) {
            echo '<div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><img src="assets/no_current_order.svg" alt="No order placed today."width="400px" /><p style="padding: 20px;">You have not placed any order.</p></div>';
        } else {
            while ($rows = mysqli_fetch_array($getresultC)) {
                $currentStatus = $rows['OStatus'];
                $currentODate = $rows['ODate'];
                $currentOrderID = $rows['OrderID'];
                $currentRestaurant = $rows['RUserID'];
                $currentDeliver = $rows['DUserID'];

                generateOrderEntry(2, $currentpastorder, $currentStatus, $currentODate, $currentOrderID, $currentRestaurant, $currentDeliver);
                $currentpastorder++;
            }
        }

        mysqli_close($con);
        echo '</div></div></div>';
        editProfile($user_type);
        echo '</div></div>';    //Close tags generated by sideTabsAndTop
    } elseif ($user_type == 2) {
        sideTabsAndTop($user_type);

        $sqlcurrentordersummary = "SELECT Food.FoodID, FoodName, SUM(Amount) AS Total FROM COrderDetails JOIN COrder ON COrderDetails.OrderID = COrder.OrderID JOIN Food ON Food.FoodID = COrder.FoodID WHERE COrderDetails.RUserID = '" . $_SESSION['userid'] . "' AND ODate = '" . $today . "' AND OStatus = 0 GROUP BY Food.FoodID";
        $getresult = mysqli_query($con, $sqlcurrentordersummary);
        $count = mysqli_num_rows($getresult);

        if ($count >= 1) {
            echo '<div class="col-md-12" style="margin:10px 0px 10px 0px"><table id="orderFoodtable"><tr><th colspan="2" style="text-align:center"><h3>Summary of Orders [' . $today . ']</h3></th></tr><tr><th>Food Name</th><th>Amount to Prepare</th></tr>';
            while ($row = mysqli_fetch_assoc($getresult)) {
                echo '<tr><td>' . $row['FoodName'] . '</td><td>' . $row['Total'] . '</td></tr>';
            }
            echo '</table></div>';
        } elseif ($count == 0) {
            echo '<div class="col-md-12" style="margin:10px 0px 30px 0px"><h3 style="text-align:center">Summary of Orders [' . $today . ']</h3><div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><img src="assets/no_current_order.svg" alt="No order placed today."width="400px" /><p style="padding: 20px;">No order being placed to your restaurant yet. (' . $today . ')</p></div></div>';
        }

        $sqlcurrentorder = "SELECT COrder.OrderID, COrderDetails.OStatus, CORderDetails.DUserID, DName FROM COrderDetails JOIN COrder ON COrderDetails.OrderID = COrder.OrderID JOIN Food ON Food.FoodID = COrder.FoodID JOIN Delivery ON COrderDetails.DUserID = Delivery.DUserID WHERE COrderDetails.RUserID = '" . $_SESSION['userid'] . "' AND ODate = '" . $today . "' AND (OStatus = 0 OR OStatus = 1) GROUP BY OrderID";
        $getresult = mysqli_query($con, $sqlcurrentorder);
        $count = mysqli_num_rows($getresult);

        if ($count >= 1) {
            echo '<div class="col-md-12" style="margin:10px 0px 10px 0px"><table id="orderFoodtable"><tr><th colspan="4" style="text-align:center"><h3>All Orders [' . $today . ']</h3></th></tr><tr><th>OrderID</th><th>Food Name</th><th>Amount</th><th>Status</th></tr>';
            while ($row = mysqli_fetch_assoc($getresult)) {
                $orderID = $row['OrderID'];
                $orderStatus = $row['OStatus'];

                $sqlrowspan = "SELECT * FROM COrderDetails JOIN COrder ON COrderDetails.OrderID = COrder.OrderID JOIN Food ON Food.FoodID = COrder.FoodID WHERE COrderDetails.OrderID = '" . $orderID . "' AND ODate = '" . $today . "' ";
                $getresult2 = mysqli_query($con, $sqlrowspan);
                $rowspan = mysqli_num_rows($getresult2);

                echo "<tr><td rowspan='$rowspan' style='text-align:center'>" . $row['OrderID'] . "</td>";
                if ($orderStatus == 0) {
                    $btn_style = "primary";
                    $btn_text = "Complete Order";
                    $btn_delivery = "";
                } elseif ($orderStatus == 1) {
                    $btn_style = "secondary";
                    $btn_text = "Order Completed";
                    if ($row['DUserID'] != "0") {
                        $btn_delivery = "Delivery Person: " . $row['DName'];
                    } elseif ($row['DUserID'] == "0") {
                        $btn_delivery = "Delivery Person: Assigning";
                    }
                }
                $statusButton = '<td style="text-align:center" rowspan="' . $rowspan . '"><a class="statusButton" data-orderID="' . $orderID . '" data-status="' . $orderStatus . '"><div class="btn btn-' . $btn_style . '">' . $btn_text . '</div></a><p>' . $btn_delivery . '</p></td></tr>';
                while ($row2 = mysqli_fetch_assoc($getresult2)) {
                    echo "<td>" . $row2['FoodName'] . "</td>";
                    echo "<td style=\"text-align:center\">" . $row2['Amount'] . "</td>";
                    if (isset($statusButton)) {
                        echo $statusButton;
                        unset($statusButton);
                    }
                    echo "</tr>";
                }
            }
            echo '</table></div>';
        } elseif ($count == 0) {
            echo '<div class="col-md-12" style="margin:10px 0px 30px 0px"><h3 style="text-align:center">All Orders [' . $today . ']</h3><div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><p> None </p></div></div>';
        }
        echo '</div></div>';
        mysqli_close($con);

        foodItems($_SESSION['userid']);

        echo '</div></div></div>';
        editProfile($user_type);
        echo '</div></div>';    //Close tags generated by sideTabsAndTop


    } elseif ($user_type == 3) {
        sideTabsAndTop($user_type);

        //Check if delivery person has order on hand
        include("conn.php");
        $sqlorderonhand = "SELECT OrderID, CName, OStatus FROM COrderDetails JOIN Customer ON COrderDetails.CUserID = Customer.CUserID WHERE DUserID = '" . $_SESSION['userid'] . "' AND (OStatus = 0 OR OStatus = 1 OR OStatus = 2)";
        $result = mysqli_query($con, $sqlorderonhand);
        $count = mysqli_num_rows($result);

        if ($count == 0) {
            //Check if delivery person has On Transit orders
            include("conn.php");
            $sqlorderontransit = "SELECT OrderID, COrderDetails.CUserID, CName, OStatus FROM COrderDetails JOIN Customer ON COrderDetails.CUserID = Customer.CUserID WHERE DUserID = '" . $_SESSION['userid'] . "' AND OStatus = 3";
            $result2 = mysqli_query($con, $sqlorderontransit);
            $count = mysqli_num_rows($result2);

            if ($count == 0) {
                echo '<div><p>You don\'t have any order on hand. Click Assign Order(s) button to get orders from a restaurant.</p><form method="post" action="profile.php"><input type="submit" class="btn btn-primary" name="getorders" value="Assign Order(s)"></form></div>';
            } else {
                echo '<div class="col-md-12" style="margin:10px 0px 10px 0px"><table id="deliverFoodtable"><tr><th colspan="3" style="text-align:center"><h3>Orders On Transit</h3></th></tr><tr><th>OrderID</th><th>Ordered By</th><th>Status</th></tr>';
                while ($row = mysqli_fetch_assoc($result2)) {
                    echo "<tr><td style='text-align:center'>" . $row['OrderID'] . "</td>";
                    echo "<td>" . $row['CName'] . "</td>";
                    echo '<td style="text-align:center"><a class="statusButton" data-orderID="' . $row['OrderID'] . '" data-status="' . $row['OStatus'] . '"><div class="btn btn-primary">Confirm Deliver</div></a></td></tr>';
                }
                echo '</table></div>';
            }
        } else {
            $sqlrestaurant = "SELECT Restaurant.RUserID, RName, RAddress FROM COrderDetails JOIN Restaurant ON COrderDetails.RUserID = Restaurant.RUserID WHERE DUserID = '" . $_SESSION['userid'] . "' AND (OStatus = 0 OR OStatus = 1 OR OStatus = 2) AND ODate = '" . $today . "'";
            $result2 = mysqli_query($con, $sqlrestaurant);
            $count = mysqli_num_rows($result2);

            while ($row = mysqli_fetch_array($result2)) {
                $rname = $row['RName'];
                $raddress = $row['RAddress'];
            }

            echo "<div style='padding:10px'><div><p><b>Restaurant name:</b> $rname</p><p><b>Address:</b><span id='address'> $raddress</span></p></div><table id='deliveryordertable'><tr><th>OrderID</th><th>Ordered By</th><th>Status</th></tr>";
            while ($rows = mysqli_fetch_array($result)) {
                switch ($rows['OStatus']) {
                    case 0:
                        $status = "Preparing";
                        $notyet = 0;
                        break;
                    case 1:
                        $status = "Done";
                        $deliverOK = 0;
                        break;
                    case 2:
                        $status = "Done";
                        $transitOK = 0;
                        break;
                }
                echo "<tr><td>" . $rows['OrderID'] . "</td><td>" . $rows['CName'] . "</td><td>" . $status . "</td></tr>";
            }

            echo '</table></div><div style="padding:30px"><div><iframe class="embed-responsive-item" id="map" height="300px" width="400px" style="display:block"></iframe></div><div style="padding:10px">';

            if ((isset($notyet) || $count == 1) && !isset($transitOK)) {
                echo '<p>Click Assign Order(s) to get more orders related to the restaurant.</p><form method="post" action="profile.php" style="margin: 10px 5px 10px 5px"><input type="submit" class="btn btn-primary" name="getmoreorders" value="Assign Order(s)"></form>';
            }
            if (isset($deliverOK) && !isset($notyet) && !isset($transitOK)) {
                echo '<form method="post" action="profile.php" style="margin: 10px 5px 10px 5px"><input type="submit" class="btn btn-success" name="updatedeliver" value="Collect Order"></form>';
            }
            if (isset($transitOK) && !isset($deliverOK) && !isset($notyet)) {
                echo '<form method="post" action="profile.php" style="margin: 10px 5px 10px 5px"><input type="submit" class="btn btn-success" name="updatetransit" value="Confirm Everything Collected"></form>';
            }
            echo '</div></div>';
        }

        echo '</div></div>';
        delivery_profile();

        echo '</div>';
        editProfile($user_type);
        echo '</div></div></div></div>';    //Close tags generated by sideTabsAndTop
    }
}
