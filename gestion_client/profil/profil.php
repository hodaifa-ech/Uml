<?php
include('../navbar.php');

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "uml";

// Connect to the database
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM client WHERE id_client = :id_client");
$stmt->bindParam(':id_client', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container  ">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card profile-card">
                    <div class="card-header">
                        <h2 class="card-title">Profile</h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Nom:</strong> <?php echo $user['nom']; ?></li>
                            <li class="list-group-item"><strong>Prenom:</strong> <?php echo $user['prenom']; ?></li>
                            <li class="list-group-item"><strong>Email:</strong> <?php echo $user['email']; ?></li>
                            <li class="list-group-item"><strong>Téléphone:</strong> <?php echo $user['tele']; ?></li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="edit_profile.php" class="btn btn-primary">Edit</a>
                        <a onclick="deleteItem(<?php $_SESSION['user_id'] ?>)" href="suprimer_profile.php" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>