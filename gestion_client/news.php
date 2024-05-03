<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANNONCES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container py-3">
        <h1>NEWS</h1>
        <div class="row">
            <?php
            // Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "supermarche";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                // Configuration de PDO pour lancer une exception en cas d'erreur SQL
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Récupération des données de la table annonce
                $stmt = $conn->query("SELECT * FROM annonce");
                $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Affichage des annonces dans des cartes Bootstrap
                foreach ($annonces as $annonce) {
                    echo '<div class="col-12 mb-3">';
                    echo '<div class="card">';
                    echo '<div class="card-header text-center"><h1>' . htmlspecialchars($annonce['titre']) . '</h1></div>';
                    echo '<div class="card-body text-center">';
                    echo '<p class="card-text">' . htmlspecialchars($annonce['contenu']) . '</p>';
                    echo '</div>';
                    echo '<div class="card-footer">';
                    echo '<a href="recrutement.php" class="btn btn-success text-center w-100">Join us</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }

                // Fermeture de la connexion à la base de données
                $conn = null;
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger" role="alert">Erreur : ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>