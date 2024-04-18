<?php
session_start();
include 'connexionDB.php';

if (isset($_SESSION['user_id'])) {
    $id_client = $_SESSION['user_id'];



    $id_produit = $_GET['id_produit'];

    $sqlCheckProduct = $conn->prepare('SELECT * FROM contient WHERE id_panier IN (SELECT id_panier FROM panier WHERE id_client = ?) AND id_produit = ?');
    $sqlCheckProduct->execute([$id_client, $id_produit]);
    $existingProduct = $sqlCheckProduct->fetch(PDO::FETCH_ASSOC);


    $sqlUpdate = $conn->prepare('UPDATE contient SET quantite = quantite + 1 WHERE id_panier IN (SELECT id_panier FROM panier WHERE id_client = ?) AND id_produit = ?');
    $sqlUpdate->execute([$id_client, $id_produit]);
    echo "La quantité du produit a été mise à jour dans le panier.";
    header('Location: panier.php');
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.php');
    exit();
}
