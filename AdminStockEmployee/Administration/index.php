<?php
session_start();
include 'Admin/config/dbcon.php';
if(isset($_POST['submit'])) {
    $useremail = $_POST['user_email'];
    $password = $_POST['user_password'];
    $select = "SELECT * FROM users WHERE email = :useremail AND password = :password";
    $qry = $conn->prepare($select);
    $qry->bindParam(':useremail', $useremail);
    $qry->bindParam(':password', $password);
    $qry->execute();
    $row = $qry->fetch(PDO::FETCH_ASSOC);
    if($row !== false) {
        $state=$row['status'];
        $_SESSION['status']=$row['status'];
        echo $state ;
        if($state==1){
            $user_role = $row['user_role'];
            $user_name = $row['nom'];
            $_SESSION['user_role']= $row['user_role'];
            $_SESSION['email']= $row['email'];
            $_SESSION['nom']= $row['nom'];
            $_SESSION['prenom']=$row['prenom'];
            $_SESSION['image']=$row['image'];
            $_SESSION['id']=$row['user_id'];
            //$_SESSION['responsable']=$row[''];
            if($user_role == 'super admin') {
                $_SESSION['admin_name'] = $user_name;
                header('location:Admin/profile.php');
                exit();
            }
            else if($user_role == 'ResponsableStock') {
                $_SESSION['admin_name'] = $user_name;
                header('location:Admin/profile.php');
                exit();
            }
            else if($user_role == 'admin'){
                header('location:Admin/profile.php');
                exit();
            }
            else if($user_role == 'respo employee'){
               // header('location: emp/employer/connexion.php');
                exit();
            }
            else if($user_role == 'fournisseur'){
                header('location: Admin/profile.php');
                exit();
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    } else {
        $error[] = 'Incorrect email or password!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="form-container">
      <form action="" method="POST">
         <h3>login now</h3>
         <input type="email" name="user_email" required placeholder="enter your email">
         <input type="password" name="user_password" required placeholder="enter your password">
         <input type="submit" name="submit" value="login now" class="form-btn">
      </form>
   </div>
</body>
</html>
