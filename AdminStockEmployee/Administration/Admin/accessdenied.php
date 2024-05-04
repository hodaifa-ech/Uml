<?php
session_start();
$name=$_SESSION['nom'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            background-image: url('../images/denied.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-content {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .error-content h1 {
            font-size: 36px;
        }

        .error-content p {
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container error-content">
    <div class="text-center">
        <h1 class="mt-4">Access Denied.</h1>
        <p class="lead" style="font-weight: bolder;"> <?php echo $name." " ?>We apologize but your account was desactivated </p>
    </div>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

