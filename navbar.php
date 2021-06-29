<?php
if (!isset($_SESSION['userid'])) {
  session_start();
}

date_default_timezone_set("Asia/Kuala_Lumpur");
$now = date("H");

switch ((int) $now) {
  case ($now < 5 && $now >= 0):
    $time = "Good night, ";
    break;
  case ($now < 12 && $now >= 5):
    $time = "Good morning, ";
    break;
  case ($now < 18 && $now >= 12):
    $time = "Good afternoon, ";
    break;
  case ($now <= 23 && $now >= 18):
    $time = "Good evening, ";
    break;
}

if (isset($_SESSION['userid'])) {
  if ($_SESSION['role'] == 1) {
    $username = $_SESSION['username'];
    $link = "profile.php";
    $text = $time . $username;
    $browsefood = '<li class="nav-item"><a class="nav-link" href="restaurant.php">Browse Food</a></li>';

    include("conn.php");
    $sqlordercount = "SELECT FoodID FROM Cart WHERE CUserID = '" . $_SESSION['userid'] . "'";
    $result = mysqli_query($con, $sqlordercount);
    $count = mysqli_num_rows($result);

    $cart = '<a class=nav-link" href="cart.php"><span class="fa-stack has-badge" data-count="' . $count . '"><i style="" class="fa fa-shopping-cart fa-stack-2x cart"></i></span></a>';
  } else {
    $username = $_SESSION['username'];
    $link = "profile.php";
    $text = $time . $username;
    $browsefood = '';
    $cart = "";
  }
} else {
  $link = "login.php";
  $text = "Sign In";
  $cart = "";
  $browsefood = '<li class="nav-item"><a class="nav-link" href="restaurant.php">Browse Food</a></li>';
}

echo '<link rel="stylesheet" href="css/navbar.css">
  <nav class="navbar navbar-expand-lg navbar-default fixed-top">
      <a class="navbar-brand" href="about.php">
        
      </a>
      <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto flex-nowrap">
          ' . $browsefood . '
          <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="' . $link . '">' . $text . '</a>
          </li>
          <li class="nav-item">
            ' . $cart . '
          </li>
        </ul>
      </div>
    </nav>';
