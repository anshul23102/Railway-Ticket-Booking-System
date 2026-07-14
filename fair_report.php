<?php
session_start();
include('DBConnection.php');

$sql = "SELECT t.train_no, t.train_name, t.class, s.source, s.destination, s.fare
        FROM train t
        INNER JOIN station s ON s.train_no = t.train_no
        ORDER BY t.train_no";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fare Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: Arial, sans-serif; }
        .container { margin-top: 50px; }
        .table-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 25px; color: #343a40; }
    </style>
</head>
<body>
<div class="container">
    <div class="table-container">
        <h2><i class="fas fa-rupee-sign"></i> Fare Report</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Train No</th>
                        <th>Train Name</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Class</th>
                        <th>Fare</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['train_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['train_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['source']); ?></td>
                            <td><?php echo htmlspecialchars($row['destination']); ?></td>
                            <td><?php echo htmlspecialchars($row['class']); ?></td>
                            <td><?php echo htmlspecialchars($row['fare']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">No fare records found in the database.</div>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>
<script src="asset/js/jquery-3.4.1.slim.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>
</body>
</html>
