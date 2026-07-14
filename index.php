<?php 
    session_start();
    include('Details.php');
    include('DBConnection.php'); 

    // Unset update flag for PNR status
    if (isset($_SESSION['update'])) {
        unset($_SESSION['update']);
    }

    // Show login/logout alerts
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<script> alert('You are logged in'); </script>";
    } else if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        echo "<script> alert('You are logged out'); </script>";
    }

    // Conditionally include headers
    if (isset($_SESSION['uname'])) {
        $uname = $_SESSION['uname'];
        if (file_exists('header2.php')) {
            include('header2.php');
        } else {
            echo "<!-- header2.php not found -->";
        }
    } else {
        if (file_exists('navbar.php')) {
            include('navbar.php');
        } else {
            echo "<!-- navbar.php not found -->";
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <title>IR</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="asset/css/animate.css">
    <link rel="stylesheet" href="asset/css/hover-min.css">
    <link rel="stylesheet" href="asset/css/custom.css">

    <!-- AOS Animation CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>

    <style>
        #bg-custom { background-color: rgba(2,2,2,0.8); }
        #m-cust { margin-right: 250px; margin-top: 60px; }
        .bg-black { background-color: black; }
        .bg-img {
            background-image: url('asset/img/7.jpg');
            background-size: 100%;
            max-width: 1300px;
            min-height: 700px;
        }
        @media(max-width: 400px){
            .bg-img {
                background-image: url('asset/img/5.jpg');
                background-size: auto;
                background-repeat: no-repeat;
            }
        }
        .bg-img2 {
            background-image:url('asset/img/5.jpg');
            background-size: 100%;
        }
        .pnr {
            background-color: white;
            color: black;
            padding-top: 10px;
            box-shadow: 2px 2px 18px 10px #222;
            border-radius: 2px;
        }
        .fs-1 { font-size: 42px; font-family: Tempus Sans ITC; margin-top: 50px; }
        .fs-2 { font-size: 18px; font-family: Yu Gothic Light; font-weight: lighter; margin-bottom: 50px; }
        .main-name {
            font-size: 50px;
            font-family: Arial Rounded MT Bold;
            margin-top: 0px;
            background-color: rgba(2,2,2,0.2);
            border-radius: 5px;
            width: 560px;
            padding-left: 50px;
        }
    </style>
</head>
<body>
    <div class="row bg-img text-light">
        <div class="col-12 col-sm-12 col-md-4 offset-1">
            <!-- Add AOS animation here -->
            <div class="row pnr m-5 text-center" data-aos="fade-down">
                <div class="col-12 mt-3">
                    <span><img src="asset/img/logo/rail_icon.png"></span><br>
                    <span class="fs-1">BOOK</span>
                    <span class="fs-2">YOUR TICKET</span>
                </div>
                <div class="col-12 mt-4">
                    <form action="./train_list.php" method="post">
                        <div class="input-group">   
                            <input type="text" name="src" class="form-control hvr-shadow" placeholder="From*" required> 
                        </div>
                        <br>
                        <div class="input-group">
                            <input type="text" name="dest" class="form-control hvr-shadow" placeholder="To*" required>
                        </div>  
                        <br>
                        <div class="input-group">
                            <input type="date" name="date" class="form-control hvr-shadow" required>
                            <div class="input-group-append">
                                <span class="input-group-text text-dark">
                                    <img src="asset/img/logo/cal.png" width="20" height="20">
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="input-group">
                            <select name="class" class="custom-select hvr-shadow">
                                <option value="ALL">All Classes</option>
                                <option value="AC">AC</option>
                                <option value="SL">Sleeper(SL)</option>
                            </select>
                        </div>
                        <br>
                        <div class="input-group">
                            <input class="btn text-light bg-blue btn-block hvr-shadow" type="submit" value="Find Trains">
                        </div><br>  
                    </form>
                </div>
            </div>                    
        </div>

        <div class="sm-hide col-sm-6">
            <div class="text-left main-name">
                <span>INDIAN RAILWAYS</span>
            </div>
        </div>
    </div>

    <div class="jumbotron bg-secondary"></div>
    <div class="jumbotron bg-secondary"></div>
    <div class="jumbotron bg-secondary"></div>

    <?php 
        if (file_exists('footer.html')) {
            include('footer.html'); 
        } else {
            echo "<!-- footer.html not found -->";
        }
    ?>

    <!-- AOS Animation JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
