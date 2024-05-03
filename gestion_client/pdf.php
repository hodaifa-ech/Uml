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

    $stmt = $conn->prepare('SELECT * FROM commandef WHERE id_client = ?');
    $stmt->execute([$id_client]);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    $stmt = $conn->prepare("SELECT * FROM client WHERE id_client = :id_client");
    // Liaison des paramètres
    $stmt->bindParam(':id_client', $id_client);
    // Exécution de la requête
    $stmt->execute();
    // Récupération des résultats
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // // Affichage des résultats
    // if ($result) {
    //     echo "ID Client: " . $result['id_client'] . "<br>";
    //     echo "Nom: " . $result['nom'] . "<br>";
    //     echo "Prénom: " . $result['prenom'] . "<br>";
    //     echo "Email: " . $result['email'] . "<br>";
    //     echo "Téléphone: " . $result['tele'] . "<br>";
    //     // Vous pouvez afficher d'autres attributs de la même manière
    // }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="pdf.css" />
    <script src="pdf.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

</head>

<body>
    <div class="container d-flex justify-content-center mt-50 mb-50">
        <div class="row">

            <div class="col-md-12">
                <div class="card" id="invoice">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title text-primary">Supermache</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-4 pull-left">

                                    <ul class="list list-unstyled mb-0 text-left">
                                        <li>2269 Maroc</li>
                                        <li>Tanger city</li>
                                        <li>+212 474447377 </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-4 ">
                                    <div class="text-sm-right">
                                        <?php
                                        // Obtenez la date et l'heure actuelles
                                        $currentDateTime = date('Y-m-d H:i:s');

                                        // Séparez la date et l'heure en utilisant l'espace comme délimiteur
                                        $dateTimeParts = explode(" ", $currentDateTime);

                                        // La première partie est la date, la deuxième partie est l'heure
                                        $date = $dateTimeParts[0];
                                        $heure = $dateTimeParts[1];
                                        ?>

                                        <h4 class="invoice-color mb-2 mt-md-2">Recu #BBB1243</h4>
                                        <ul class="list list-unstyled mb-0">
                                            <li>Date et Heure d'achat: <span class="font-weight-semibold"><br><?php echo $heure ?><br> le <?php echo $date ?></span></li>
                                            <li> <span class="font-weight-semibold"></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-md-flex flex-md-wrap">
                            <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Client Details:</span>

                                <ul class="list list-unstyled mb-0">
                                    <li>Nom:<?php echo " " . $result['nom'] ?></li>
                                    <li><?php echo "Prénom: " . $result['prenom'] ?></li>
                                    <li><?php echo "ID Client: " . $result['id_client'] ?></li>
                                    <li><?php echo "Email: " . $result['email'] ?></li>
                                    <li><?php echo "Téléphone: " . $result['tele'] ?></li>
                                </ul>
                            </div>
                            <div class="mb-2 ml-auto"> <span class="text-muted">Payment Details:</span>
                                <div class="d-flex flex-wrap wmin-md-400">
                                    <ul class="list list-unstyled mb-0 text-left">
                                        <li>
                                            <h5 class="my-2">Total Due:</h5>
                                        </li>
                                    </ul>
                                    <ul class="list list-unstyled text-right mb-0 ml-auto">
                                        <?php
                                        $totalPrice = 0;
                                        foreach ($commandes as $index => $product) :
                                            $totalPrice += $product['Pprice'] * $product['Pquantite'];
                                        endforeach;
                                        ?>
                                        <li>
                                            <h5 class="font-weight-semibold my-2"><?php echo $totalPrice; ?>DH</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-lg">
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
                                <!-- <tr>
                                    <td colspan="2">Total</td>
                                    <td colspan="3"><?php //echo $totalPrice; 
                                                    ?> DH</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <div class="d-md-flex flex-md-wrap">
                            <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th class="text-left">
                                                    <h6>Total:</h6>
                                                </th>
                                                <td class="text-right text-primary">
                                                    <h5 class="font-weight-semibold"><?php echo $totalPrice; ?>DH</h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>