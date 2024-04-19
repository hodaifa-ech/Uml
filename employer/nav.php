<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php 
session_start(); 
$connect = false;
$responsable = false;
$employer=false;

if(isset($_SESSION['responsable'])){
  $connect = true;
  $responsable = true;
}

if(isset($_SESSION['employer'])){
  $connect = true;
  $responsable = false;
  $employer=true;
}
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="">ESPACE D'EMPLOYER</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

      <?php if($connect && $responsable){ ?>
          <li class="nav-item">
            <a class="nav-link active" href="profilerep.php">PROFILE</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="add.php">AJOUTER EMPLOYER</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="liste.php">LISTE EMPLOYER</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="listedemanderep.php">DEMANDE DES EMPLOYER</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="deconnexion.php">DECONNECTION</a>
          </li>
        <?php }
        if($connect && $employer){ ?>
        <li class="nav-item">
            <a class="nav-link" href="listedemande.php">VOTRE DEMANDE</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="deconnexion.php">DECONNECTION</a>
        </li>
         <?php  } 
        if(!$connect){?>
          <li class="nav-item">
            <a class="nav-link" href="connexion.php">LOGIN</a>
          </li>

        <?php        } ?>
       
          
      </ul>
    </div>
  </div>
</nav>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

