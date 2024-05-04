<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LISTE DEMANDE</title>
</head>
<body> 
<?php include "nav.php";?>

<?php 

if(!isset($_SESSION['responsable'])){
    header("location:connexion.php");
}?>


<?php
    require_once "../include/database.php";
    $sqlstate=$pdo->query('SELECT * FROM recrute')->fetchAll(PDO::FETCH_OBJ);
?>


 <div class="container">
     <table class="table table-striped">
        <tr>
            <th>ID DEMANDE</th>
            <th>NOM</th>
            <th>MESSAGE</th>
            <th>TELEPHONE</th>
            <th>EMAIL</th>    
        </tr>

        <?php 
        foreach($sqlstate as $admin) {
        ?>
        <tr>
            <td class="text-center" ><?=  $admin->id; ?></td>
            <td><?php echo $admin->nom; ?></td>
            <td><?php echo $admin->message; ?></td>

            <td><a href="tel:<?= $admin->tele; ?>" class="btn btn-sm btn-warning"><?= $admin->tele; ?></a></td>
            <td><a href="mailto:<?= $admin->email; ?>" class="btn btn-sm btn-success"><?= $admin->email; ?></a></td>

        </tr>
        <?php
        
        }
        ?>
    </table>
</div>
</body>
</html>