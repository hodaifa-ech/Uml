<?php

include 'connexionDB.php';



$sqlState = $conn->prepare('SELECT * FROM produit WHERE id_category=?');
$id = @$_GET['id_category'];
$sqlState->execute([$id]);
$produits = $sqlState->fetchAll(PDO::FETCH_ASSOC);



$sqlState = $conn->prepare('SELECT * FROM category');
$sqlState->execute();
$categorys = $sqlState->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="alert alert-success">
            <!-- <?php echo $message; ?> -->
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="home.php" class="btn btn-sm btn-outline-dark">All</a>
                    <?php foreach ($categorys as $category) : ?>
                        <a href="category.php?id=<?php echo $category['id_category'] ?>" class="btn btn-sm btn-outline-dark"><?php echo $category['Cnom']; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <?php if ($produits) : ?>
                <?php foreach ($produits as $produit) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if ($produit['image']) : ?>
                                <img src="image/<?php echo $produit['image'] ?>" alt="product_image" class="card-img-top">
                            <?php else : ?>
                                <img src="image/4k-Akatsuki-Wallpaper.jpg" alt="" class="card-img-top">
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo $produit['pnom'] ?></h5>
                                <p class="card-text"><?php echo $produit['quantite'] . "/" . $produit['quantite'] ?></p>
                                <h5 class="text-danger"><?php echo $produit['Pprice'] . "DH" ?></h5>
                                <a href="show_product.php?id_produit=<?php echo $produit['id_produit'] ?>" class="btn btn-sm btn-primary">View</a>
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
</body>

</html>