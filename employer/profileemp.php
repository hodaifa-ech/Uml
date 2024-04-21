<?php 
session_start();
include "nav.php"; 
require_once "../include/database.php";

$id = $_SESSION['employer']['id'];
$sqlstate = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$sqlstate->execute([$id]);

$user = $sqlstate->fetch(); // Fetch the result set




$presenceCookieSet = isset($_COOKIE['presence']);

// Determine the presence based on the user's presence and the presence cookie
$presence = false;
if ($user['presence'] == 1) {
    $presence = true;
    if($presence){
        setcookie("presence", "12345", time() + (24 * 60 * 60), "http://localhost/site/employer/profileemp.php");
        if (!$presenceCookieSet){
            $sqlstate = $pdo->prepare("UPDATE user SET presence = 0 WHERE id = ?");
            $sqlstate->execute([$_SESSION['employer']['id']]);
            $presence = false;
        }
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" href="img/<?= $_SESSION['employer']['imageu']?>">
    <title>PROFILE | <?php echo $_SESSION['employer']['login']; ?> </title>
<style>
.card-img-top{
    border-radius=100%;
    height=200px; 
    width=200px;
}
 </style>


</head>
<body>
<div class="d-flex align-items-center justify-content-between">
<h1 title="<?php echo $_SESSION['employer']['login'] ; ?>" class="z" >WELCOME <?php echo strtoupper($_SESSION['employer']['login']) ; ?></h1> 

<a href="demande.php?id=<?=$_SESSION['employer']['id']?>" target="_blank" rel="noopener noreferrer" class="btn btn-warning MX-3">CREER DEMANDE</a>

</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Profile:</h3>

            <?php  
            if(!$presence){?>

         <p class="card-text">
             <a href="presence.php?id=<?= $_SESSION['employer']['id']; ?>" class="btn btn-success">MARQUER LA PRESENCE</a>
         </p>
            <?php } ?>
        
            <p class="card-text">
                <a href="edit.php?id=<?= $_SESSION['employer']['id']; ?>" 
                onclick="return confirm('ARE YOU SURE THAT YOU WANT TO EDIT YOUR PROFILE <?= $_SESSION['employer']['login'] ?> ??')"
                class="btn btn-primary btn-sm">Edit</a>
            </p>
        </div>

            <div class="card my-4 py-4">
            <img width="40px" class="card-img-top rounded-circle" src="img/<?= $_SESSION['employer']['imageu']?>">
                <div class="card-body text-center">
                    <h4 class="card-title">NAME:<?php echo strtoupper($_SESSION['employer']['login']); ?></h4>
                    <p class="card-text"><strong> date de naissance <?= date('Y-m-d', strtotime($_SESSION['employer']['date_ajoute'])) ?> </strong> </p>
                                  
                </div>
            </div>
        </div>
    </div>
</div>

    
</body>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

</html>
