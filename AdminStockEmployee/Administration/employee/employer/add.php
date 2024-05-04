<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJOUTER EMPLOYER</title>
</head>
<body>
<?php include "nav.php";?>
<?php 
if(!isset($_SESSION['responsable'])){
    header("location:connexion.php");
}?>


<?php

if(isset($_POST['add'])){
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['role']) && !empty($_POST['nbrh']) ) {
    require_once "../include/database.php";
    $role=$_POST['role'];
    $login = htmlspecialchars($_POST['login']);
    $password = $_POST['password'];
    $nbrh=$_POST['nbrh'];
    $filename = "";
   
    if (!empty($_FILES['image'])) {
        $image = $_FILES['image']['name'];
        $filename = uniqid() . "_" . $image;
        $destination = '/opt/lampp/htdocs/site/employer/img/'. $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
    }
    // Prepare the statement
    $sqlstate = $pdo->prepare('INSERT INTO user(login,imageu, password,role,nbrh) VALUES (?,?,?,?,?)');
    var_dump($sqlstate);
    $sqlstate->execute([$login,$filename,$password,$role,$nbrh]);
    header("location:liste.php");
}    
    else{?>
            <div class="alert alert-danger m-2" role="alert">
                toues les champs sont obligatoir !!!
            </div>
    <?php
    }
}
?>

<div class="container py-2">

<h4>Ajouter employer</h4>
<a href="liste.php" class="btn btn-secondary m-2 ">back to liste</a>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="login" class="form-label">LOGIN</label>
    <input type="text" class="form-control" id="login" name="login">
  </div>

  <div class="mb-3">
    <label for="image" class="form-label">IMAGE</label>
    <input type="file" class="form-control" id="image" name="image">
  </div>

  <div class="mb-3">
    <label for="role" class="form-label">ROLE</label> 
    <select class="form-select" name="role" id="role">
        <option selected disabled>Select a role</option>
        <option value="employer">employer</option>
        <option value="employer" disabled>you can add roles as you want</option>
    </select>
</div>

<div class="mb-3">
    <label for="nbrh" class="form-label">NOMBRE D'HEURE</label>
    <input type="number" min=1 max=100 class="form-control" id="nbrh" name="nbrh">
</div>


  

  <div class="mb-3">
    <label for="password" class="form-label">PASSWORD</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  <button type="submit" class="btn btn-primary" name="add">ADD</button>
</form>
</div>

    
</body>
</html>