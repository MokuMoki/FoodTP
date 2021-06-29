<?php
    session_start();
    header("location: about.php");
    session_destroy();
?>