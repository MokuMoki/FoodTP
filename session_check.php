<?php
    if (!isset($_SESSION['userid'])){
        session_start();
    }
        
    if (isset($_SESSION['userid']))
    {
        if($_SESSION['keepsession'] == 'False')
        {
            if($_SESSION['session_expire'] < time())
            {
                header("location: login.php");
                session_destroy();
            }
            else {
                $_SESSION['session_expire'] = time() + 3600;
            }
        } 
    } else {
        header("location: login.php");
    }
?>