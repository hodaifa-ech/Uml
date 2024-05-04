<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de recrutement</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="news.php">NEWS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container">
        <h2 class="mt-5">Formulaire de recrutement</h2>
        <?php
        // Vérifier si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Connexion à la base de données avec PDO
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "game";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                // Configuration de PDO pour lancer une exception en cas d'erreur SQL
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparation de la requête d'insertion
                $stmt = $conn->prepare("INSERT INTO recrute (nom, message, tele, email) VALUES (:nom, :message, :tele, :email)");

                // Liaison des paramètres
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':tele', $tele);
                $stmt->bindParam(':email', $email);

                // Récupération et validation des données du formulaire
                $nom = htmlspecialchars($_POST['nom']);
                $message = $_POST['message'];
                $tele = filter_var($_POST['tele'], FILTER_SANITIZE_NUMBER_INT);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                // Vérification du format de l'email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Format d'email invalide !");
                }

                // Vérification du format du numéro de téléphone
                if (!preg_match("/^\+2126[0-9]{8}$/", $tele)) {
                    throw new Exception("Format de téléphone invalide !");
                }
                
                

                // Exécution de la requête préparée
                $stmt->execute();

                echo '<div class="alert alert-success mt-3" role="alert">Enregistrement ajouté avec succès !</div>';

                // Fermeture de la connexion à la base de données
                $conn = null;
            } catch(PDOException $e) {
                echo '<div class="alert alert-danger mt-3" role="alert">Erreur : ' . $e->getMessage() . '</div>';
            } catch(Exception $e) {
                echo '<div class="alert alert-danger mt-3" role="alert">Erreur : ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form class="mt-4" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="tele" class="form-label">Téléphone :</label>
                <input type="text" class="form-control" id="tele" name="tele" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message :</label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>

    <!-- Bootstrap JavaScript (facultatif si vous n'utilisez pas de fonctionnalités JavaScript de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-DmY02bEYLz7LqN7t78M9CT5iW1167IWgvnfsjA4Jww5E3j7Ck9xvaXc4NL0OzN5a" crossorigin="anonymous"></script>
</body>
</html>
