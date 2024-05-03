<?php

session_start();
include 'connexionDB.php';

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

// Get products
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $requete = $conn->prepare('SELECT * FROM produit WHERE pnom LIKE :searchTerm');
    $requete->bindValue(':searchTerm', '%' . $searchTerm . '%');
    $requete->execute();
    $produits = $requete->fetchAll(PDO::FETCH_OBJ);
} else {
    // Get all products
    $requete = $conn->prepare('SELECT * FROM produit');
    $requete->execute();
    $produits = $requete->fetchAll(PDO::FETCH_OBJ);
}

// Get categories
$requet = $conn->prepare('SELECT * FROM category');
$requet->execute();
$categorys = $requet->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarché</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    <style>
        .announcement {
            background-color: rgb(67, 38, 75);
            white-space: nowrap;
            /* Prevent the text from wrapping */
            overflow: hidden;
            /* Hide overflowed content */
            position: absolute;
            top: 10%;
            width: 100%;
            z-index: 1;
        }

        .e {
            color: rgb(255, 0, 0);
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Supermarche</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu " style="z-index: 10;">
                            <?php

                            if (empty($_SESSION)) {


                            ?>

                                <li>
                                    <a class="dropdown-item" href="registre.php">sign in</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="login.php">login
                                    </a>
                                </li>

                            <?php
                            } else {
                            ?>


                                <li>
                                    <a class="dropdown-item" href="profil/profil.php"> <?php echo $_SESSION['nom'] . " " . $_SESSION['prenom'] ?></a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="logout.php">log out </a>
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
                        <a class="nav-link active mx-5" aria-current="page" href="panier.php">
                            <button type="button" class="btn btn-dark position-relative">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php
                                    include 'connexionDB.php';
                                    if (!empty($_SESSION))
                                        echo countProductsInCart($_SESSION['user_id'], $conn);
                                    ?>
                                    <span class="visually-hidden">Product</span>
                                </span>
                            </button>
                        </a>
                    </li>
                </ul>
                <form class="d-flex" action="home.php" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- navbar -->
    <div class="announcement">
        <a target="_blank" class="e" href="news.php">
            <marquee bgcolor="white" direction="right" scrollamount="7">--------ANNONCE------</marquee>
        </a>
    </div>
    <div class="container mt-5">


        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="home.php" class="btn btn-sm btn-outline-dark">All</a>
                    <?php foreach ($categorys as $category) : ?>
                        <a href="category.php?id_category=<?php echo $category->id_category ?>" class="btn btn-sm btn-outline-dark"><?php echo $category->Cnom; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <?php if ($produits) : ?>
                <?php foreach ($produits as $produit) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if ($produit->image) : ?>
                                <img src="../AdminStockEmployee/Administration/images/<?php echo $produit->image ?>" alt="product_image" class="card-img-top">
                            <?php else : ?>
                                <img src="image/4k-Akatsuki-Wallpaper.jpg" alt="" class="card-img-top">
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo $produit->pnom ?></h5>
                                <p class="card-text"><?php echo $produit->quantite . "/" . $produit->quantite ?></p>
                                <h5 class="text-danger"><?php echo $produit->Pprice . "DH" ?></h5>
                                <a href="show_product.php?id_produit=<?php echo $produit->id_produit ?>" class="btn btn-sm btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-info">
                    No products available.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>