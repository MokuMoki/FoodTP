<?php
include("session_check.php");

$orderID = $_POST['orderID'];
$orderStatus = $_POST['status'];

if ($_SESSION['role'] == 2) {
    include("conn.php");
    $RUserID = $_SESSION['userid'];
    $sql = "SELECT OrderID FROM COrderDetails WHERE OrderID = '$orderID' AND OStatus = $orderStatus AND RUserID = '$RUserID'";
    $sql = $con->query($sql);
    $exist = mysqli_fetch_array($sql);

    if ($exist['OrderID'] != "") {
        if ($orderStatus == 0) {
            $orderStatus++;
            $sql = $con->prepare("UPDATE COrderDetails SET OStatus = ? WHERE OrderID = ?");
            $sql->bind_param("is", $orderStatus, $orderID);
            $sql->execute();
            echo "Updated status. Assigning delivery person.";
        } elseif ($orderStatus == 1) {
            echo "Status of order already updated.";
        }
    } else {
        echo "Order doesn't exist.";
    }
} elseif ($_SESSION['role'] == 3) {
    include("conn.php");
    $DUserID = $_SESSION['userid'];
    $sql = "SELECT OrderID FROM COrderDetails WHERE OrderID = '$orderID' AND OStatus = $orderStatus AND DUserID = '$DUserID'";
    $sql = $con->query($sql);
    $exist = mysqli_fetch_array($sql);

    if ($exist['OrderID'] != "") {
        if ($orderStatus == 3) {
            $orderStatus++;
            $sql = $con->prepare("UPDATE COrderDetails SET OStatus = ? WHERE OrderID = ?");
            $sql->bind_param("is", $orderStatus, $orderID);
            $sql->execute();
            echo "Order delivery complete.";
        } elseif ($orderStatus == 4) {
            echo "Order already completed.";
        }
    } else {
        echo "Order doesn't exist.";
    }
} else {
    echo "Illegal action.";
}

mysqli_close($con);
