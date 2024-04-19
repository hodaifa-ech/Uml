<?php 
include "nav.php"; require_once "../include/database.php";
?>
<?php 
session_start();
if(!isset($_SESSION['responsable'])){
    header("location:connexion.php");
}?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" href="img/<?= $_SESSION['responsable']['imageu']?>">
    <title>PROFILE | <?php echo $_SESSION['responsable']['login']; ?> </title>
<style>
.card-img-top{
    border-radius=100%;
    height=200px; 
    width=200px;
}
 </style>
</head>
<body>
<h1 title="<?php echo $_SESSION['responsable']['login'] ; ?>" class="z" >WELCOME <?php echo strtoupper($_SESSION['responsable']['login']) ; ?></h1> 

<div class="container">
    <div class="row">
        <div class="col-md-12">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Profile:</h3>
            <p class="card-text">
                <a href="edit.php?id=<?= $_SESSION['responsable']['id']; ?>" 
                onclick="return confirm('ARE YOU SURE THAT YOU WANT TO EDIT YOUR PROFILE <?= $_SESSION['responsable']['login'] ?> ??')"
                class="btn btn-primary btn-sm">Edit</a>
            </p>
        </div>

            <div class="card my-4 py-4">
            <img width="40px" class="card-img-top rounded-circle" src="img/<?= $_SESSION['responsable']['imageu']?>">
                <div class="card-body text-center">
                    <h4 class="card-title"><?php echo strtoupper($_SESSION['responsable']['login']); ?></h4>
                    <p class="card-text"><strong> date de naissance <?= date('Y-m-d', strtotime($_SESSION['responsable']['date_ajoute'])) ?> </strong> </p>
                                  
                </div>
            </div>
        </div>
    </div>
</div>

    
</body>
</html>
