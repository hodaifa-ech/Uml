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
// session_start();
// if(!isset($_SESSION['responsable'])){
//     header("location:connexion.php");
// }?>


<?php
    require_once "../include/database.php";
    $sqlstate=$pdo->query('SELECT * FROM demande')->fetchAll(PDO::FETCH_OBJ);
?>


 <div class="container">
     <table class="table table-striped">
        <tr>
            <th>TYPE</th>
            <th>MESSAGE</th>
            <th>AUTHOR</th>
            <th>PERIODE</th>
            <th>IMAGE</th>   
            <th>DATE</th>   
            <th>ETAT</th> 
            <th>action</th>
        </tr>

        <?php 
        foreach($sqlstate as $admin) {
        ?>
        <tr>
            <td><?php echo $admin->type; ?></td>
            <td><?php echo $admin->message; ?></td>
            <td><?php echo $admin->author; ?></td>

            <td>
            <?php
            if($admin->periode!=0){
                echo $admin->periode."jours" ;
            }else{ ?>
            None
            <?php

            } ?>
            </td>
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

            <td>
            <a href="editdemande.php?id=<?= $admin->id ;?>"
	         onclick="return confirm('ARE YOU SURE THAT YOU WANT TO EDIT ??')"
             class="btn btn-warning btn-sm">Edit</a>
            
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
</body>
</html>