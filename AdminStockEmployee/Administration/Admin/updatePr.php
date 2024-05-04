<?php
session_start();
include('includes/header.php');

// Include database connection
include 'config/dbsihame.php';

// Check if the updateid parameter is set
if(isset($_GET['updateid'])) {
    $id_produit = $_GET['updateid'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM produit WHERE id_produit = :id_produit";
    $query = $conn->prepare($sql);
    $query->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    // Check if form is submitted
    if(isset($_POST['submit'])){
        // Retrieve form data
        $pnom = $_POST['pnom'];
        $Pprice = $_POST['Pprice'];
        $quantite = $_POST['quantite'];
        $description = $_POST['description'];
        
        // Handle file upload for the new image (if any)
        if ($_FILES['image']['size'] > 0) {
            $file_name = $_FILES['image']['name']; // Get the name of the uploaded file
            $tempname = $_FILES['image']['tmp_name'];
            $folder = '../images/' . $file_name;
            // Move the uploaded file to the desired directory
            if (move_uploaded_file($tempname, $folder)) {
                echo "File uploaded successfully!";
            } else {
                echo "File upload failed!";
            }
            // Update the image filename in the database
            $update_image = "UPDATE produit SET image = :image WHERE id_produit = :id_produit";
            $query = $conn->prepare($update_image);
            $query->bindParam(':image', $file_name, PDO::PARAM_STR);
            $query->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);
            $query->execute();
        }

        // Update the product record in the database
        $update = "UPDATE produit SET pnom = :pnom, Pprice = :Pprice, quantite = :quantite, description = :description WHERE id_produit = :id_produit";
        $query = $conn->prepare($update);
        $query->bindParam(':pnom', $pnom, PDO::PARAM_STR);
        $query->bindParam(':Pprice', $Pprice, PDO::PARAM_INT);
        $query->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);
        $query->execute();

        // Redirect to a success page or do further processing
        exit(); // Ensure script stops executing after redirect
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>
   <!-- Bootstrap CSS -->
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- Custom CSS -->
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container" id="update_product">
    <div class="row">
        <div class="col-md-12">
            <div class="bc">
                <div class="form-container">
                    <form action="" method="post" enctype="multipart/form-data">
                        <h3>Update Product</h3>
                        <!-- Replace with the provided code snippet -->
                        <!-- Include the select dropdown for category -->
                        <!-- Populate the dropdown with available categories -->
                        <!-- Pre-select the current category of the product -->
                        <input type="text" name="pnom" required value="<?php echo $row['pnom'] ?>">
                        <input type="text" name="Pprice" required value="<?php echo $row['Pprice'] ?>">
                        <input type="number" name="quantite" required value="<?php echo $row['quantite'] ?>">
                        <input type="file" name="image">
                        <input type="text" name="description" required value="<?php echo $row['description'] ?>">
                        <select name="category">
                            <?php  
                            $sql = "SELECT * FROM category";
                            $query = $conn->prepare($sql);
                            $query->execute(); // Execute the prepared statement
                            while($categoryRow = $query->fetch(PDO::FETCH_ASSOC)){
                                $id_category = $categoryRow['id_category'];
                                $Cnom = $categoryRow['Cnom'];
                                $selected = ($id_category == $row['id_category']) ? 'selected' : '';
                                ?>
                                <option value="<?php echo $id_category; ?>" <?php echo $selected; ?>><?php echo $Cnom; ?></option>
                            <?php
                            }
                            ?>
                        </select>  
                        <!-- End of added code snippet -->
                        <input type="submit" class="bg-info" name="submit" value="Submit" class="form-btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php include('includes/footer.php') ?>
