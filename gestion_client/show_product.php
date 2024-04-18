<?php

include 'connexionDB.php';



$sqlState = $conn->prepare('SELECT * FROM produit WHERE id_produit=?');
$id = @$_GET['id_produit'];
$sqlState->execute([$id]);
$produit = $sqlState->fetch(PDO::FETCH_ASSOC);
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
    <div class="row my-5 w-100">
        <div class="col-md-12">

            <div class="row">



                <div class="col-md-8">
                    <div class="card">
                        <?php if ($produit['image']) : ?>
                            <img src="image/<?php echo $produit['image'] ?>" alt="product_image" class="card-img-top img-fluid" style="height: 100%;">
                        <?php else : ?>
                            <img src="image/4k-Akatsuki-Wallpaper.jpg" alt="" class="card-img-top img-fluid" style="height: 400px;">
                        <?php endif; ?>



                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $produit['pnom'] ?></h5>
                            <p class="card-text"><?php echo $produit['quantite'] ?></p>
                            <h5 class="text text-danger "><?php echo $produit['Pprice'] . 'DH' ?></h5>

                            <div class="my-3 d-flex justify-content-start align-items-center">

                                <div>
                                    <a href="panier.php?id_produit=<?php echo $produit['id_produit'] ?>" class="btn btn-sm btn-outline-dark">ajouter ou panier</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>
    </div>
    </div>
</body>

</html>