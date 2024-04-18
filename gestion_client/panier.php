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
    <script>
        function deleteItem(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(id).submit();
                }
            });
        }
    </script>
    <style>
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Supermarche</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu">
                            <?php

                            if (empty($_SESSION)) {


                            ?>

                                <li>
                                    <a class="dropdown-item" href="registre.php">sign in</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="login.php">login
                                    </a>
                                </li>

                            <?php
                            } else {
                            ?>


                                <li>
                                    <a class="dropdown-item" href="profil/profil.php"> <?php echo $_SESSION['nom'] . " " . $_SESSION['prenom'] ?></a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="logout.php">log out </a>
                                </li>
                            <?php
                            }
                            ?>







                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <ul class="navbar-nav  mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active mx-5" aria-current="page" href="panier.php">
                            <button type="button" class="btn btn-dark position-relative">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php
                                    include 'connexionDB.php';
                                    if (!empty($_SESSION))
                                        echo countProductsInCart($_SESSION['user_id'], $conn);
                                    ?>
                                    <span class="visually-hidden">Product</span>
                                </span>
                            </button>
                        </a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- navbar -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>