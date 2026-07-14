<?php 
session_start();
include('DBConnection.php');

if (!isset($_SESSION["adminuname"])) {
    header("location: ./Adminlogin.php?logout=1");
    exit();
}

include("adminheader2.html");

$ticketData = null;
$passengerData = [];

if (isset($_GET['show'])) {
    $pnr = $_GET['pnr'];

    // Step 1: Basic ticket info (safe from joins failing)
    $sql = "SELECT * FROM ticket WHERE ticket_no = '$pnr'";
    $ticketResult = $conn->query($sql);

    if ($ticketResult->num_rows > 0) {
        $ticketData = $ticketResult->fetch_assoc();

        // Step 2: Optional joins (if data available)
        $sqlTrain = "SELECT t.train_no, t.train_name, s.source, s.destination, s.depart_time, s.arrival_time
                     FROM train t
                     JOIN station s ON t.train_no = s.train_no
                     WHERE s.station_no = '{$ticketData['station_no']}'";

        $trainResult = $conn->query($sqlTrain);
        if ($trainResult->num_rows > 0) {
            $ticketData = array_merge($ticketData, $trainResult->fetch_assoc());
        }

        // Step 3: Get passengers
        $sqlPassengers = "SELECT * FROM passanger WHERE ticket_no = '$pnr'";
        $result1 = $conn->query($sqlPassengers);
        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                $passengerData[] = $row;
            }
        }
    } else {
        echo "<script>alert('Invalid PNR');</script>";
    }
}
?>


<!doctype html>
<html lang="en">
<head>
	<title>IR</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">

    <!-- :start of optional css-->

    <!-- font-awesome for icon -->
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">

    <!-- animation css -->
    <link rel="stylesheet" href="asset/css/animate.css">

    <!-- hover css animations -->
    <link rel="stylesheet" href="asset/css/hover-min.css">

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">

    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>
    <style>




        .logo{
            border-radius: 1000px;
        }
        div.shadow-cust{
            width: 230px;
            background-color: #DCEEFF;
       }
       .shadow-cust{
            box-shadow: 3px 3px 5px 0px #333;
       }
       i.fa-circle{
            box-shadow:inset 0px 0px 3px 0px #222;
            border-radius: 10px;  
       }


    </style>

</head>
<body class="bg-img">
    <div class="row">
        <div class="col-12 col-sm-3">    
    	   <?php include("adminmenu.html"); ?>
        </div>
        <div class="col-12 col-sm-8">
            <div class="container">
                <form name="payForm" onsubmit="return(pnrvalid());" class="m-5 p-5 border bg-light" action="" method="get">
                <div class="row">
                    <div class="col-12">
                        <h4 class="navbar-brand text-primary">PNR Status/cancel ticket:</h4>
                    </div>
                    <div class="col-8">
                        <input class="form-control" type="text" placeholder="Enter PNR Number" name="pnr" id="pnr" maxlength="5">
                        <span id="err_pnr" class="text-red"></span>
                    </div>
                    <div class="col-4">      
                        <input type="submit" class="btn btn-dark text-light" value="Get Status" name="show">
                    </div>
                </div>
                </form>
            </div>

        </div>
            <!-- table for trains records -->
            <div class="container">
                <table class="table table-hover bg-light text-center">
                    <?php 
                    if (isset($_GET['show'])) {

                      if($result->num_rows > 0){ ?>
                    <tr class="table-primary">
                        <th>PRN NO.</th>
                        <th>Status</th>
                        <th>No. Of Passangers</th>
                        <th>Train No.</th>
                        <th>Train Name</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Date</th>
                        <th>Mobile No.</th>
                        <th>Booked by</th>
                    </tr>
                     <?php   while($data = $result->fetch_assoc()){
                     ?>
                    <tr>
                        <td><?php echo $data['ticket_no']; ?></td>
                        <td class="text-danger text-bold"><?php echo $data['status']; ?></td>
                        <td><?php echo $data['passangers']; ?></td>
                        <td><?php echo $data['train_no']; ?></td>
                        <td><?php echo $data['train_name']; ?></td>
                        <td><?php echo $data['source']; ?></td>
                        <td><?php echo $data['destination']; ?></td>
                        <td><?php echo $data['depart_time']; ?></td>
                        <td><?php echo $data['arrival_time']; ?></td>
                        <td><?php echo $data['date']; ?></td>
                        <td><?php echo $data['phno']; ?></td>
                        <td><?php echo $data['username']; ?></td>
                    </tr>
                    <?php 
                    } //while ends
                    } // if ends
                    else{
                        echo "<script> alert('invalid pnr'); </script>";
                    }
                    } //show ends 
                 ?>
                </table>
            </div>
    

            <!-- table for passanger records -->
            <div class="container">
                <table class="table table-hover bg-light text-center">
                    <?php 
                    if (isset($_GET['show'])) {

                      if($result1->num_rows > 0){ ?>
                    <tr class="table-primary">
                        <th>PNO</th>
                        <th>Name</th>
                        <th>Age </th>
                        <th>Gender </th>
                        <th>Seat No.</th>
                    </tr>
                     <?php   while($data1 = $result1->fetch_assoc()){
                     ?>
                    <tr>
                        <td><?php echo $data1['pno']; ?></td>
                        <td><?php echo $data1['p_name']; ?></td>
                        <td><?php echo $data1['p_age']; ?></td>
                        <td><?php echo $data1['p_gender']; ?></td>
                        <td><?php echo $data1['seat_no']; ?></td>
                    </tr>
                    <?php 
                    } //while ends
                    } // if ends
                    else{
                        echo "<script> alert('invalid pnr'); </script>";
                    }
                    } //show ends 
                 ?>
                </table>
            </div>


        <!-- </div> -->
    </div>
</body>
</html>


