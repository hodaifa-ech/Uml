<?php 
session_start();
include('includes/header.php');

$id_fournisseur = $_SESSION['id'];

?>
<h2 class="container mt-4">Commandes reçues</h2>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Nom produit</th>
            <th scope="col">Quantité</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
<?php

try {
    include 'config/dbsihame.php';
    $sqll = "SELECT c.id_fournisseur, c.quantite, p.pnom, c.id_commande
    FROM commande c
    INNER JOIN produit p ON c.id_produit = p.id_produit
    WHERE c.id_commande NOT IN (
        SELECT id_commande FROM commande WHERE etat_commande != 'En attente'
    )"; // Sélectionne uniquement les commandes en attente

    $query = $conn->prepare($sqll);
    if ($query->execute()) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id_fournisseur = $row['id_fournisseur'];
            $quantite = $row['quantite'];
            $nom_produit = $row['pnom'];
            $id_commande = $row['id_commande'];
        ?>
            <tr>
                <td><?php echo $nom_produit ?></td>
                <td><?php echo $quantite ?></td>
                <td>
                    <button class="btn btn-info text-white"><a href="Accepte.php?id_commande=<?php echo $id_commande; ?>" style="text-decoration:none;">Accepter</a></button>
                    <button class="btn btn-info text-white"><a href="refuse.php?id_commande=<?php echo $id_commande; ?>">Refuser</a></button>
                </td>
            </tr>
        <?php
        }
    }
} catch (PDOException $e) {
    // Handle PDO errors
    echo "PDO Error: " . $e->getMessage();
} catch (Exception $e) {
    // Handle other errors
    echo "Error: " . $e->getMessage();
}

?>
    </tbody>
</table>
