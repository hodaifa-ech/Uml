<?php
session_start();
include('config/dbcon.php') ;
include('includes/header.php') ;
$name=$_SESSION['nom'];
$email=$_SESSION['email'];

$prenom=$_SESSION['prenom'];
$user_role=$_SESSION['user_role'];
$id= $_SESSION['id'];
if($_SESSION['image']){
    $image=$_SESSION['image'];
}else{
    $image='noprofile.jpg';
}
//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <title>Profile</title>
    <!-- custom css file link  -->
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="assets/css/style.css">
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>    
    <div class="container mt-8 mb-4 p-4 d-flex justify-content-center">
        <div class="card p-4">
            <div class=" image d-flex flex-column justify-content-center align-items-center">
                <button class="btn btn-secondary"> 
                    <img src="../images/<?php echo $image ?>" height="100" width="100" />
                </button>
                <span class="name mt-3"><?php echo $name ?></span> 
                <span class="idd"><?php echo $email ?></span> 
                <div class=" d-flex mt-2">
                    <button class="btn1 btn-dark"><a style="text-decoration:none;" href="editProfile.php?updateid=<?php echo $id;?>">Modifier Profile</a></button>
                </div> 
                <div class="text mt-3"> 
                    <span><?php echo $name ." ". $prenom." ";?>c'est un <?php echo $user_role ?></span>
                </div> 
            </div> 
        </div>
    </div>  
  
</body>
</html>