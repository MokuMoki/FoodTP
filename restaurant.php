<!DOCTYPE html>

<head lang="en">
    <link rel="stylesheet" href="css/restaurant.css">
    <title>Browse Restaurants</title>

    <!-- Bootstrap pre-requisite -->
    <?php include("head.php") ?>
</head>

<body>
    <?php include("navbar.php");
    if (isset($_SESSION['role']) && $_SESSION['role'] != 1) {
        header("Location: profile.php");
    } elseif (!isset($_SESSION['userid'])) {
        header("Location: login.php");
    }
    ?>
    <div style="transform: translate(0px, 100px)" class="gallery col-sm-12">
        <div class="col-sm-12" style="text-align:center">
            <?php
            function genFilter($id, $RName)
            {
                include("conn.php");
                $sqlRating = "SELECT AVG(CRRating) As avgrating, COUNT(CRRating) As ratingcount FROM Restaurant JOIN CRateR ON Restaurant.RUserID = CRateR.RUserID WHERE Restaurant.RUserID = '" . $id . "'";
                $result = mysqli_query($con, $sqlRating);
                $rating = mysqli_fetch_array($result);

                if ($rating['avgrating'] != "") {
                    switch ($rating['avgrating']) {
                        case ($rating['avgrating'] >= 4):
                            $badge = "success";
                            break;
                        case ($rating['avgrating'] >= 2):
                            $badge = "warning";
                            break;
                        case ($rating['avgrating'] >= 0):
                            $badge = "danger";
                            break;
                    }
                } else {
                    $badge = "secondary";
                }

                echo '<button class="btn btn-primary filter-button" data-filter="' . $id . '">' . $RName . ' <div class="badge badge-' . $badge . '">' . round($rating['avgrating'], 1) . '/5 (' . $rating['ratingcount'] . ')</div></button>';
                mysqli_free_result($result);
            }

            include("conn.php");
            $sqlRestaurant = "SELECT Restaurant.RUserID, RName FROM Food JOIN Restaurant ON Food.RUserID = Restaurant.RUserID WHERE Deleted = 0 GROUP BY RName";
            $result = mysqli_query($con, $sqlRestaurant);

            while ($row = mysqli_fetch_array($result)) {
                genFilter($row['RUserID'], $row['RName']);
            }
            ?>

        </div>
        <div class="card col-xs-11" style="padding:20px; margin-top:30px">
            <section class="row d-flex justify-content-center">
                <div class="filter">
                    <div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><img src="assets/pick_restaurant.svg" alt="Awaiting selection" width="800px" class="img-fluid" />
                        <p style="padding: 20px 0px 0px 0px;">Pick a restaurant from buttons on top.</p>
                    </div>
                </div>
                <?php
                function genFoodCard($id, $foodid, $FoodImage, $FoodName, $Price, $existCart)
                {
                    if ($FoodImage == "") {
                        $FoodImage = "assets/default_image.png";
                    }
                    if($existCart == 1){
                        $btn_color = "secondary";
                        $btn_text = "In cart";
                    } else {
                        $btn_color = "primary";
                        $btn_text = "Add to cart";
                    }
                    echo '<div class="gallery_product card col-sm-3 col-lg-2 filter ' . $id . '" style="margin: 10px; padding: 5px; display: none;"><div class="container image_box"><img src="' . $FoodImage . '" class="image_inner"></div><div style="margin: 10px"><h4 style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis; padding: 2px 0px 2px 0px">' . $FoodName . '</h4><p>RM' . $Price . '</p><form action="cart.php" method="post"><input type="hidden" name="cartfoodid" value="' . $foodid . '"><button class="btn btn-'.$btn_color.' col-sm-12 align-items-end"></form>'.$btn_text.'</button></div></div>';
                }

                $sqlCartFood = "SELECT FoodID FROM Cart WHERE CUserID = '".$_SESSION['userid']."'";
                $result = mysqli_query($con, $sqlCartFood);
                $existFood = [];
                while ($row = mysqli_fetch_array($result)) {
                    $existFood[] = $row['FoodID'];
                }
                mysqli_free_result($result);

                $sqlFood = "SELECT Restaurant.RUserID, FoodID, FoodImage, FoodName, Price FROM Food JOIN Restaurant ON Food.RUserID = Restaurant.RUserID WHERE Deleted = 0";
                $result = mysqli_query($con, $sqlFood);

                while ($row = mysqli_fetch_array($result)) {
                    if (in_array($row['FoodID'], $existFood)) {
                        genFoodCard($row['RUserID'], $row['FoodID'], $row['FoodImage'], $row['FoodName'], ($row['Price'] / 100), 1);
                    } else {
                        genFoodCard($row['RUserID'], $row['FoodID'], $row['FoodImage'], $row['FoodName'], ($row['Price'] / 100), 0);
                    }
                }

                ?>
            </section>
        </div>
    </div>
    <div style="transform: translate(0px, 170px);"><?php include("footer.php") ?></div>
</body>

<script>
    $(document).ready(function() {

        $(".filter-button").click(function() {
            var value = $(this).attr('data-filter');

            if (value == "all") {
                $('.filter').show('1000');
            } else {
                $(".filter").not('.' + value).hide('3000');
                $('.filter').filter('.' + value).show('3000');
            }
        });

        if ($(".filter-button").removeClass("active")) {
            $(this).removeClass("active");
        }
        $(this).addClass("active");
    });
</script>