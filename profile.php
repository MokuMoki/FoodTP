<!DOCTYPE html>

<head lang="en">
    <link rel="stylesheet" href="css/profile.css">
    <title>Your Profile</title>

    <!-- Bootstrap pre-requisite -->
    <?php include("head.php");
    include("session_check.php");
    include("profile_core.php"); ?>
</head>

<!-- DON'T BE FOOLED! Almost all the UI elements are generated backend using profile_core.php! -->

<body>
    <?php include("navbar.php") ?>
    <div class="container" style="transform: translate(0px, 100px);">
        <div class="row">
            <?php bigBro($_SESSION['role']) ?>
        </div>
    </div>
    <div style="transform: translate(0px, 170px);">
        <?php include("footer.php") ?>
    </div>
    <?php if ($_SESSION['role'] == 2) {
        modal();
    } ?>
</body>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/password_check.js"></script>
<script type="text/javascript" src="scripts/profile.js"></script>

</html>