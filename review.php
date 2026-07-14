<?php
session_start();
include('DBConnection.php');
include('Details.php');

if (!isset($_SESSION["uname"])) {
    header("location: ./index.php?logout=1");
    exit();
}

include("header2.php");

$uname = $_SESSION["uname"];
$count = 0;
$pnr = $_SESSION['pnr'] ?? null;

if (isset($_POST['continue'])) {
    $src = $_POST['src'];
    $dest = $_POST['dest'];
    $class = $_POST['class'];
    $date = $_POST['date'];
    $station_no = $_POST['station_no'];
    $train_name = $_POST['train_name'];
    $train_no = $_POST['train_no'];
    $dep_time = $_POST['dep_time'];
    $arr_time = $_POST['arr_time'];
    $duration = $_POST['duration'];
    $email = $_POST['email'];
    $phno = $_POST['phno'];
    $fare = $_POST['fare'];

    if (!empty($_SESSION["temp"])) {
        $ticket_sql = "INSERT INTO ticket (status, date, phno, email, train_no, station_no, username)
                       VALUES ('booked', '$date', '$phno', '$email', '$train_no', '$station_no', '$uname')";

        if ($conn->query($ticket_sql) === TRUE) {
            $pnr = $conn->insert_id;
            $_SESSION['pnr'] = $pnr;
        } else {
            die("Ticket booking failed: " . $conn->error);
        }
    }

    for ($i = 1; $i <= 5; $i++) {
        $name = $_POST["name$i"] ?? '';
        $age = $_POST["age$i"] ?? '';
        $gender = $_POST["gender$i"] ?? '';

        if ($name !== '' && $age !== '' && $gender !== '') {
            $passenger_sql = "INSERT INTO passanger (p_name, p_age, p_gender, seat_no, ticket_no)
                              VALUES ('$name', '$age', '$gender', 0, '$pnr')";

            if ($conn->query($passenger_sql) === TRUE) {
                $conn->query("UPDATE train SET seat_avail = seat_avail - 1 WHERE train_no = '$train_no' AND seat_avail > 0");
                $count++;
            } else {
                die("Passenger booking failed: " . $conn->error);
            }
        }
    }

    $_SESSION["temp"] = false;
} else {
    header("location: ./index.php");
    exit();
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
        i.fa-circle { box-shadow: inset 0px 0px 3px 0px #222; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">
    <div class="container border border-primary mt-5 mb-5 p-4">
        <div class="row">
            <div class="col-2 offset-1 sm-hide">
                <div class="bg-primary p-3 text-center logo border border-primary">
                    <img src="asset/img/logo/passangerW.png">
                </div>
            </div>
            <i class="sm-hide fa fa-arrow-circle-right text-primary mt-4 pl-5"></i>
            <div class="col-8 col-sm-2 offset-1">
                <div class="p-3 text-center logo border border-primary">
                    <img src="asset/img/logo/reviewG.png">
                </div>
            </div>
            <i class="sm-hide fa fa-arrow-circle-right mt-4 text-lightdark pl-5"></i>
            <div class="col-2 offset-1 sm-hide">
                <div class="p-3 text-center logo border">
                    <img class="text-danger" src="asset/img/logo/cardG.png">
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pl-5 pb-5">
        <div class="row">
            <div class="col-8">
                <h5 class="text-bold-16">
                    <span class="text-blue"><?php echo $train_name; ?></span>&nbsp;
                    <span class="text-black">(<?php echo $train_no; ?>)</span>
                    <span class="strong fs-12 text-666 font-light"><b><?php echo $class; ?> | <?php echo $count; ?> Traveller</b></span>
                </h5>
                <h6 class="strong fs-12 text-666">
                    <span><b>From Station</b></span>
                    <span class="offset-4"><b>Arrival Station</b></span>
                </h6>
                <h5 class="text-bold-16 text-black">
                    <span><img src="asset/img/logo/rail_icon.png" width="20" class="sm-hide"> <?php echo $src; ?></span>
                    <span class="offset-3">&nbsp;&nbsp;&nbsp;<img src="asset/img/logo/rail_icon.png" width="20" class="sm-hide"> <?php echo $dest; ?></span>
                </h5>
                <h6 class="strong fs-12 text-666">
                    <span><b> Departure: <?php echo $date; ?> | <?php echo $dep_time; ?></b></span>
                    <span class="offset-2"><b>Arrival: <?php echo $date; ?> | <?php echo $arr_time; ?></b></span>
                </h6>

                <div class="card mt-4">
                    <div class="card-header bg-primary p-2">
                        <h5 class="text-light"><b>Travelling Passangers</b></h5>
                    </div>
                    <?php
                    $passenger_result = $conn->query("SELECT * FROM passanger WHERE ticket_no = '$pnr'");
                    if ($passenger_result && $passenger_result->num_rows > 0) {
                        while ($data = $passenger_result->fetch_assoc()) {
                    ?>
                    <div class="card-body">
                        <span class="fs-20 text-blue"><b><?php echo $data['p_name']; ?></b></span>
                        <span class="text-bold text-blue">&nbsp;&nbsp;&nbsp;<?php echo $data['p_age']; ?> | <?php echo $data['p_gender']; ?></span>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <form action="./payment.php" method="post">
                    <input type="hidden" name="src" value="<?php echo $src; ?>">
                    <input type="hidden" name="dest" value="<?php echo $dest; ?>">
                    <input type="hidden" name="class" value="<?php echo $class; ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                    <input type="hidden" name="station_no" value="<?php echo $station_no; ?>">
                    <input type="hidden" name="train_name" value="<?php echo $train_name; ?>">
                    <input type="hidden" name="train_no" value="<?php echo $train_no; ?>">
                    <input type="hidden" name="dep_time" value="<?php echo $dep_time; ?>">
                    <input type="hidden" name="arr_time" value="<?php echo $arr_time; ?>">
                    <input type="hidden" name="duration" value="<?php echo $duration; ?>">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <input type="hidden" name="phno" value="<?php echo $phno; ?>">
                    <input type="hidden" name="fare" value="<?php echo ($fare * $count); ?>">
                    <input type="hidden" name="pnr" value="<?php echo $pnr; ?>">
                    <input type="hidden" name="count" value="<?php echo $count; ?>">
                    <div class="text-center">
                        <input type="submit" value="Continue" name="continue1" class="btn btn-blue text-light hvr-grow m-2">
                    </div>
                </form>
            </div>

            <div class="col-12 col-sm-3 pl-4">
                <div class="card shadow-cust">
                    <div class="ml-4 mt-1 text-white">
                        <i class="fa fa-xs fa-circle ml-2 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                    </div>
                    <hr class="mt-1">
                    <div class="card-body pt-0 pb-0 text-center">
                        <img src="asset/img/logo/logo.png" width="40" height="40" class="mb-2">
                        <h5 class="text-bold-16 font-light">
                            <span class="text-blue"><?php echo $train_name; ?></span>&nbsp;
                            <span class="text-black">(<?php echo $train_no; ?>)</span>
                        </h5>
                        <h6 class="strong fs-12 text-666">
                            <span><?php echo $class; ?>, <?php echo $count; ?> Traveller</span>
                        </h6>
                        <div class="alert-primary p-1">
                            <h6 class="strong fs-12 text-666"><span><?php echo $date; ?></span></h6>
                            <h5 class="text-bold-16 font-light"><span class="text-black"><?php echo $src; ?></span></h5>
                            <h6 class="strong fs-12 text-666"><span>Departure: <?php echo $dep_time; ?></span></h6>
                            <i class="fa fa-arrow-circle-right text-dark"></i>
                            <h6 class="strong fs-12 text-666"><span><?php echo $date; ?></span></h6>
                            <h5 class="text-bold-16 font-light"><span class="text-black"><?php echo $dest; ?></span></h5>
                            <h6 class="strong fs-12 text-666"><span>Arrival: <?php echo $arr_time; ?></span></h6>
                        </div>
                        <h6 class="text-bold fs-12 text-black">
                            <span class="float-left">PNR NO: </span><span class="float-right"><?php echo $pnr; ?></span><br>
                            <span class="float-left">Total Fare: </span><span class="float-right"><?php echo ($fare * $count); ?>.00</span>
                        </h6>
                    </div>
                    <hr class="mb-1">
                    <div class="ml-4 mb-1 text-white">
                        <i class="fa fa-xs fa-circle ml-2 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                        <i class="fa fa-xs fa-circle ml-4 pt-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.html'); ?>
</body>
</html>
