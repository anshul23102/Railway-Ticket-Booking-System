<?php
session_start();
include('DBConnection.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Seat Availability</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<?php
if (isset($_GET['train_id']) && $_GET['train_id'] !== '') {
    $train_id = mysqli_real_escape_string($conn, $_GET['train_id']);
    $query = "SELECT t.*, s.station_no, s.source, s.destination, s.fare, s.arrival_time, s.depart_time, s.duration
              FROM train t
              LEFT JOIN station s ON s.train_no = t.train_no
              WHERE t.train_no = '$train_id'
              LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $train = mysqli_fetch_assoc($result);
        $bookings_query = "SELECT COUNT(*) AS booked_seats FROM passanger p
                           INNER JOIN ticket t ON t.ticket_no = p.ticket_no
                           WHERE t.train_no = '$train_id' AND t.status = 'booked'";
        $bookings_result = mysqli_query($conn, $bookings_query);
        $bookings = mysqli_fetch_assoc($bookings_result);
        $booked = $bookings['booked_seats'] ?? 0;
?>
        <div class="card p-4 shadow">
            <h2 class="mb-3">Seat Availability for <strong><?php echo $train['train_name']; ?></strong> (Train No: <?php echo $train_id; ?>)</h2>
            <p><strong>Available Seats:</strong> <span id="availableSeats"><?php echo max($train['seat_avail'], 0); ?></span></p>
            <p><strong>Booked Passengers:</strong> <span id="bookedPassengers"><?php echo $booked; ?></span></p>
            <?php if ($train['seat_avail'] > 0 && !empty($train['station_no'])) { ?>
                <?php if (isset($_SESSION['uname'])) { ?>
                    <form action="psg_details.php" method="post" class="mt-4">
                        <input type="hidden" name="station_no" value="<?php echo $train['station_no']; ?>">
                        <input type="hidden" name="src" value="<?php echo $train['source']; ?>">
                        <input type="hidden" name="dest" value="<?php echo $train['destination']; ?>">
                        <input type="hidden" name="class" value="<?php echo $train['class']; ?>">
                        <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                        <input type="hidden" name="train_name" value="<?php echo $train['train_name']; ?>">
                        <input type="hidden" name="train_no" value="<?php echo $train['train_no']; ?>">
                        <input type="hidden" name="dep_time" value="<?php echo $train['depart_time']; ?>">
                        <input type="hidden" name="arr_time" value="<?php echo $train['arrival_time']; ?>">
                        <input type="hidden" name="duration" value="<?php echo $train['duration']; ?>">
                        <input type="hidden" name="fare" value="<?php echo $train['fare']; ?>">
                        <input type="submit" name="book" value="Book Ticket" class="btn btn-primary">
                    </form>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-primary mt-4">Login to Book</a>
                <?php } ?>
            <?php } else { ?>
                <button type="button" class="btn btn-secondary mt-4" disabled>Not Available</button>
            <?php } ?>
        </div>
        <script>
            setInterval(function() {
                fetch('seat_status.php?train_id=<?php echo $train_id; ?>')
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (!data.error) {
                            document.getElementById('availableSeats').innerText = data.availableSeats;
                            document.getElementById('bookedPassengers').innerText = data.bookedPassengers;
                        }
                    });
            }, 5000);
        </script>
<?php
    } else {
        echo "<div class='alert alert-danger'>Train not found with ID: $train_id</div>";
    }
} else {
    echo "<div class='alert alert-warning'>No train ID provided.</div>";
}
?>
</body>
</html>
