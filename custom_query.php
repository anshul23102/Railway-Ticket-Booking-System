<?php
session_start();
include("DBConnection.php");

$result = null;
$error = "";
$query = "";

if (isset($_POST['run_query'])) {
    $query = trim($_POST['query']);

    if (stripos($query, "select") === 0) {
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Only SELECT queries are allowed for safety.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Custom SQL Query Page</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <style>
        body { background-color: #f7f9fa; padding: 30px; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        textarea { font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h3>Run Custom SQL Query</h3>
        <form method="POST">
            <div class="form-group">
                <label>Enter SELECT query:</label>
                <textarea name="query" rows="4" class="form-control" required><?php echo htmlspecialchars($query); ?></textarea>
            </div>
            <button type="submit" name="run_query" class="btn btn-primary">Run Query</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <?php while ($field = mysqli_fetch_field($result)): ?>
                                <th><?php echo htmlspecialchars($field->name); ?></th>
                            <?php endwhile; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($result, 0); ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($result): ?>
            <div class="alert alert-info mt-3">No records found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
