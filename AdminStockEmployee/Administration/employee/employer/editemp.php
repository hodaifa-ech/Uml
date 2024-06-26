<?php include "nav.php"; require_once "../include/database.php"; ?>
<?php 
if(!isset($_SESSION['employer'])){
    header("location:connexion.php");
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['employer']['login'] ?>|EDIT</title>
</head>
<body>




<?php 

$sqlstate = $pdo->prepare("SELECT * FROM user WHERE id=? ");
$id = $_GET["id"];
$sqlstate->execute([$id]);
$user = $sqlstate->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['add'])){
    
    if (!empty($_POST['login']) && !empty($_POST['password']) ) {
        $role = $_SESSION['employer']['role'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $nbrh=$_SESSION['employer']['nbrh'];
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $filename = uniqid() . "_" . $image;
            $destination = 'img/' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        } else {
            $filename = $user['imageu'];
        }
        
        $query = "UPDATE user SET login=?,imageu=?,password=?,role=?,nbrh=? WHERE id=?";
        if (!empty($_FILES['image']['name'])) {
            $sqlState = $pdo->prepare($query);
            $updated = $sqlState->execute([$login,$filename,$password,$role,$nbrh,$id]);
        } else {
            $query = "UPDATE user SET login=?,password=?,role=?,nbrh=? WHERE id=?";
            $sqlState = $pdo->prepare($query);
            $updated = $sqlState->execute([$login,$password,$role,$nbrh,$id]);
        }
?>

        <div class="alert alert-success m-2" role="alert">
            vous avez votre compte <strong><?= $login;?> <a  href="profileemp.php"> cliquer ici </a> !!! </strong>
            
        </div>
<?php
    } else {
        
?>
        <div class="alert alert-danger m-2" role="alert">
            Le login et le mot de passe sont obligatoires !
        </div>
<?php
    }
}

?>

<div class="container py-2">
    <h4>EDIT</h4>
    <a href="profileemp.php" class="btn btn-secondary m-2 ">Retour à la liste</a>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="login" class="form-label">LOGIN</label>
            <input type="text" class="form-control" id="login" name="login" value="<?=$user['login']?>" >
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">IMAGE</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <img class="img-fluid" width="200px" src='img/<?= $user['imageu']; ?>' alt="<?= $user['login']; ?>">
        <br>
        <div class="mb-3">
            <label for="password" class="form-label">MOT DE PASSE</label>
            <input type="password" class="form-control" id="password" name="password" value="<?=$user['password']?>">
        </div>
        <button type="submit" class="btn btn-primary" name="add">MODIFIER</button>
    </form>
</div>
    
</body>
</html>
