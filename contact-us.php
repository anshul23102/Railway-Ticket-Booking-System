<?php 

session_start();
include("DBConnection.php");

// Check whether user is logged in or not
if (isset($_SESSION["uname"])) {
    $uname = $_SESSION["uname"];
    include("header2.php");
} else {
    include("header.html");
}

// Check if contact form is submitted
if (isset($_POST['contact'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $msg = trim($_POST['msg']);

    if (!empty($name) && !empty($email) && !empty($msg)) {
        // Sanitize input
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $msg = mysqli_real_escape_string($conn, $msg);

        // Check for duplicate entry
        $sql1 = "SELECT * FROM contact WHERE name='$name' AND email='$email' AND message='$msg'";
        $result = $conn->query($sql1);

        if ($result && $result->num_rows > 0) {
            echo "<script>alert('You already contacted us');</script>";
        } else {
            $sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$msg')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Thanks for contacting us');</script>";
            } else {
                echo "<script>alert('Database error: {$conn->error}');</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill in all the fields');</script>";
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
        #bg-custom { background-color: rgba(2,2,2,0.8); }
        .bg-custom { background-color: rgba(2,2,2,0.8); }
        .bg-img, .bg-img2 { background-image: url('asset/img/5.jpg'); background-size: 100%; }
        .m-cust { margin-right: 250px; margin-top: 60px; }
        .container { padding: 60px; margin: 60px; border-radius: 10px; }
        .bg-grey { background-color: #f6f6f6; }
        .row { border-radius: 10px; }
        @media screen and (max-width: 768px) {
            .col-sm-4 { text-align: center; margin: 25px 0; }
            .btn-lg { width: 100%; margin-bottom: 35px; }
        }
        @media screen and (max-width: 480px) {
            .logo { font-size: 150px; }
        }
    </style>
</head>
<body class="bg-img2">

<!-- Contact Section -->
<div id="contact" class="container bg-img">
    <h2 class="text-center">CONTACT</h2> <span id="error" class="text-danger text-bold offset-8"></span>
    <div class="row bg-grey pt-3">
        <div class="col-sm-5">
            <p>Contact us and we'll get back to you within 24 hours.</p>
            <p><span class="fa fa-map-marker"></span> Mumbai, India</p>
            <p><span class="fa fa-phone"></span> +91 9999999999</p>
            <p><span class="fa fa-envelope"></span> contact_us@ir.com</p>
        </div>
        <form action="" method="post" name="conForm" onsubmit="return contactvalid();" class="col-sm-7 slideanim">
            <div class="row">
                <div class="col-sm-6 form-group">
                    <input class="form-control" id="name" name="name" placeholder="Name" type="text">
                </div>
                <div class="col-sm-6 form-group">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                </div>
            </div>
            <textarea class="form-control" id="msg" name="msg" placeholder="Comment" rows="5"></textarea><br>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <input type="submit" value="Send" name="contact" class="btn btn-dark btn-block pull-right">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function contactvalid() {
    var name = document.conForm.name.value.trim();
    var email = document.conForm.email.value.trim();
    var msg = document.conForm.msg.value.trim();
    var error = document.getElementById("error");

    if (!name) {
        error.textContent = "Please enter your name";
        document.getElementById("name").focus();
        return false;
    }
    if (!email) {
        error.textContent = "Please enter your email";
        document.getElementById("email").focus();
        return false;
    }
    var atpos = email.indexOf("@");
    var dotpos = email.lastIndexOf(".");
    if (atpos < 1 || dotpos - atpos < 2) {
        error.textContent = "Please enter a valid email address";
        document.getElementById("email").focus();
        return false;
    }
    if (!msg) {
        error.textContent = "Please enter your message";
        document.getElementById("msg").focus();
        return false;
    }
    error.textContent = "";
    return true;
}
</script>

</body>
</html>
