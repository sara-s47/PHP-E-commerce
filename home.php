<?php
$categories = json_decode(file_get_contents('categories.json'), true);

$allProducts = json_decode(file_get_contents('products.json'), true);

$category = "all";

$priceFilter = "asc";

$categoryProducts = $allProducts;


if (isset($_POST['submit'])) {

    $category = $_POST['category'];

    $priceFilter = $_POST['priceFilter'] ?? $priceFilter;

    $searchName = isset($_POST['searchName']) ? trim($_POST['searchName']) : '';

    $categoryProducts = array_filter($allProducts, function ($product) use ($category) {

        return $product['category'] == $category || $category == "all";
    });


    $categoryProducts = array_filter($allProducts, function ($product) use ($category, $searchName) {
        $matchesCategory = $product['category'] == $category || $category == "all";
        $matchesName = empty($searchName) || stripos($product['title'], $searchName) !== false;

        return $matchesCategory && $matchesName;
    });

    if ($priceFilter === 'lower_to_higher') {
        usort($categoryProducts, fn($a, $b) => $a['price'] - $b['price']);
    } elseif ($priceFilter === 'higher_to_lower') {
        usort($categoryProducts, fn($a, $b) => $b['price'] - $a['price']);
    }
}

session_start();

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
}


if (isset($_POST['delete'])) {


    $deleteProductId = $_POST['deletedProduct'];
    foreach ($allProducts as $id => $product) {
        if ($product['id'] == $deleteProductId) {
            unset($allProducts[$id]);
            // echo "<script>alert('product deleted successfully')</script>";
            file_put_contents('products.json', json_encode($allProducts));
            break;
        }
    }
    header('location:home.php');

}

$cartArray = json_decode(file_get_contents('cart.json'), true);
if (isset($_POST['add'])) {
    $productId = $_POST['product'];




    if ($cartArray != null) {
        $productExists = false;
        foreach ($cartArray as $product) {
            if ($product['id'] == $productId) {
                $productExists = true;
                break;
            }
        }


        if (!$productExists) {

            foreach ($allProducts as $product) {
                if ($product['id'] == $productId) {
                    $product['quantity'] = $_POST['quantity'];
                    $cartArray[] = $product;
                    break;
                }
            }



        } else {
            echo "<script>alert('product is already in the cart');</script>";
        }
    } else {
        foreach ($allProducts as $product) {
            if ($product['id'] == $productId) {
                $product['quantity'] = $_POST['quantity'];
                $cartArray[] = $product;
                break;
            }
        }
    }

    $cartJson = json_encode($cartArray, JSON_PRETTY_PRINT);
    file_put_contents('cart.json', $cartJson);



}

if ((json_decode(file_get_contents('cart.json'), true)) != null) {

    $cartCount = array_sum(array_map(function ($product) {
        return $product['quantity'];
    }, $cartArray));
}

if (isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    header('location:home.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="images/klipartz.com.png">

    <link rel="stylesheet" href="home2.css">
</head>

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
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item active">
                        <form action="home.php" method="post">
                            <button type="submit" name="logout" class="nav-link" href="signup.php">
                                logout
                                <span class="sr-only">(current)</span>
                            </button>
                        </form>
                    </li>
                <?php endif; ?>

            </ul>
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">

                        </i>
                        cart
                        <i class="fa-solid fa-cart-shopping cart"></i>
                        <span class="badge badge-info"><?php echo isset($cartCount) ? $cartCount : 0 ?></span>
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

    <div class="cover">
        <div class="on-cover">
            <p> START YOUR GREAT SHOPPING ECPERIENCE!</p>
        </div>

    </div>
    <?php if (isset($_SESSION['username'])): ?>
        <h1 class="h1">welcome <?= $_SESSION['username']; ?></h1>
    <?php endif; ?>

    <h2>Search By Category And Price :</h2>
    <div class="search">
        <form action="home.php" method="post">
            <h4 class="p">Search by name:</h4>
            <input type="text" name="searchName" class="form-control" placeholder="Enter product name" style="width:70%">
            <h4 class="p">category :</h4>
            <select name="category" class="form-select " aria-label="Default select example">
                <option value="<?= $category ?>"><?= $category ?></option>
                <?php if (isset($_POST['submit'])): ?>
                    <option value="all">all</option>
                <?php endif; ?>
                <?php foreach ($categories as $category): ?>

                    <option value="<?= $category['slug'] ?>"><?= $category['slug'] ?></option>
                <?php endforeach; ?>

            </select>
            <br>
            <h4 class="p">price :</h4>

            <input type="radio" name="priceFilter" value="lower_to_higher"> lower to higher &nbsp; &nbsp; &nbsp;
            <input type="radio" name="priceFilter" value="higher_to_lower"> higher to lower
            <br>

            <input type="submit" value="search" name="submit" class="buy--btn">
        </form>
    </div>

    <h2>PRODUCTS :</h2>


    <div class="container-fluid">
        <?php foreach ($categoryProducts as $product): ?>
            <?php if (is_array($product) && isset($product['title']) && isset($product['price'])): ?>

                <div class="container1">
                    <div class="wrapper ">
                        <div class="banner-image">
                            <img src="<?= $product['images'][0] ?>" alt="">
                        </div>
                        <h1> <?= $product['title'] ?></h1>
                        <p class="price">price :<?= $product['price'] ?> $</p>
                    </div>
                    <div class="button-wrapper">
                        <form action="home.php" method="post">
                            <input hidden type="text" name="product" value="<?= $product['id']; ?>">
                            <button class="btn1 fill" type="sumbit" name="add">add to cart</button>
                            <div style="display:flex; flex-direction:row; width:120%; padding-top:10px;">
                                <span style="dislplay:inline;margin-left:20px; color:white; width:60%;" class="aligin-self-start">quantity :
                                    </span>
                                <input type="number" name="quantity" class="align-self-center quantity"
                                    style="width:50%; display:inline;" min="1" value="1" required>
                            </div>
                            <br>
                        </form>
                        &nbsp; &nbsp;
                        <form action="productDetails.php" method="post">
                            <input hidden type="text" name="product" value="<?= $product['id']; ?>">
                            <button class="btn1 outline1" type="sumbit" name="details">Details</button>
                        </form>
                        &nbsp; &nbsp;

                    </div>
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <form action="home.php" method="post">
                                <input hidden name="deletedProduct" value="<?= $product['id']; ?>" type="text">
                                <button type="submit" name="delete" class="delete btn1">delete product</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>

            <?php endif; ?>
        <?php endforeach; ?>
    </div>


</body>

</html>