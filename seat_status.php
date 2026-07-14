<?php
include('DBConnection.php');
header('Content-Type: application/json');

if (!isset($_GET['train_id']) || $_GET['train_id'] === '') {
    echo json_encode(['error' => 'No train ID provided']);
    exit();
}

$train_id = mysqli_real_escape_string($conn, $_GET['train_id']);
$train_result = $conn->query("SELECT seat_avail FROM train WHERE train_no = '$train_id'");

if (!$train_result || $train_result->num_rows === 0) {
    echo json_encode(['error' => 'Train not found']);
    exit();
}

$train = $train_result->fetch_assoc();
$booked_result = $conn->query("SELECT COUNT(*) AS booked FROM passanger p
                               INNER JOIN ticket t ON t.ticket_no = p.ticket_no
                               WHERE t.train_no = '$train_id' AND t.status = 'booked'");
$booked = $booked_result->fetch_assoc();

echo json_encode([
    'availableSeats' => (int)$train['seat_avail'],
    'bookedPassengers' => (int)$booked['booked']
]);
