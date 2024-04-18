<?php
session_start();
include '../connexionDB.php';
$id = $_SESSION['user_id'];
$requete = $conn->prepare('DELETE FROM client WHERE id_client=?');
$suprimer = $requete->execute([$id]);
if ($suprimer) {
    session_unset();

    session_destroy();
    header('location: ../home.php');
} else
    echo "non";
