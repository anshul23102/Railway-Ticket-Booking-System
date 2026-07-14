<?php
session_start();
include("DBConnection.php");

$trainNumber = "";
$results = [];
$message = "";
$hasSearched = false;

if (isset($_POST['search'])) {
    $trainNumber = mysqli_real_escape_string($conn, trim($_POST['train_number']));
    $hasSearched = true;

    if ($trainNumber === "") {
        $message = "Please enter a train number.";
    } else {
        $query = "SELECT ti.ticket_no, ti.status, ti.date, ti.username,
                         t.train_no, t.train_name, t.class,
                         s.source, s.destination,
                         p.p_name, p.p_age, p.p_gender, p.seat_no
                  FROM ticket ti
                  INNER JOIN train t ON t.train_no = ti.train_no
                  INNER JOIN station s ON s.station_no = ti.station_no
                  LEFT JOIN passanger p ON p.ticket_no = ti.ticket_no
                  WHERE ti.train_no = '$trainNumber' AND ti.status = 'booked'
                  ORDER BY ti.ticket_no DESC, p.pno ASC";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            $message = "Error executing query: " . mysqli_error($conn);
        } elseif (mysqli_num_rows($result) === 0) {
            $message = "No booked tickets found for train number $trainNumber.";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $results[] = $row;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmed Tickets by Train Number</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <style>
        body { background-color: #f0f0f0; }
        .container { background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 20px; margin-bottom: 20px; }
        .header { background-color: #007bff; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .table th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <div>
        <?php if (file_exists('adminheader1.html')) include('adminheader1.html'); ?>
        <?php if (file_exists('adminmenu.html')) include('adminmenu.html'); ?>
    </div>

    <div class="container">
        <div class="header">
            <h2><i class="fas fa-ticket-alt"></i> Confirmed Tickets by Train Number</h2>
        </div>
        <form method="POST" action="" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-sm-4">
                    <label for="train_number">Enter Train Number:</label>
                    <input type="text" class="form-control" id="train_number" name="train_number" value="<?php echo htmlspecialchars($trainNumber); ?>" required>
                </div>
                <div class="col-auto" style="margin-top: 30px;">
                    <button type="submit" name="search" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>

        <?php if ($hasSearched && $message !== ""): ?>
            <div class="alert alert-warning"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (count($results) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>PNR</th>
                            <th>Passenger Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Seat No.</th>
                            <th>Journey Date</th>
                            <th>Train</th>
                            <th>Route</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $ticket): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ticket['ticket_no']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['p_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['p_age'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['p_gender'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['class']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['seat_no'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($ticket['date']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['train_name']); ?> (<?php echo htmlspecialchars($ticket['train_no']); ?>)</td>
                                <td><?php echo htmlspecialchars($ticket['source']); ?> to <?php echo htmlspecialchars($ticket['destination']); ?></td>
                                <td><span class="badge badge-success">Booked</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
