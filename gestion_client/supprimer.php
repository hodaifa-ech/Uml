<?php
session_start();
include 'connexionDB.php';

if (isset($_SESSION['user_id'])) {
    $id_client = $_SESSION['user_id'];

    if (isset($_GET['id_produit'])) {
        $id_produit = $_GET['id_produit'];

        // Supprimer le produit du panier du client
        $sqlDeleteProduct = $conn->prepare('DELETE FROM contient WHERE id_panier IN (SELECT id_panier FROM panier WHERE id_client = ?) AND id_produit = ?');
        $sqlDeleteProduct->execute([$id_client, $id_produit]);

        echo "Le produit a été supprimé du panier.";
        header('Location: panier.php');
    } else {
        echo "ID du produit non spécifié.";
    }
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.php');
    exit();
}
