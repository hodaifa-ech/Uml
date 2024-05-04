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
    <title>Formulaire d'annonce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="profilerep.php">HOME</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-5">Formulaire d'annonce</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "game";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("INSERT INTO annonce (titre, contenu) VALUES (:titre, :contenu)");

                $titre = htmlspecialchars($_POST['titre']);
                $contenu = htmlspecialchars($_POST['contenu']);

                $stmt->bindParam(':titre', $titre);
                $stmt->bindParam(':contenu', $contenu);

                $stmt->execute();

                echo '<div class="alert alert-success mt-3" role="alert">Annonce ajoutée avec succès !</div>';

                $conn = null;
            } catch(PDOException $e) {
                echo '<div class="alert alert-danger mt-3" role="alert">Erreur : ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form class="mt-4" method="post">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre :</label>
                <input type="text" class="form-control" id="titre" name="titre" required>
            </div>
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu :</label>
                <textarea class="form-control" id="contenu" name="contenu" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-DmY02bEYLz7LqN7t78M9CT5iW1167IWgvnfsjA4Jww5E3j7Ck9xvaXc4NL0OzN5a" crossorigin="anonymous"></script>
</body>
</html>
