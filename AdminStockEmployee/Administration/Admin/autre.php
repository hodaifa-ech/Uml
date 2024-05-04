
<?php 
      $insertIntoUsers = "INSERT INTO users(nom, prenom, email,telephone,status,user_role, password, image) VALUES (:nom, :prenom, :email,:telephone,:status,:user_role, :password, :file_name)";
      $queryForUsers = $conn->prepare($insertIntoUsers);

      $queryForUsers->bindParam(':nom', $nom, PDO::PARAM_STR);
      $queryForUsers->bindParam(':prenom', $prenom, PDO::PARAM_STR);
      $queryForUsers->bindParam(':email', $email, PDO::PARAM_STR);
      $queryForUsers->bindParam(':telephone', $telephone, PDO::PARAM_STR);
      $queryForUsers->bindParam(':status', $status, PDO::PARAM_STR);
      $queryForUsers->bindParam(':user_role', $user_role, PDO::PARAM_STR);
      $queryForUsers->bindParam(':password', $pass, PDO::PARAM_STR);
      $queryForUsers->bindParam(':file_name', $file_name, PDO::PARAM_STR);
      $queryForUsers->execute();


    

?>




