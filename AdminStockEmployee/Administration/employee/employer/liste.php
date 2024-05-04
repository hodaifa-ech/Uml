<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LISTE EMPLOYER</title>
</head>
<body> 
<?php include "nav.php";?>
<?php 

if(!isset($_SESSION['responsable'])){
    header("location:connexion.php");
}?>


<?php
    require_once "../include/database.php";
    $sqlstate=$pdo->query('SELECT * FROM user')->fetchAll(PDO::FETCH_OBJ);
?>


 <div class="container">
   <a href="add.php" class="btn btn-primary m-2 ">AJOUTER EMPLOYER</a>
   
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>LOGIN</th>
            <th>IMAGE</th>
            <th>ROLE</th>
            <th>HEURE DE TRAVAILE</th>
            <th>DATE</th>   
            <th>OPERATION</th>    
        </tr>

        <?php 
        foreach($sqlstate as $admin) {
        ?>
        <tr>
            <td><?php echo $admin->id; ?></td>
            <td><?php echo $admin->login; ?></td>
            <td><img class="img-fluid" width="100px" src='img/<?= $admin->imageu; ?>' alt="<?= $admin->login; ?>"></td>
            <td><?= $admin->role ?></td>
            <td><?= $admin->nbrh."Heure" ?></td>
            <td><?php echo $admin->date_ajoute; ?></td>
            <td>
            <a href="delete.php?id=<?= $admin->id ;?>"
             onclick="return confirm('ARE YOU SURE THAT YOU WANT TO DELETE  <?php echo $admin->login; ?> ??')"
             class="btn btn-danger btn-sm">Delete</a>
            
            <a href="edit.php?id=<?= $admin->id ;?>"
	         onclick="return confirm('ARE YOU SURE THAT YOU WANT TO EDIT  <?php echo $admin->login; ?> ??')"
             class="btn btn-success btn-sm">Edit</a>  </td>
            
        </tr>
        <?php
        }
        ?>
    </table>
</div>
</body>
</html>