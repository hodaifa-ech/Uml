<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LOG IN</title>
</head>
<body>

<?php

include "nav.php"; require_once "../include/database.php"; 


if (isset($_POST['send'])) {
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        $name = $_POST['login'];
        $password = $_POST['password'];
        
        
        
        // Prepare SQL statement to select user based on login
        $sqlstate = $pdo->prepare('SELECT * FROM user WHERE login=?');
        $sqlstate->execute([$name]);
        $user = $sqlstate->fetch();
        
        if ($user) {
            
           
                // Check if the role is 'employer'
                if ($user['role'] === 'Employer') {
                    
                    // Verify password for admin
                    if ( (string)$password === (string)$user['password']){
                        $_SESSION['employer'] = $user;
                        header("location: profileemp.php");
                        exit; // Terminate script after redirect
                    } else {
                        ?>
                        <div class="alert alert-danger m-2" role="alert">
                            Password or login incorrect !! 
                        </div>
                        <?php
                        
                    }
                }elseif($user['role'] === 'responsable'){
                    if ( (string)$password === (string)$user['password']){
                        $_SESSION["responsable"] = $user;
                        header("location: profilerep.php");
                        exit; // Terminate script after redirect
                    } else {
                        ?>
                        <div class="alert alert-danger m-2" role="alert">
                            Password or login incorrect !! 
                        </div>
                        <?php
                    }
                    

                }
             
        }else {
            ?>
            <div class="alert alert-warning m-2" role="alert">
                User not found!
            </div>
            <?php
        }

    }else {
        ?>
        <div class="alert alert-warning m-2" role="alert">
            Login or password incorrect!!
        </div>
        <?php
    }
}
?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form class="p-4 border rounded" method="POST">
                <h1 class="text-center mb-4">Login Form</h1>
                <div class="mb-3">
                    <input class="form-control" name="login" type="text" placeholder="Login">
                </div>
                <div class="mb-3">
                    <input class="form-control" name="password" type="password" placeholder="Password">
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit" name="send">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
