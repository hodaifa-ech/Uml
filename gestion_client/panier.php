<?php
include 'navbar.php';
include 'connexionDB.php';

if (isset($_SESSION['user_id'])) {
    $id_client = $_SESSION['user_id'];
    if (isset($_POST['ajouter'])) {
    }
    $stmt = $conn->prepare('SELECT p.id_produit, p.pnom, p.Pprice, p.image, c.quantite
    FROM produit p
    INNER JOIN contient c ON p.id_produit = c.id_produit
    INNER JOIN panier pa ON c.id_panier = pa.id_panier
    WHERE pa.id_client = ?');
    $stmt->execute([$id_client]);
    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlCheckClient = $conn->prepare('SELECT id_client FROM panier WHERE id_client = ?');
    $sqlCheckClient->execute([$id_client]);
    $existingClient = $sqlCheckClient->fetch(PDO::FETCH_ASSOC);
    if (!$existingClient) {
        $sqlInsertClient = $conn->prepare('INSERT INTO panier (id_client) VALUES (?)');
        $sqlInsertClient->execute([$id_client]);
    }

    if (isset($_GET['id_produit'])) {
        $id_produit = $_GET['id_produit'];

        $sqlCheckProduct = $conn->prepare('SELECT * FROM contient WHERE id_panier IN (SELECT id_panier FROM panier WHERE id_client = ?) AND id_produit = ?');
        $sqlCheckProduct->execute([$id_client, $id_produit]);
        $existingProduct = $sqlCheckProduct->fetch(PDO::FETCH_ASSOC);

        if ($existingProduct) {
            $sqlUpdate = $conn->prepare('UPDATE contient SET quantite = quantite + 1 WHERE id_panier IN (SELECT id_panier FROM panier WHERE id_client = ?) AND id_produit = ?');
            $sqlUpdate->execute([$id_client, $id_produit]);
            echo "La quantité du produit a été mise à jour dans le panier.";
        } else {
            $sqlInsertProduct = $conn->prepare('INSERT INTO contient (id_panier, id_produit, quantite) VALUES ((SELECT id_panier FROM panier WHERE id_client = ?), ?, 1)');
            $sqlInsertProduct->execute([$id_client, $id_produit]);
            echo "Le produit a été ajouté au panier.";
        }
    } else {
        echo "ID du produit non spécifié.";
    }
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="my-5">Votre Panier</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom du Produit</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalPrice = 0;
                        foreach ($products_in_cart as $index => $product) :
                            $totalPrice += $product['Pprice'] * $product['quantite'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo $product['pnom']; ?></td>
                                <td><?php echo $product['Pprice']; ?> DH</td>
                                <td><?php echo $product['quantite']; ?></td>
                                <td>
                                    <a type="button" class="btn btn-sm btn-outline-primary" name="ajouter" href="ajouter.php?id_produit=<?php echo $product['id_produit']; ?>">Ajouter Quantité</a>
                                    <a type="button" class="btn btn-sm btn-outline-danger" name="supprimer" href="supprimer.php?id_produit=<?php echo $product['id_produit']; ?>">supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2">Total</td>
                            <td colspan="3"><?php echo $totalPrice; ?> DH</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="commande.php" class="btn btn-primary">Commander</a>
            </div>
        </div>
    </div>
</body>

</html>