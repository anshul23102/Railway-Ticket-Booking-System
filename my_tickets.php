<?php
session_start();
include('DBConnection.php');

if (!isset($_SESSION["uname"])) {
    header("location: ./index.php?logout=1");
    exit();
}

include("header2.php");
$uname = $_SESSION["uname"];

$sql = "SELECT ti.ticket_no, ti.status, ti.date, t.train_no, t.train_name,
               s.source, s.destination, s.depart_time, s.arrival_time,
               COUNT(p.pno) AS passenger_count
        FROM ticket ti
        INNER JOIN train t ON t.train_no = ti.train_no
        INNER JOIN station s ON s.station_no = ti.station_no
        LEFT JOIN passanger p ON p.ticket_no = ti.ticket_no
        WHERE ti.username = '$uname'
        GROUP BY ti.ticket_no
        ORDER BY ti.ticket_no DESC";
$tickets = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <title>My Tickets</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <h3 class="mb-4 text-primary">My Booked Tickets</h3>
        <table class="table table-bordered table-hover bg-white text-center">
            <tr class="table-primary">
                <th>PNR</th>
                <th>Status</th>
                <th>Train</th>
                <th>Route</th>
                <th>Date</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Passengers</th>
                <th>View</th>
            </tr>
            <?php if ($tickets && $tickets->num_rows > 0) { ?>
                <?php while ($ticket = $tickets->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $ticket['ticket_no']; ?></td>
                        <td><?php echo $ticket['status']; ?></td>
                        <td><?php echo $ticket['train_name']; ?> (<?php echo $ticket['train_no']; ?>)</td>
                        <td><?php echo $ticket['source']; ?> to <?php echo $ticket['destination']; ?></td>
                        <td><?php echo $ticket['date']; ?></td>
                        <td><?php echo $ticket['depart_time']; ?></td>
                        <td><?php echo $ticket['arrival_time']; ?></td>
                        <td><?php echo $ticket['passenger_count']; ?></td>
                        <td><a class="btn btn-sm btn-primary" href="pnrstatus.php?show=Get+Status&pnr=<?php echo $ticket['ticket_no']; ?>">View</a></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="9">No tickets booked yet.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php include('footer.html'); ?>
</body>
</html>
