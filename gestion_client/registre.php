<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$database = "uml";

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $tele = $_POST['tele'];
    $password = $_POST['password'];

    try {

        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, "");

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $check_email = $conn->prepare("SELECT * FROM client WHERE email = :email");
        $check_email->bindParam(':email', $email);
        $check_email->execute();
        $row_count = $check_email->rowCount();
        if ($row_count > 0) {
            // L'email existe déjà dans la base de données
            echo "Erreur : Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Insérer l'utilisateur dans la base de données
            $sql = "INSERT INTO client (nom, prenom, email, tele, password) VALUES (:nom, :prenom, :email, :tele, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':tele' => $tele, ':password' => $password]);
            echo "Inscription réussie !";
            header("Location: login.php");
        }

        echo "Inscription réussie !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion
    $conn = null;
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Inscription</h2>
        <form action="registre.php" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom:</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="tele" class="form-label">Téléphone:</label>
                <input type="text" class="form-control" id="tele" name="tele" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">S'inscrire</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>