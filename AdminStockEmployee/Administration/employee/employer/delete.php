<?php 
session_start();
if(!isset($_SESSION['responsable']) || !isset($_SESSION['employer']) ){
    header("location:connexion.php");
}?>
<?php 

require_once "../include/database.php";
$id=$_GET['id'];
$sqlstate=$pdo->prepare("DELETE FROM user WHERE id =?");
$sqlstate->execute([$id]);
if($sqlstate){
    header("location:liste.php");
}else{
    echo " <strong><h1>error database!!!</h1></strong>";
}
?>