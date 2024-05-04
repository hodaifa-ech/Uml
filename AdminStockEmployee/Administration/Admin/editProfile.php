<?php
session_start();
@include 'config/dbcon.php';
//include('includes/header.php');
$name=$_SESSION['nom'];
$email=$_SESSION['email'];
$image=$_SESSION['image'];
$prenom=$_SESSION['prenom'];
$user_role=$_SESSION['user_role'];
$user_id= $_SESSION['id'];
if(isset($_POST['submit'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    //image partie 
    $file_name=$_FILES['image']['name'];
    $tempname=$_FILES['image']['tmp_name'];
    $folder='../images/'.$file_name;
    //The query preparation:
     // Insert new product into the database $id_admin
     $update = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email,
     image = :file_name WHERE user_id = :id";
      $query = $conn->prepare($update);
      if (move_uploaded_file($tempname, $folder)) {
        //
      } else {
          echo "was not uploaded";
      }
      $query->bindParam(':nom', $nom, PDO::PARAM_STR);
      $query->bindParam(':prenom', $prenom, PDO::PARAM_STR);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->bindParam(':file_name', $file_name, PDO::PARAM_STR);
      $query->bindParam(':id',$user_id, PDO::PARAM_INT);
      $query->execute();
      exit();// Ensure script stops executing after redirect
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit profile</title>
    <link rel="stylesheet" href="../styling.css">
       <!-- custom css file link  -->
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <h1>Modifier Profile</h1>
        <form  action="" method="post"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_id">Email:</label>
                <input name="email" style="width: 900px;" type="text" class="form-control" id="event_id" required value="<?php echo $email ?>">
            </div>
            <div class="form-group">
                <label for="event_title">Nom:</label>
                <input name="nom" style="width: 900px;" type="text"  class="form-control" id="event_title" required  value="<?php echo $name ?>">
            </div>
            <div class="form-group">
                <label for="event_title">Prenom:</label>
                <input name="prenom" style="width: 900px;" type="text"  class="form-control" id="event_title" required value="<?php echo $prenom ?>">
            </div>
            <div class="form-group">
                <label for="event_img">Image de profile:</label>
                <input type="file" name="image"  class="form-control" id="image" />
            </div>
            <button type="submit" name="submit" class="btn btn-info">Sauvgarder</button>
        </form>
    </div> 
</body>
</html>