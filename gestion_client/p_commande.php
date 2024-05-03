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




    // Récupérer les produits dans le panier de l'utilisateur
    $stmt = $conn->prepare('SELECT * FROM commandef WHERE id_client = ?');
    $stmt->execute([$id_client]);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_POST['payer_button'])) {


        $conn->beginTransaction();

        // Récupérer l'ID du panier de l'utilisateur
        $sqlGetPanierId = $conn->prepare('SELECT id_panier FROM panier WHERE id_client = ?');
        $sqlGetPanierId->execute([$id_client]);
        $id_panier = $sqlGetPanierId->fetchColumn();

        // Insérer un enregistrement dans la table paiement
        $sqlInsertPaiement = $conn->prepare('INSERT INTO paiement (id_client, id_commande) VALUES (?,?)');
        foreach ($commandes as $commande) {
            $sqlInsertPaiement->execute([$id_client, $commande['id_commande']]);
        }

        // Insérer un enregistrement dans la table recut
        $sqlInsertRecut = $conn->prepare('INSERT INTO recut (id_client, id_commande) VALUES (?, ?)');
        foreach ($commandes as $commande) {
            $sqlInsertRecut->execute([$id_client, $commande['id_commande']]);
        }



        // Insérer un enregistrement dans la table recut


        // Valider la transaction
        $conn->commit();

        // Redirection vers une page de confirmation ou une autre page appropriée

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
        <h1 class="my-5">Votre Commande</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="your_table_id">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom du Produit</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Quantité</th>
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
                                <td><?php echo $product['Pprice']; ?> DH</td>
                                <td><?php echo $product['Pquantite']; ?></td>
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

        <!-- form table -->
        <!-- <form id="download_form" method="post" action="generate_pdf.php" style="display: none;">
            <input type="hidden" name="tableHtml" id="table_html_input">
        </form> -->


        <?php
        // Check if the button "Payer" is clicked (You need to implement this logic based on your application flow)
        $purchase_successful = false;
        if (isset($_POST['payer_button'])) {
            // Set a flag to indicate that the purchase is successful
            $purchase_successful = true;
        }

        // Define the button text based on the purchase status
        if ($purchase_successful) {
            $button_text = "Télécharger reçu"; // Change button text to "Télécharger reçu"
        } else {
            $button_text = "Payer"; // Default button text
        }
        ?>

        <!-- Display a thank you message and receipt download link if purchase is successful -->
        <?php if ($purchase_successful) : ?>
            <p>Merci pour votre achat !</p>
            <p>Veuillez récupérer votre reçu: <a href="pdf.php" target="_blank">Télécharger</a> </p>

        <?php endif; ?>

        <!-- Display the button with the appropriate text -->
        <div class="row mt-4">
            <div class="col-md-12">
                <form method="post">
                    <button type="submit" name="payer_button" id="telecharger_button" <?php if ($purchase_successful) echo 'hidden'; ?>><?php echo $button_text; ?></button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>