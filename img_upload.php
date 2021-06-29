<?php
include("session_check.php");
if ($_SESSION['role'] == 2) {
    $RUserID = $_SESSION['userid'];

    // file name
    $filename = $_FILES['file']['name'];

    // Location
    $dir = "uploads/" . $RUserID . "/";
    $location = $dir . $filename;

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    // file extension
    $file_extension = pathinfo($location, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);

    // Valid image extensions
    $image_ext = array("jpg", "png", "jpeg");

    $response = 0;
    if (in_array($file_extension, $image_ext)) {
        if (file_exists($location)) unlink($location);

        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
            $response = $location;

            $foodid = $_POST['foodid'];
            $newname = $dir . $foodid . "." . $file_extension;
            rename($location, $newname);

            include("conn.php");
            $exist = "SELECT FoodID FROM Food WHERE FoodID = $foodid";
            $result = mysqli_query($con, $exist);
            $count = mysqli_num_rows($result);

            if ($count == 0) {
                $imagedir = $con->prepare("INSERT INTO Food (FoodID, FoodImage, RUserID) VALUES (?, ?, ?)");
                $imagedir->bind_param("sss", $foodid, $newname, $_SESSION['userid']);
                $imagedir->execute();
            } else {
                $imagedir = $con->prepare("UPDATE Food SET FoodImage = ? WHERE RUserID = ? AND FoodID = ?");
                $imagedir->bind_param("sss", $newname, $_SESSION['userid'], $foodid);
                $imagedir->execute();
            }
        }
    }
    echo $response;
} else {
    echo 0;
}
