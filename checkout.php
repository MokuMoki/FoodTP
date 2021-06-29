<?php
include("session_check.php");
if (isset($_POST['checkout']) && $_POST['checkout'] == 1) {
    session_start();
    include("conn.php");

    $RUserID = $_POST['ruserid'];
    $sqlCartFood = "SELECT FoodID, CartAmount FROM Cart WHERE CUserID = '" . $_SESSION['userid'] . "'";
    $result = mysqli_query($con, $sqlCartFood);
    $cartFood = [];
    $cartAmount = [];
    while ($row = mysqli_fetch_array($result)) {
        $cartFood[] = $row['FoodID'];
        $cartAmount[] = $row['CartAmount'];
    }

    $orderID = md5(microtime(true) + rand());
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $today = date("Y-m-d");

    foreach ($cartFood as $i) {
        $count = 0;
        $foodtocreateorder = $con->prepare("INSERT INTO COrder (OrderID, FoodID, Amount) VALUES (?, ?, ?)");
        $foodtocreateorder->bind_param("ssi", $orderID, $i, $cartAmount[$count]);
        $foodtocreateorder->execute();
        $count++;
    }

    $createorder = $con->prepare("INSERT INTO COrderDetails (OrderID, OStatus, ODate, CUserID, RUserID, DUserID, ORating) VALUES (?, 0, ?, ?, ?, '0', 0)");
    $createorder->bind_param("ssss", $orderID, $today, $_SESSION['userid'], $RUserID);
    $createorder->execute();

    $checkout = $con->prepare("DELETE FROM Cart WHERE CUserID = ?");
    $checkout->bind_param("s", $_SESSION['userid']);
    $checkout->execute();
} elseif (isset($_POST['checkout']) && $_POST['checkout'] == 2) {
    include("conn.php");
    $updateamount = $con->prepare("UPDATE Cart SET CartAmount = ? WHERE FoodID = ? AND CUserID = ?");
    $updateamount->bind_param("iss", $_POST['amount'], $_POST['foodid'], $_SESSION['userid']);
    $updateamount->execute();
}
