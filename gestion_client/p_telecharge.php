<?php

session_start();
include 'connexionDB.php';

function countProductsInCart($userId, $conn)
{
    try {

        $stmt = $conn->prepare("SELECT SUM(quantite) AS total FROM contient INNER JOIN panier ON contient.id_panier = panier.id_panier WHERE panier.id_client = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($result && $result['total'] !== null) {
            return $result['total'];
        } else {
            return 0;
        }
    } catch (PDOException $e) {

        echo "Database Error: " . $e->getMessage();
        return -1;
    }
}

if (isset($_SESSION['user_id'])) {
    $id_client = $_SESSION['user_id'];
    if (isset($_POST['ajouter'])) {
    }
    $stmt = $conn->prepare('SELECT * FROM commande WHERE id_client = ?');
    $stmt->execute([$id_client]);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        header('Location: panier.php');
    } elseif (!isset($_SESSION['product_id_alert_displayed'])) {

        $_SESSION['product_id_alert_displayed'] = true;
        echo "<script>alert('ID du produit non spécifié.');</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h1 class="my-5">Votre Commande</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="your_table_id">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom du Produit</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalPrice = 0;
                        foreach ($commandes as $index => $product) :
                            $totalPrice += $product['Pprice'] * $product['Pquantite'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo $product['Pnom']; ?></td>
                                <td><?php echo $product['Pquantite']; ?></td>
                                <td><?php echo $product['Pprice']; ?> DH</td>
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
                <form method="post">
                    <button type="submit" name="payer_button" id="telecharger_button">Télécharger reçu</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>