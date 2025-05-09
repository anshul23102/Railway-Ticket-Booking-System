<?php 

session_start();
include('DBConnection.php');
include('Details.php');

if (!isset($_SESSION["uname"])) {
    header("location: ./index.php?logout=1");
}

include("header2.php");
$train_no = '';

if (isset($_GET['show'])) {
    $pnr = trim($_GET['pnr']);

    $pnr_file = file("pnr_numbers.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $pnr_found = false;
    $pnr_status = "";
    $pnr_date = "";

    foreach ($pnr_file as $line) {
        list($stored_pnr, $status, $date) = explode("|", trim($line));
        if ((string)$stored_pnr === (string)$pnr) {
            $pnr_found = true;
            $pnr_status = $status;
            $pnr_date = $date;
            break;
        }
    }

    if ($pnr_found) {
        $sql = "SELECT t.train_no, t.train_name, s.source, s.destination, ti.ticket_no,
                       ti.phno, ti.status, s.depart_time, s.arrival_time, ti.date, ti.username
                FROM train t, station s, ticket ti
                WHERE t.train_no = s.train_no 
                  AND s.station_no = ti.station_no 
                  AND ti.ticket_no = '$pnr'";

        $result = $conn->query($sql);
    } else {
        echo "<script> alert('Invalid PNR Number'); window.history.back(); </script>";
    }
}

if (isset($_POST['cticket'])) {
    $pnr = $_GET['pnr'];
    $train_no = $_SESSION['train_no'];

    if (isset($_SESSION['update'])) {
        echo "<script> alert('Ticket already cancelled'); </script>";
    } else {
        $sql = "UPDATE ticket SET status = 'cancelled' WHERE ticket_no = '$pnr'";
        if ($conn->query($sql) === true) {
            $sql5 = "UPDATE train SET seat_avail = seat_avail + 1 WHERE train_no = '$train_no'";
            if ($conn->query($sql5) === true) {
                unset($_SESSION['train_no']);
                $_SESSION['update'] = true;
            } else {
                echo $conn->error;
            }
            echo "<script> alert('Ticket cancelled'); </script>";
        } else {
            echo $conn->error;
        }
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
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>
    <style>
        .bg-black {
            background-color: black;
        }
    </style>
</head>
<body class="alert-light">
<div class="container">
    <form name="payForm" onsubmit="return(pnrvalid());" class="m-5 p-5 border bg-light" action="" method="get">
        <div class="row">
            <div class="col-12">
                <h4 class="navbar-brand text-primary">PNR Status/Cancel Ticket:</h4>
            </div>
            <div class="col-8">
                <input class="form-control" type="text" placeholder="Enter PNR Number" name="pnr" id="pnr" maxlength="5">
                <span id="er_pnr" class="text-red"></span>
            </div>
            <div class="col-4">
                <input type="submit" class="btn btn-dark text-light" value="Get Status" name="show">
            </div>
        </div>
    </form>
</div>

<div class="container">
    <table class="table table-bordered text-center">
        <?php 
        if (isset($_GET['show']) || isset($_POST['cticket'])) {
            if (isset($result) && $result->num_rows > 0) { ?>
                <tr class="table-primary">
                    <th>PNR NO.</th>
                    <th>Status</th>
                    <th>Train No.</th>
                    <th>Train Name</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Date</th>
                    <th>Mobile No.</th>
                    <th>Booked by</th>
                    <th>Action</th>
                </tr>
                <?php while ($data = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $data['ticket_no']; ?></td>
                        <td class="text-danger text-bold"><?php echo $data['status']; ?></td>
                        <td><?php echo $data['train_no']; ?></td>
                        <?php $_SESSION['train_no'] = $data['train_no']; ?>
                        <td><?php echo $data['train_name']; ?></td>
                        <td><?php echo $data['source']; ?></td>
                        <td><?php echo $data['destination']; ?></td>
                        <td><?php echo $data['depart_time']; ?></td>
                        <td><?php echo $data['arrival_time']; ?></td>
                        <td><?php echo $data['date']; ?></td>
                        <td><?php echo $data['phno']; ?></td>
                        <td><?php echo $data['username']; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="trainno" value="<?php echo $data['train_no']; ?>">
                                <button name="cticket" class="text-light btn btn-dark hvr-grow">Cancel Ticket</button>
                            </form>
                        </td>
                    </tr>
                <?php } 
            } else {
                echo "<script> alert('Invalid PNR'); </script>";
            }
        } ?>
    </table>
</div>

<?php include('footer.html'); ?>
</body>
</html>

<script type="text/javascript">
    function pnrvalid() {
        var pnr = document.payForm.pnr.value;
        var numbers = /^[0-9]+$/;

        if (pnr === "") {
            document.getElementById("er_pnr").innerHTML = "ENTER PNR NO";
            document.getElementById("pnr").style = "border-color: #f00;box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25)";
            return false;
        }
        if (!pnr.match(numbers)) {
            document.getElementById("er_pnr").innerHTML = "Enter only numbers";
            document.getElementById("pnr").style = "border-color: #f00;box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25)";
            return false;
        }
        if (pnr.length != 5) {
            document.getElementById("er_pnr").innerHTML = "PNR must be 5 digits";
            document.getElementById("pnr").style = "border-color: #f00;box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25)";
            return false;
        }

        document.getElementById("er_pnr").innerHTML = "";
        document.getElementById("pnr").style = "border:none;box-shadow:none";
        return true;
    }
</script>