<?php 
    session_start();
    include('DBConnection.php');

    if(!isset($_SESSION["adminuname"])){
        header("location: ./Adminlogin.php?logout=1"); 
    }

    include("adminheader2.html");

    $train_no = '';
    $count = 1;
    $result = null;

    if (isset($_GET['show'])) {
        if (!empty(trim($_GET['train_no']))) {
            $train_no = trim($_GET['train_no']);
            $sql1 = "SELECT * FROM station s, train t WHERE s.train_no = t.train_no AND t.train_no = '$train_no'";
            $result = $conn->query($sql1);
        } else {
            echo "<script>alert('Please enter a train number');</script>";
        }
    }

    if (isset($_POST['update'])) {
        if (isset($_POST['trainno']))
            $train_no = $_POST['trainno'];
        $station_no = $_POST['station_no'];

        $train_name  = ucwords($_POST['trainname']);
        $seat  = $_POST['seat'];
        $class  = $_POST['class'];
        $src  = ucwords($_POST['src']);
        $dest  = ucwords($_POST['dest']);
        $depart  = $_POST['depart'];
        $arr  = $_POST['arr'];
        $fare  = $_POST['fare'];

        $duration = round(abs(strtotime($depart) - strtotime($arr)) / 3600, 1);

        function updateQuery($conn, $sql){
            if ($conn->query($sql) == true){
                echo "<script>alert('Train Data Updated');</script>";
            } else {
                echo $conn->error;
            }
        }

        $sql2 = "UPDATE train SET train_no = '$train_no', train_name = '$train_name', seat_avail = '$seat', class = '$class' WHERE train_no = '$train_no'";
        updateQuery($conn, $sql2);

        $sql3 = "UPDATE station SET source = '$src', destination = '$dest', fare = '$fare', arrival_time = '$arr', depart_time = '$depart', duration = '$duration', train_no = '$train_no' WHERE station_no = '$station_no' AND train_no = '$train_no'";
        updateQuery($conn, $sql3);
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
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>
    <style>
        .logo { border-radius: 1000px; }
        div.shadow-cust { width: 230px; background-color: #DCEEFF; }
        .shadow-cust { box-shadow: 3px 3px 5px 0px #333; }
        i.fa-circle { box-shadow:inset 0px 0px 3px 0px #222; border-radius: 10px; }
        .text-main h5, .text-main {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            font-family: serif;
        }
    </style>
</head>
<body class="bg-img">
    <div class="row">
        <div class="col-12 col-sm-3">    
            <?php include("adminmenu.html"); ?>
        </div>
        <div class="col-12 col-sm-9">
            <form name="payForm" class="m-5 p-5 border bg-light" action="" method="get">
                <div class="row">
                    <div class="col-12">
                        <h4 class="navbar-brand text-primary">Train Number:</h4>
                    </div>
                    <div class="col-8">
                        <input class="form-control" type="text" placeholder="Enter Train Number" name="train_no" id="train" maxlength="5">
                        <span id="er_train" class="text-red"></span>
                    </div>
                    <div class="col-4">      
                        <input type="submit" class="btn btn-dark text-light" value="Get Details" name="show">
                    </div>
                </div>
            </form>

            <?php 
            if ($result && $result->num_rows > 0) {
                while($data = $result->fetch_assoc()) {
            ?>
            <form action="" method="post" name="train">
                <div class="row bg-light m-3 p-4 border-radius">
                    <input type="hidden" name="station_no" value="<?php echo $data['station_no']; ?>">

                    <div class="col-sm-6 col-md-3"><h5>Train No:</h5></div>
                    <div class="col-sm-6 col-md-3">
                        <input class="form-control" type="text" value="<?php echo $data['train_no']; ?>" disabled>
                        <input type="hidden" name="trainno" value="<?php echo $data['train_no']; ?>">
                    </div>

                    <div class="col-sm-6 col-md-3"><h5>Train Name:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="trainname" value="<?php echo $data['train_name']; ?>"></div>

                    <div class="col-sm-6 col-md-3"><h5>Seat Availability:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="seat" value="<?php echo $data['seat_avail']; ?>"></div>

                    <div class="col-sm-6 col-md-3"><h5>Class:</h5></div>
                    <div class="col-sm-6 col-md-3">
                        <select class="form-control" name="class">
                            <option value="<?php echo $data['class']; ?>"><?php echo $data['class']; ?></option>
                            <option value="ALL">All Classes</option>
                            <option value="AC">AC</option>
                            <option value="SL">Sleeper(SL)</option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3"><h5>Source:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="src" value="<?php echo $data['source']; ?>"></div>

                    <div class="col-sm-6 col-md-3"><h5>Destination:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="dest" value="<?php echo $data['destination']; ?>"></div>

                    <div class="col-sm-6 col-md-3"><h5>Departure Time:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="depart" value="<?php echo $data['depart_time']; ?>"></div>

                    <div class="col-sm-6 col-md-3"><h5>Arrival Time:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="arr" value="<?php echo $data['arrival_time']; ?>"></div>

                    <div class="col-sm-6 col-md-3"><h5>Fare:</h5></div>
                    <div class="col-sm-6 col-md-3"><input class="form-control" type="text" name="fare" value="<?php echo $data['fare']; ?>"></div>

                    <div class="col-sm-6 col-md-3 offset-1">
                        <input class="btn btn-success" type="submit" value="Update Details" name="update">
                    </div>
                </div>
            </form>
            <?php $count++; } // while ends ?>
            <?php 
            } elseif (isset($_GET['show']) && !empty($_GET['train_no']) && (!$result || $result->num_rows == 0)) {
                echo "<script>alert('Train Not Found');</script>";
            }
            ?>
        </div>
    </div>
</body>
</html>
