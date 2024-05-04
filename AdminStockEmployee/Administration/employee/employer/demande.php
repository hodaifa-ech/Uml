<?php 
session_start();
if(!isset($_SESSION['employer']) ){
    header("location:connexion.php");
}?>

<?php 


require_once "../include/database.php";
$sqlstate = $pdo->prepare('SELECT * FROM user WHERE id=?');
$idauthor = $_GET['id'];
$sqlstate->execute([$idauthor]);
$user = $sqlstate->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['add'])) {
    if (!empty($_POST['type']) && !empty($_POST['message'])) {
        $type = $_POST['type'];
        $message = $_POST['message'];
        $author = $user['login'];
        $image = $user['imageu'];
        $periode = $_POST['periode'];

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $sqlstate = $pdo->prepare("INSERT INTO demande(type, message, author, periode, image) VALUES (?, ?, ?, ?, ?)");
        $sqlstate->execute([$type, $message, $author, $periode, $image]);
        ?>
        <div class="alert alert-success m-2" role="alert">
            Votre demande a été bien envoyée !!!
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-success m-2" role="alert">
            Le type et le message sont obligatoires !!!
        </div>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>CREER DEMANDE</title>
</head>
<body>

<div class="container py-2">
<h4>Ajouter DEMANDE</h4>

<div class="d-flex align-items-center justify-content-between">
<h4></h4>
<a href="profileemp.php" class="btn btn-success m-2 ">Votre Profile</a>
</div>
<form method="post">
    <div class="mb-3">
        <label for="role" class="form-label">TYPE DEMANDE</label> 
        <select class="form-select" name="type" id="role">
            <option selected disabled>Select a type</option>
            <optgroup label="WITH PERIODE">
               <option value="conger">CONGER</option>
               <option value="actualiser">MISE A JOUR FONCTIONNEL</option>
               <option value="absence">ABSENCE</option>
            </optgroup>
            <optgroup label="WITHOUT PERIODE">
                <option value="demission">DEMISSION</option>
                <option value="retraite">RETRAITE</option>
            </optgroup>
        </select>
    </div>

    <div class="mb-3">
        <label for="periode" class="form-label">PERIODE</label> 
        <input type="number" min="0" class="form-control" name="periode">
    </div>

    <div class="mb-3">
        <label for="message" class="form-label">MESSAGE</label>
        <textarea name="message" id="message" class="form-control" ></textarea>
    </div>
    <button type="submit" class="btn btn-primary" name="add">ADD</button>
</form>
</div>
    
</body>
</html>
