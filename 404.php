<!DOCTYPE html>

<head lang="en">
    <title>40wO4</title>

    <!-- Bootstrap pre-requisite -->
    <?php include('head.php') ?>
</head>
<style>
    .background {
        background-image: url("assets/404.jpg");
    }
</style>

<body>
    <?php include('navbar.php') ?>
    <div style="transform: translate(0px, 100px)">
        <div style="text-align:center">
            <div><img src="assets/404.jpg"><br /><button onclick="notThis()">Gwoo bwacc??</button></div>
        </div>
        <div style="text-align:center; margin:40px 0px 10px 0px">
            <p>The page you requested doesn't exist.</p><button onclick="goBack()" class="btn btn-warning">Go back to last page.</button>
        </div>
    </div>
    <div style="transform: translate(0px, 150px)"><?php include('footer.php') ?></div>
</body>

</html>

<script>
    function goBack() {
        window.history.back();
    }

    function notThis() {
        alert("Nyot this bakaa!")
    }
</script>