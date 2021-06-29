<!DOCTYPE html>

<head lang="en">
  <link rel="stylesheet" href="css/about.css">
  <link rel="stylesheet" href="css/accordion.css">
  <title>About FoodTP</title>

  <!-- Bootstrap pre-requisite -->
  <?php include 'head.php'; ?>
</head>

<body>
  <?php include 'navbar.php' ?>

  <section id="aboutheader">
    <video id="aboutvid" autoplay="autoplay" muted="muted" loop="loop">
      <source src="assets/hero.mp4" type="video/mp4">
    </video>

    <div class="container h-100">
      <div class="d-flex h-100 text-center align-items-center">
        <div class="w-100 text-white" ng-app="typeSet">
          <h1 class="display-3" style="text-shadow: 2px 2px #4c96d7">FoodTP -To <span class="skill" ng-controller="typeSetControllerLoc">{{view.type}}</span></h1>
          <a href="restaurant.php">
            <button>Browse Menu</button>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section id="aboutfeatures">
    <div class="container">
      <div id="desc" class="row">
        <h3 class="col-md-12">Getting your lunch, minus the crowd</h3>
        <p class="col-md-12">Getting your lunch is more of a hassle in APU, not anymore with FoodTP. We figure out the logistic, you figure out what food to eat.</p>
      </div>

      <div class="row">
        <div id="box" class="col">
          <div id="icon">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 172 172" style=" fill:#000000;">
              <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                <path d="M0,172v-172h172v172z" fill="none"></path>
                <g fill="#3498db">
                  <path d="M86,8.0625c-43.043,0 -77.9375,34.8945 -77.9375,77.9375c0,43.043 34.8945,77.9375 77.9375,77.9375c43.043,0 77.9375,-34.8945 77.9375,-77.9375c0,-43.043 -34.8945,-77.9375 -77.9375,-77.9375zM86,13.4375c40.07062,0 72.5625,32.49188 72.5625,72.5625c0,40.07062 -32.49188,72.5625 -72.5625,72.5625c-40.07062,0 -72.5625,-32.49188 -72.5625,-72.5625c0,-40.07062 32.49188,-72.5625 72.5625,-72.5625zM85.93701,24.10876c-22.94309,-0.01308 -44.38545,12.77978 -55.078,33.70398c-12.20663,23.90531 -7.5845,52.96206 11.43762,71.91162c1.06963,0.91375 2.65626,0.84984 3.65332,-0.13647c0.99706,-0.99706 1.05023,-2.5837 0.13648,-3.65332c-22.05631,-22.04825 -22.05631,-57.7999 0,-79.84814c12.83819,-12.77638 31.0014,-18.64294 48.8894,-15.79956c1.48081,0.24188 2.8708,-0.77627 3.11267,-2.25708c0.24188,-1.48081 -0.77627,-2.87605 -2.25708,-3.11792c-3.31201,-0.53817 -6.61682,-0.80123 -9.89441,-0.8031zM106.20874,27.90381c-0.47602,0.06013 -0.93441,0.24599 -1.32275,0.5564c-0.78744,0.61812 -1.16713,1.62527 -0.97632,2.60352c0.17738,0.97556 0.89091,1.77249 1.84766,2.06811c1.92157,0.72563 3.7992,1.55829 5.63745,2.48279c0.37625,0.17469 0.79877,0.2677 1.20728,0.2677c1.23893,-0.01075 2.31864,-0.85874 2.60352,-2.06811c0.28218,-1.20669 -0.28118,-2.44588 -1.38574,-3.01294c-2.01562,-1.00781 -4.07366,-1.91081 -6.18335,-2.6875c-0.45687,-0.20425 -0.95171,-0.27009 -1.42773,-0.20996zM122.16577,36.88489c-0.64802,0.1038 -1.25884,0.44113 -1.69018,0.99207c-0.87075,1.11263 -0.73293,2.69951 0.30444,3.64282c1.79525,1.41631 3.50761,2.95146 5.13354,4.56665c22.03481,22.04556 22.03481,57.78158 0,79.82715c-0.76593,0.65037 -1.10246,1.68019 -0.87134,2.66651c0.24188,0.97556 1.00857,1.73701 1.98413,1.97888c0.98631,0.23112 2.01588,-0.08965 2.677,-0.85559c24.12569,-24.1445 24.123,-63.25955 0,-87.40674c-1.77375,-1.77375 -3.65189,-3.4437 -5.61646,-4.99707c-0.58856,-0.38834 -1.27312,-0.51848 -1.92114,-0.41467zM70.12695,56.43225c-0.78912,-0.07584 -1.58815,0.19959 -2.1626,0.7821l-10.75,10.75c-0.91375,1.06963 -0.85009,2.65626 0.14697,3.65332c0.98631,0.98631 2.58395,1.05022 3.64282,0.13647l6.18335,-6.15186v44.58521h-5.375c-1.48081,0 -2.6875,1.20669 -2.6875,2.6875c0,1.48081 1.20669,2.6875 2.6875,2.6875h16.125c1.48081,0 2.6875,-1.20669 2.6875,-2.6875c0,-1.48081 -1.20669,-2.6875 -2.6875,-2.6875h-5.375v-51.0625c0.01075,-1.09112 -0.65088,-2.0766 -1.65869,-2.49854c-0.25195,-0.10548 -0.51382,-0.16893 -0.77685,-0.19421zM105.06445,56.43225c-0.78912,-0.07584 -1.58814,0.19959 -2.1626,0.7821l-10.75,10.75c-0.91375,1.06963 -0.85009,2.65626 0.14697,3.65332c0.98631,0.98631 2.58395,1.05022 3.64282,0.13647l6.18335,-6.15186v44.58521h-5.375c-1.48081,0 -2.6875,1.20669 -2.6875,2.6875c0,1.48081 1.20669,2.6875 2.6875,2.6875h16.125c1.48081,0 2.6875,-1.20669 2.6875,-2.6875c0,-1.48081 -1.20669,-2.6875 -2.6875,-2.6875h-5.375v-51.0625c0.01075,-1.09112 -0.65088,-2.0766 -1.65869,-2.49854c-0.25195,-0.10548 -0.51382,-0.16893 -0.77685,-0.19421zM112.19263,129.95532c-0.98631,-0.0215 -1.89931,0.51256 -2.38306,1.37524c-0.473,0.86 -0.45083,1.91199 0.08398,2.75049l2.6875,4.65064c0.4515,0.87075 1.35475,1.42748 2.34107,1.43823c0.98631,0.01075 1.8993,-0.51256 2.38306,-1.37524c0.48375,-0.86269 0.45058,-1.92274 -0.07349,-2.75049l-2.6875,-4.65064c-0.46225,-0.87075 -1.36525,-1.42748 -2.35156,-1.43823zM60.14331,130.0603c-0.34803,-0.04602 -0.70312,-0.0257 -1.0498,0.06299c-0.69337,0.18812 -1.28295,0.64945 -1.6377,1.27026l-2.6875,4.65064c-0.52406,0.83044 -0.55723,1.89049 -0.07349,2.75049c0.48375,0.86 1.39674,1.38599 2.38306,1.37524c0.98631,-0.01075 1.88957,-0.56748 2.34107,-1.43823l2.6875,-4.65064c0.36819,-0.61812 0.46351,-1.35374 0.28345,-2.04712c-0.18006,-0.68263 -0.63114,-1.28295 -1.24927,-1.6377c-0.30906,-0.17872 -0.64928,-0.28991 -0.99732,-0.33594zM72.06384,135.30933c-1.03528,0.14664 -1.92807,0.89284 -2.22034,1.96314l-1.39624,5.16504c-0.28219,0.93525 -0.04249,1.96238 0.65088,2.66651c0.68263,0.70413 1.70219,0.97455 2.64551,0.71386c0.94331,-0.26069 1.66927,-1.03007 1.88965,-1.98413l1.40674,-5.18603c0.37087,-1.42706 -0.48358,-2.89645 -1.91064,-3.27539c-0.35945,-0.09473 -0.72046,-0.11187 -1.06555,-0.06299zM99.89941,135.39856c-0.33888,-0.04417 -0.68993,-0.02582 -1.03931,0.06824c-1.40556,0.37894 -2.24633,1.79609 -1.92114,3.2124l1.39624,5.18604c0.39775,1.43781 1.87957,2.29009 3.31738,1.88965c1.43781,-0.38969 2.28983,-1.87957 1.90015,-3.31738l-1.42773,-5.16504c-0.31444,-1.032 -1.20896,-1.74137 -2.22559,-1.8739zM86,137.0625c-1.48081,0 -2.6875,1.20669 -2.6875,2.6875v5.375c0,1.48081 1.20669,2.6875 2.6875,2.6875c1.48081,0 2.6875,-1.20669 2.6875,-2.6875v-5.375c0,-1.48081 -1.20669,-2.6875 -2.6875,-2.6875z"></path>
                </g>
              </g>
            </svg>
          </div>
          <p>Order before 11am</p>
        </div>

        <div id="box" class="col">
          <div id="icon">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 172 172" style=" fill:#000000;">
              <g transform="">
                <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                  <path d="M0,172v-172h172v172z" fill="none"></path>
                  <g id="original-icon" fill="#3498db">
                    <g id="surface1">
                      <path d="M37.625,16.125v139.75h96.75v-139.75zM48.375,26.875h75.25v118.25h-75.25zM59.125,43v10.75h10.75v-10.75zM80.625,43v10.75h32.25v-10.75zM64.5,59.125c-2.96045,0 -5.375,2.41455 -5.375,5.375c0,2.96045 2.41455,5.375 5.375,5.375c2.96045,0 5.375,-2.41455 5.375,-5.375c0,-2.96045 -2.41455,-5.375 -5.375,-5.375zM80.625,59.125v10.75h32.25v-10.75zM59.125,86v10.75h10.75v-10.75zM80.625,86v10.75h32.25v-10.75zM64.5,102.125c-2.96045,0 -5.375,2.41455 -5.375,5.375c0,2.96045 2.41455,5.375 5.375,5.375c2.96045,0 5.375,-2.41455 5.375,-5.375c0,-2.96045 -2.41455,-5.375 -5.375,-5.375zM80.625,102.125v10.75h32.25v-10.75zM59.125,118.25v10.75h10.75v-10.75zM80.625,118.25v10.75h32.25v-10.75z"></path>
                    </g>
                  </g>
                  <path d="" fill="none"></path>
                  <path d="" fill="none"></path>
                </g>
              </g>
            </svg>
          </div>
          <p>Large and growing menu</p>
        </div>

        <div id="box" class="col">
          <div id="icon">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 172 172" style=" fill:#000000;">
              <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                <path d="M0,172v-172h172v172z" fill="none"></path>
                <g fill="#3498db">
                  <path d="M36.98672,11.46667c-1.50787,0 -2.80396,1.06819 -3.09062,2.55312c-1.38747,7.26987 -5.22943,28.18901 -5.22943,37.58021c0,22.93333 17.2,28.66667 17.2,28.66667v71.66667c0,4.7472 3.8528,8.6 8.6,8.6c4.7472,0 8.6,-3.8528 8.6,-8.6v-71.66667c0,0 17.2,-5.73333 17.2,-28.66667c0,-9.3912 -3.84196,-30.31034 -5.22942,-37.58021c-0.28667,-1.48493 -1.58276,-2.55312 -3.09063,-2.55312c-1.7372,0 -3.14661,1.40941 -3.14661,3.14661v33.29141c0,2.04107 -1.65424,3.69531 -3.69531,3.69531c-1.88627,0 -3.46652,-1.41739 -3.67292,-3.29219l-3.78489,-34.05286c-0.17774,-1.58813 -1.51889,-2.78828 -3.12422,-2.78828h-0.05599h-0.05599c-1.60533,0 -2.94649,1.20561 -3.12422,2.79948l-3.7849,34.05287c-0.2064,1.86907 -1.78665,3.28099 -3.67292,3.28099c-2.04107,0 -3.69531,-1.65425 -3.69531,-3.69531v-33.29141c0,-1.7372 -1.40941,-3.14661 -3.14661,-3.14661zM131.86667,11.46667c-1.96233,0.00197 -3.78735,1.00742 -4.8375,2.6651c0,0 -18.09583,26.3169 -18.09583,54.66823v11.35469c0,3.7668 1.36955,7.40809 3.85208,10.24609l7.61458,7.39063v54.14192c0,4.7472 3.8528,8.6 8.6,8.6c4.7472,0 8.6,-3.8528 8.6,-8.6v-134.73333c0,-3.16643 -2.5669,-5.73333 -5.73333,-5.73333z"></path>
                </g>
              </g>
            </svg>
          </div>
          <p>Cooked by well known restaurants</p>
        </div>
      </div>
    </div>
  </section>

  <section id="aboutsteps">
    <div class="container">
      <div id="desc" class="row">
        <h3 class="col-md-12">How does it work?</h3>
        <p class="col-md-12">Three steps, from restaurant to your bench or Modesto table. Order before 11am and get your food during lunch break.</p>
      </div>
      <div id="timeline">
        <div class="timechunk left">
          <div id="content">
            <h2>Step 1</h2>
            <p>You order on web before 11am.</p>
            <img src="assets/step1.jpg" alt="Step 1">
          </div>
        </div>
        <div class="timechunk right">
          <div id="content">
            <h2>Step 2</h2>
            <p>On 11am, system will compile list of orders and send to restaurant for batch preparation.</p>
            <img src="assets/step2.jpg" alt="Step 2">
          </div>
        </div>
        <div class="timechunk left">
          <div id="content">
            <h2>Step 3</h2>
            <p>Once completed, system will assign delivery person to send batches of food.</p>
            <img src="assets/step3.jpg" alt="Step 3">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="aboutqna">
    <div class="container">
      <div id="desc" class="row">
        <h3 class="col-md-12">What's your hidden agenda?</h3>
        <p class="col-md-12">None! I am just an APU student doing this for assignment. If you have any questions, please refer to the QnA below.</p>
      </div>


      <div class="col-md-12">
        <button class="accordion">What really is FoodTP?</button>
        <div class="panel">
          <p>It is an assignment with a theme of food delivering service. Rest assure we will not deliver to you.</p>
        </div>

        <button class="accordion">Where do you deliver?</button>
        <div class="panel">
          <p>The dynamic header states that we deliver to almost all locations where APU accomondate, plus a few extra locations. Please don't believe in that as we do not deliver at all.</p>
        </div>

        <button class="accordion">Is there a minimum order or delivery charge?</button>
        <div class="panel">
          <p>Since we don't deliver, there won't be any minimum order or delivery charge. However we do appreciate donations.</p>
        </div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.js"></script>
<script type="text/javascript" src="scripts/typeset_effect.js"></script>
<script type="text/javascript" src="scripts/accordion.js"></script>