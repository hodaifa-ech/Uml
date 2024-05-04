<?php 
session_start();
include('includes/header.php');
include('config/dbsihame.php');

$id_fournisseur =$_SESSION['id'];

?>
<h2 class="container mt-4">Commandes livrées</h2>

<table class="table">
    <thead>
        <tr>
             <th scope="col">ID commande</th>
            <th scope="col">Nom produit</th>
            <th scope="col">Quantité</th>
            <th scope="col">Etat</th> <!-- Ajout de la colonne Action -->
        </tr>
    </thead>
    <tbody>
<?php

try {
    
    include 'config/dbsihame.php';
    $sqll = "SELECT c.id_fournisseur, c.quantite, p.pnom, c.id_commande
    FROM commande c
    INNER JOIN produit p ON c.id_produit = p.id_produit
    WHERE c.etat_commande = 'Accepter'";

    $query = $conn->prepare($sqll);
    if ($query->execute()) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id_fournisseur = $row['id_fournisseur'];
            $quantite = $row['quantite'];
            $nom_produit = $row['pnom'];
            $id_commande = $row['id_commande']; // Récupérer l'ID de la commande
        ?>
            <tr>
                <td><?php echo $id_commande ?></td>
                <td><?php echo $nom_produit ?></td>
                <td><?php echo $quantite ?></td>
                <td>
                    <!-- Ajouter un lien vers le fichier livraison.php -->
                    <a href="cmdrecu.php?id_commande=<?php echo $id_commande; ?>">Livré</a>
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
