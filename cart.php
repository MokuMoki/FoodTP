<!DOCTYPE html>

<head lang="en">
    <link rel="stylesheet" href="css/cart.css">
    <title>Cart</title>

    <!-- Bootstrap pre-requisite -->
    <?php include("head.php");
    include("session_check.php"); ?>
</head>

<body>
    <?php include("navbar.php");

    if (isset($_POST['cartfoodid'])) {
        $foodid = $_POST['cartfoodid'];

        include("conn.php");
        $sqlcart = "SELECT CUserID FROM Cart WHERE CUSerID = '" . $_SESSION['userid'] . "'";
        $result = mysqli_query($con, $sqlcart);
        $cartitems = mysqli_num_rows($result);

        if ($cartitems == 0) {
            $insertOK = 1;
        } else {
            $sqlqueryrestaurant = "SELECT RUserID FROM Food JOIN Cart ON Food.FoodID = Cart.FoodID WHERE CUSerID = '" . $_SESSION['userid'] . "'";
            $result1 = mysqli_query($con, $sqlqueryrestaurant);
            $restaurant = mysqli_fetch_array($result1)['RUserID'];

            $sqlqueryfood = "SELECT FoodName FROM Food JOIN Restaurant ON Food.RUserID = Restaurant.RUserID WHERE Restaurant.RUserID = '" . $restaurant . "' AND Food.FoodID = '" . $_POST['cartfoodid'] . "'";
            echo $sqlqueryfood;
            $result2 = mysqli_query($con, $sqlqueryfood);
            $row = mysqli_num_rows($result2);

            if ($row == 0) {
                $insertOK = 0;
            } else {
                $insertOK = 1;
            }
        }

        if ($insertOK == 1) {
            $addtocart = $con->prepare("INSERT INTO Cart (CUserID, FoodID, CartAmount) VALUES (?, ?, 1)");
            $addtocart->bind_param("ss", $_SESSION['userid'], $foodid);
            $addtocart->execute();
            header("Location: cart.php");
        } else {
            echo '<script>alert("You may not add food from different restaurant."); window.location="restaurant.php";</script>';
        }
    }

    if (isset($_POST['removefoodID'])) {
        include("conn.php");
        $deletefood = $con->prepare("DELETE FROM Cart WHERE FoodID = ? AND CUserID = ?");
        $deletefood->bind_param("ss", $_POST['removefoodID'], $_SESSION['userid']);
        $deletefood->execute();
        header("Location: cart.php");
    }

    ?>
    <div style="transform: translate(0px, 50px)">
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Food Cart</h1>
                <h4>Adjust the Amount before you checkout.</h4>
            </div>
        </section>
        <div class="container mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <?php
                        function cartFoodList($FoodID, $FoodName, $FoodImage, $FoodPrice, $CartAmount)
                        {
                            echo '<tr>
                                    <td><img src="' . $FoodImage . '" style="max-width:120px"/> </td>
                                    <td style="min-width:300px">' . $FoodName . '</td>
                                    <td><input class="form-control amount" type="number" data-foodID="' . $FoodID . '" data-price="' . $FoodPrice . '" value="' . $CartAmount . '" min="1" max="9"/></td>
                                    <td class="text-right price" id="' . $FoodID . '">RM<p>' . ($FoodPrice * $CartAmount) . '</p></td>
                                    <td class="text-right" data-foodID="' . $FoodID . '"><button class="btn btn-sm btn-danger cartremove"><i class="fa fa-trash"></i></button></td>
                                </tr>';
                        }
                        include("conn.php");
                        $sqlCartFood = "SELECT Cart.FoodID, FoodName, FoodImage, Price, Cart.CartAmount, RUserID FROM Cart JOIN Food ON Cart.FoodID = Food.FoodID WHERE CUserID = '" . $_SESSION['userid'] . "'";
                        $result = mysqli_query($con, $sqlCartFood);
                        $rowcount = mysqli_num_rows($result);

                        if ($rowcount != 0) {
                            echo '<table class="table table-striped"><thead><tr><th scope="col"> </th><th scope="col">Product</th><th scope="col" class="text-center">Quantity</th><th scope="col" class="text-right">Price</th><th> </th></tr></thead><tbody>';
                            $price = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                if (!isset($ruserid)) {
                                    $ruserid = $row['RUserID'];
                                }
                                cartFoodList($row['FoodID'], $row['FoodName'], $row['FoodImage'], ($row['Price'] / 100), $row['CartAmount']);
                                $price += ($row['Price'] / 100 * $row['CartAmount']);
                            }
                            echo '<tr><td colspan="4"><p style="float:right"><b>Total</b></p></td><td>RM<p id="total">' . $price . '</p></td></tr></tbody></table></div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-success col-sm-6" id="checkout" data-ruserid="' . $ruserid . '" style="float:right">Checkout</button>
                            </div>';
                        } elseif ($rowcount == 0) {
                            echo '<div class="col-md-12" style="padding:10px 0px 20px 0px; text-align:center;"><img src="assets/pick_restaurant.svg" alt="Awaiting selection" width="800px" class="img-fluid" /><p>There\'s nothing in your cart.</p></div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div style="transform: translate(0px, 170px);"><?php include("footer.php") ?></div>
</body>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $('.cartremove').click(
            function() {
                var foodID = $(this).parent().attr("data-foodID");
                console.log(foodID);

                if (confirm("Are you sure you want to remove this food from your cart?")) {
                    $.post("cart.php", {
                            removefoodID: foodID
                        })
                        .done(function(data) {
                            location.reload(true);
                        });;
                } else {
                    // Do nothing!
                }
            });

        $('#checkout').click(
            function() {
                if (confirm("Are you sure you want to checkout now?")) {
                    var ruserid = $('#checkout').attr("data-ruserid");
                    console.log(ruserid);
                    $.post("checkout.php", {
                            checkout: 1,
                            ruserid: ruserid
                        })
                        .done(function(data) {
                            location.reload(true);
                        });;
                } else {
                    // Do nothing!
                }
            });

        $('.amount').click(
            function() {
                var price = $(this).attr("data-price");
                var amount = $(this).val();
                var foodid = $(this).attr("data-foodID");

                var newprice = price * amount;

                $('#' + foodid).children().html(newprice.toFixed(2));

                $.post("checkout.php", {
                        checkout: 2,
                        foodid: foodid,
                        amount: amount
                    })
                    .done(function(data) {
                        
                    });;

                var amounts = $(".amount");

                var total = 0;
                for (var i = 0; i < amounts.length; i++) {
                    total = total + (($(amounts[i]).attr("data-price")) * ($(amounts[i]).val()));
                }

                $('#total').html(total.toFixed(2));
            });
    });
</script>