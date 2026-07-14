<?php
session_start();
include('DBConnection.php');

if (!isset($_SESSION["uname"])) {
    header("location: ./index.php?logout=1");
    exit();
}

include("header2.php");

$uname = $_SESSION["uname"];
$ticket = null;
$passengers = null;
$message = "";

if (isset($_POST['cticket']) && isset($_POST['pnr'])) {
    $pnr = mysqli_real_escape_string($conn, $_POST['pnr']);
    $ticket_result = $conn->query("SELECT train_no, status FROM ticket WHERE ticket_no = '$pnr' AND username = '$uname'");

    if ($ticket_result && $ticket_result->num_rows > 0) {
        $ticket_data = $ticket_result->fetch_assoc();
        if ($ticket_data['status'] === 'cancelled') {
            $message = "Ticket is already cancelled.";
        } else {
            $passenger_count_result = $conn->query("SELECT COUNT(*) AS total FROM passanger WHERE ticket_no = '$pnr'");
            $passenger_count = $passenger_count_result->fetch_assoc()['total'];
            $conn->query("UPDATE ticket SET status = 'cancelled' WHERE ticket_no = '$pnr' AND username = '$uname'");
            $conn->query("UPDATE train SET seat_avail = seat_avail + $passenger_count WHERE train_no = '{$ticket_data['train_no']}'");
            $message = "Ticket cancelled successfully.";
        }
    } else {
        $message = "PNR not found for your account.";
    }
}

if (isset($_GET['show']) && isset($_GET['pnr'])) {
    $pnr = mysqli_real_escape_string($conn, $_GET['pnr']);
    $sql = "SELECT ti.ticket_no, ti.status, ti.date, ti.phno, ti.email, ti.username,
                   t.train_no, t.train_name, t.class,
                   s.source, s.destination, s.depart_time, s.arrival_time, s.fare
            FROM ticket ti
            INNER JOIN train t ON t.train_no = ti.train_no
            INNER JOIN station s ON s.station_no = ti.station_no
            WHERE ti.ticket_no = '$pnr' AND ti.username = '$uname'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
        $passengers = $conn->query("SELECT * FROM passanger WHERE ticket_no = '$pnr'");
    } else {
        $message = "PNR not found for your account.";
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
</head>
<body class="alert-light">
    <div class="container">
        <form class="m-5 p-5 border bg-light" action="" method="get">
            <div class="row">
                <div class="col-12">
                    <h4 class="navbar-brand text-primary">PNR Status / Cancel Ticket</h4>
                </div>
                <div class="col-8">
                    <input class="form-control" type="text" placeholder="Enter PNR Number" name="pnr" id="pnr" required>
                </div>
                <div class="col-4">
                    <input type="submit" class="btn btn-dark text-light" value="Get Status" name="show">
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <?php if ($message !== "") { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <?php if ($ticket) { ?>
            <table class="table table-bordered text-center bg-white">
                <tr class="table-primary">
                    <th>PNR No.</th>
                    <th>Status</th>
                    <th>Train No.</th>
                    <th>Train Name</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Date</th>
                    <th>Mobile</th>
                    <th>Booked By</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td><?php echo $ticket['ticket_no']; ?></td>
                    <td class="text-danger text-bold"><?php echo $ticket['status']; ?></td>
                    <td><?php echo $ticket['train_no']; ?></td>
                    <td><?php echo $ticket['train_name']; ?></td>
                    <td><?php echo $ticket['source']; ?></td>
                    <td><?php echo $ticket['destination']; ?></td>
                    <td><?php echo $ticket['depart_time']; ?></td>
                    <td><?php echo $ticket['arrival_time']; ?></td>
                    <td><?php echo $ticket['date']; ?></td>
                    <td><?php echo $ticket['phno']; ?></td>
                    <td><?php echo $ticket['username']; ?></td>
                    <td>
                        <?php if ($ticket['status'] !== 'cancelled') { ?>
                            <form method="post" action="">
                                <input type="hidden" name="pnr" value="<?php echo $ticket['ticket_no']; ?>">
                                <button type="submit" name="cticket" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        <?php } else { ?>
                            Cancelled
                        <?php } ?>
                    </td>
                </tr>
            </table>

            <h5>Passengers</h5>
            <table class="table table-bordered bg-white">
                <tr class="table-primary">
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Seat No.</th>
                </tr>
                <?php while ($passenger = $passengers->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $passenger['p_name']; ?></td>
                        <td><?php echo $passenger['p_age']; ?></td>
                        <td><?php echo $passenger['p_gender']; ?></td>
                        <td><?php echo $passenger['seat_no']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>

    <?php include('footer.html'); ?>
</body>
</html>
