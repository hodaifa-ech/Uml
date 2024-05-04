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

if(!isset($_SESSION['employer'])){
    header("location:connexion.php");
}?>


<?php
    require_once "../include/database.php";
    $sqlstate=$pdo->query('SELECT * FROM demande')->fetchAll(PDO::FETCH_OBJ);
?>


 <div class="container py-2">
    <h2>Mes demandes:</h2>
     <table class="table table-striped">
        <tr>
            <th>TYPE</th>
            <th>MESSAGE</th>
            <th>AUTHOR</th>
            <th>PERIODE</th>
            <th>IMAGE</th>   
            <th>DATE</th>   
            <th>ETAT</th> 
        </tr>

        <?php 
        foreach($sqlstate as $admin) {
        ?>
        <tr>
            <td><?php echo $admin->type; ?></td>
            <td><?php echo $admin->message; ?></td>
            <td><?php echo $admin->author; ?></td>
            <td><?php echo $admin->periode."Jours" ?></td>
            <td><img class="img-fluid" width="100px" src='img/<?= $admin->image; ?>' alt="<?= $admin->name; ?>"></td>
            <td><?php echo $admin->date; ?></td>

            <td>
            <?php if($admin->etat==="processing"){?>
                <span class="badge  bg-dark " >
                   <?= $admin->etat ?>
                </span>
            <?php }elseif($admin->etat==="verified"){?> 
                <span class="badge  bg-success " >
                   <?= $admin->etat ?>
                </span>

            <?php }else{?>
                <span class="badge  bg-danger " >
                   <?= $admin->etat ?>
                </span>
            
            <?php

            }
             
            ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
</body>
</html>