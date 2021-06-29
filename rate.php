<?php
include("session_check.php");
include 'conn.php';

$type = $_POST['type'];
$rated = $_POST['rated'];
$rating = $_POST['rating'];
$userID = $_SESSION['userid'];

switch ($type) {
    case 1:
        $orderID = $_POST['val'];
        break;
    case 2:
        $RuserID = $_POST['val'];
        break;
    case 3:
        $DuserID = $_POST['val'];
        break;
}

switch ($rated) {
    case 1:
        switch ($type) {
            case 1:
                $rateSQL = "UPDATE COrderDetails SET ORating = $rating WHERE OrderID = '$orderID'";
                break;
            case 2:
                $rateSQL = "UPDATE CRateR SET CRRating = $rating WHERE CUserID = '$userID' AND RUserID = '$RuserID'";
                break;
            case 3:
                $rateSQL = "UPDATE CRateD SET CDRating = $rating WHERE CUserID = '$userID' AND DUserID = '$DuserID'";
                break;
        }
        break;
    case 0:
        switch ($type) {
            case 1:
                $rateSQL = "UPDATE COrderDetails SET ORating = $rating WHERE OrderID = '$orderID'";
                break;
            case 2:
                $rateSQL = "INSERT INTO CRateR (CUserID, RUserID, CRRating) VALUES ('$userID', '$RuserID', $rating)";
                break;
            case 3:
                $rateSQL = "INSERT INTO CRateD (CUserID, DUserID, CDRating) VALUES ('$userID', '$DuserID', $rating)";
                break;
        }
        break;
}

if(mysqli_query($con, $rateSQL)){
    if($rating == 1){
        echo 'Rated 1 star.';
    } else {
        echo 'Rated '.$rating.' stars.';
    }
} else {
    echo 'Error in giving rating.';
}
mysqli_close($con);
