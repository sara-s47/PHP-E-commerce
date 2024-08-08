<?php

session_start();
// var_dump($_POST);
$errors;

if (isset($_POST['product']) && is_numeric($_POST['product'])) {
    $id = (int) $_POST['product'];
    $_SESSION['productId'] = $id;
} elseif (isset($_SESSION['productId'])) {
    $id = (int) $_SESSION['productId'];
} else {
    die("Invalid product ID.");  
}

$allProducts = json_decode(file_get_contents('products.json'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error decoding products.json: " . json_last_error_msg());
}

if (!isset($allProducts[$id - 1])) {
    die("Product not found.");
}

$selectedProduct = $allProducts[$id - 1];

$reviews = $selectedProduct['reviews'];

$cartArray = json_decode(file_get_contents('cart.json'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $cartArray = [];
}


if (isset($_POST['add'])) {
    $productId = $_POST['productid'];

    if ($cartArray != null) {
        $productExists = false;
        foreach ($cartArray as $product) {
            if ($product["id"] == $productId) {
                var_dump($product);
                $productExists = true;
                break;
            }
        }


        if (!$productExists) {

            $cartArray[] = $selectedProduct;

        } else {
            // echo "<script>alert('Product is already in the cart.');</script>";
            $errors = "Product is already in the cart.";
        }
    } else {
        $cartArray[] = $selectedProduct;
    }

    $cartJson = json_encode($cartArray, JSON_PRETTY_PRINT);
    file_put_contents('cart.json', $cartJson);


    // header("Location: productDetails.php?id=" . $id);
    // exit();


}
$cartCount = count($cartArray);

// var_dump($selectedProduct);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="productDetails.css">
    <title>Product Details</title>
</head>
<html>

<body>

    <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><img src="images/klipartz.com.png" class="logo" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">
                        <i class="fa fa-home"></i>
                        Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">
                        login
                        <!-- <span class="sr-only">(current)</span> -->
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="signup.php">
                        signup
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">

                        </i>
                        cart
                        <i class="fa-solid fa-cart-shopping cart"></i>
                        <span class="badge badge-info"><?php echo isset($cartCount) ? $cartCount : null ?></span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        Test
                        <span class="badge badge-success">11</span>
                       
                        </i>
                    </a>
                </li> -->
            </ul>
        </div>
    </nav>

    <main>
        <div class="card">
            <div class="card__title">
                <div class="icon">
                    <a href="home.php"><i class="fa fa-arrow-left"></i></a>
                </div>
                <h3>New products</h3>
            </div>
            <div class="card__body">
                <div class="half">
                    <div class="featured_text">
                        <h1><?= $selectedProduct['category']; ?></h1>
                        <p class="sub"><?= $selectedProduct['title'] ?></p>
                        <p class="price">$<?= $selectedProduct['price'] ?></p>
                    </div>
                    <div class="image">
                        <img src="<?= $selectedProduct['images'][0]; ?>" alt="">
                    </div>
                </div>
                <div class="half">
                    <div class="description">
                        <p><?= $selectedProduct['description']; ?></p>
                    </div>
                    <span class="stock"><i class="fa fa-pen"></i> In stock</span>
                    <div class="reviews">
                        <ul class="stars">
                            <p style="display : inline;"> rating : <?= $selectedProduct['reviews'][2]['rating']; ?>
                            </p>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <span>(64 reviews)</span>
                        <h3>comments:</h3><br>
                        <?php foreach ($reviews as $review): ?>
                            <p style="font-weight:bold; display:inline;"> ( <?= $review['reviewerName'] ?>) </p>
                            <p style=" display:inline;"><?= $review['comment'] ?> </p>
                            <br>
                            <br>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card__footer">
                <div class="recommend">
                    <p>Recommended by</p>
                    <h3><?= $selectedProduct['reviews'][2]['reviewerName']; ?></h3>
                </div>
                <div class="action">
                    <form action="productDetails.php" method="post">
                        <input hidden name="productid" value="<?= $selectedProduct['id']; ?>" type="text">
                        <button type="submit" name="add">Add to cart</button>
                    </form>
                    <p style="color:red;"><?= isset($errors) ? $errors : null ?></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>