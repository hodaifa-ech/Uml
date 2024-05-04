<?php 
session_start();
if(!isset($_SESSION['employer'])){
    header("location:connexion.php");
}?>
<?php

require_once "../include/database.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_GET['id'];

$sqlstate = $pdo->prepare("UPDATE user SET presence = 1 WHERE id = ?");
$sqlstate->execute([$id]);

    header("location: profileemp.php");
    exit; // Ensure no further code execution after redirection
    


?>
