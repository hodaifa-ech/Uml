<?php
session_start();
include('config/dbsihame.php');

// Check if the id_commande is set and not empty
if (isset($_GET['id_commande']) && !empty($_GET['id_commande'])) {
    // Get the id_commande from the URL
    $id_commande = $_GET['id_commande'];

    // Update the etat_commande to "Accepter" in the database
    $sql = "UPDATE commande SET etat_commande = 'Refuser' WHERE id_commande = :id_commande";
    $query = $conn->prepare($sql);
    $query->bindParam(':id_commande', $id_commande);
    $query->execute();

    // Redirect back to the commande.php page
    header("Location: four.php");
    exit();
} else {
    // If id_commande is not set or empty, redirect to an error page or back to the original page
    header("Location: error_page.php");
    exit();
}
?>
