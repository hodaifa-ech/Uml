<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFIER DEMANDE</title>
</head>
<body>

<?php 
session_start();
if(!isset($_SESSION['responsable'])){
    header("location:connexion.php");
}?>
<?php include "nav.php"; require_once "../include/database.php"; ?>
<?php 

$sqlstate = $pdo->prepare("SELECT * FROM demande WHERE id=? ");
$id = $_GET["id"];
$sqlstate->execute([$id]);
$demande = $sqlstate->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['add'])){
    
    if (!empty($_POST['etat'])) {
        $type = $demande['type'];
        $message = $demande['message'];
        $author = $demande['author'];
        $date = $demande['date'];
        $etat = $_POST['etat'];
        
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $filename = uniqid() . "_" . $image;
            $destination = 'img/' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        } else {
            $filename = $demande['image'];
        }
        
        $query = "UPDATE demande SET type=?,message=?,author=?,image=?,date=?,etat=? WHERE id=?";
        if (!empty($_FILES['image']['name'])) {
            $sqlState = $pdo->prepare($query);
            $updated = $sqlState->execute([$type,$message,$author,$filename,$date,$etat,$id]);
        } else {
            $query = "UPDATE demande SET type=?,message=?,author=?,date=?,etat=? WHERE id=?";
            $sqlState = $pdo->prepare($query);
            $updated = $sqlState->execute([$type,$message,$author,$date,$etat,$id]);
        }
?>

        <div class="alert alert-success m-2" role="alert">
            vous avez modifié la demande de  <strong><?= $author;?> !!! </strong>
            <a href="listedemanderep.php">CLIQUEZ ICI POUR VOIR LA LISTE DES DEMANDES</a>
        </div>
<?php
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
?>
        <div class="alert alert-danger m-2" role="alert">
          le champ etat est obligatoir
        </div>
<?php
    }
}

?>

<div class="container py-2">
    <h4>EDIT DEMANDE</h4>
    <a href="listedemanderep.php" class="btn btn-secondary m-2 ">Retour à la liste</a>

    <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
            <label for="type" class="form-label">TYPE</label> 
            <input class="form-control" disabled type="text" name="type" id="type" value="<?= $demande['type'] ?>">
    </div>
    <div class="mb-3">
        <label for="periode" class="form-label">PERIODE</label> 
        <input disabled type="number" min="0" class="form-control" name="periode" value="<?= $demande['periode'] ?>">
    </div>

        
        <div class="mb-3">
        <label >MESSGAE</label>
        <textarea name="message" class="form-control" disabled><?= $demande['message'] ?> </textarea>
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">AUTHOR</label>
            <input disabled type="text" class="form-control" id="author" name="author" value="<?=$demande['author']?>">
        </div>
        <img class="img-fluid" width="200px" src='img/<?= $demande['image']; ?>' alt="<?= $demande['login']; ?>">
    <br>
    <br><br>
        <div class="mb-3">
            <label for="date" class="form-label">DATE</label>
            <input disabled type="text" class="form-control" id="date" name="date" value="<?=$demande['date']?>">
        </div>

        

        <div class="mb-3">
            <label for="etat" class="form-label">ETAT</label> 
            <select class="form-select" name="etat" id="etat">
                <option disabled> <?= $demande['etat'] ?></option>
                <option value="verified">VERIFIED</option>
                <option value="rejected">REJECTED</option>
                <option value="processing">PROCESSING</option>
            </select>
        </div>
       
        <button type="submit" class="btn btn-primary" name="add">MODIFIER DEMANDE</button>
    </form>
</div>
    
</body>
</html>
