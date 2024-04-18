<?php
session_start();
include '../connexionDB.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

function countProductsInCart($userId, $conn)
{
    try {

        $stmt = $conn->prepare("SELECT SUM(quantite) AS total FROM contient INNER JOIN panier ON contient.id_panier = panier.id_panier WHERE panier.id_client = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($result && $result['total'] !== null) {
            return $result['total'];
        } else {
            return 0;
        }
    } catch (PDOException $e) {

        echo "Database Error: " . $e->getMessage();
        return -1;
    }
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteItem(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(id).submit();
                }
            });
        }
    </script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Supermarche</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../home.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu">
                            <?php

                            if (empty($_SESSION)) {


                            ?>

                                <li>
                                    <a class="dropdown-item" href="../registre.php">sign in</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../login.php">login
                                    </a>
                                </li>

                            <?php
                            } else {
                            ?>


                                <li>
                                    <a class="dropdown-item" href="profil.php"> <?php echo $_SESSION['nom'] . " " . $_SESSION['prenom'] ?></a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../logout.php">log out </a>
                                </li>
                            <?php
                            }
                            ?>







                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <ul class="navbar-nav  mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active mx-5" aria-current="page" href="../panier.php">
                            <button type="button" class="btn btn-dark position-relative">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php
                                    include '../connexionDB.php';
                                    if (!empty($_SESSION))
                                        echo countProductsInCart($_SESSION['user_id'], $conn);
                                    ?>
                                    <span class="visually-hidden">Product</span>
                                </span>
                            </button>
                        </a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- navbar -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>