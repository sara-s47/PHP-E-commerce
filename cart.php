<?php
$cartProducts = json_decode(file_get_contents("cart.json"), true);

$cartProductsPrices = array_map(function ($product) {
  return $product['price'] * $product['quantity'];
}, $cartProducts);


// var_export($cartProductsPrices);

$sumPrices = array_sum($cartProductsPrices);
$cartCount = array_sum(array_map(function ($product) {
  return $product['quantity'];
}, $cartProducts));

if (isset($_POST['delete'])) {


  $deleteProductId = $_POST['deletedProduct'];
  foreach ($cartProducts as $id => $product) {
    if ($product['id'] == $deleteProductId) {
      unset($cartProducts[$id]);
      // echo "<script>alert('product deleted successfully')</script>";
      file_put_contents('cart.json', json_encode($cartProducts));
      break;
    }
  }
  header('location:cart.php');

}

if (isset($_POST['clear_cart'])) {
  file_put_contents('cart.json', json_encode([]));
  header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <link rel="icon" type="image/png" href="images/klipartz.com.png">
  <link rel="stylesheet" href="cart.css">
  <title>cart</title>
</head>

<body>

  <!-- End -->

  <div class="pb-5 ps-5 pt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">
        <form action="cart.php" method="post" class="d-flex pb-2">
            <button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>
          </form>

          <!-- Shopping cart table -->
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="border-0 bg-light">
                    <div class="p-2 px-3 text-uppercase">Product</div>
                  </th>
                  <th scope="col" class="border-0 bg-light">
                    <div class="py-2 text-uppercase">Price</div>
                  </th>
                  <th scope="col" class="border-0 bg-light">
                    <div class="py-2 text-uppercase">Quantity</div>
                  </th>
                  <th scope="col" class="border-0 bg-light">
                    <div class="py-2 text-uppercase">Remove</div>
                  </th>
                </tr>
              </thead>
              <tbody>

                <?php foreach ($cartProducts as $cartProduct): ?>
                  <tr>
                    <th scope="row">
                      <div class="p-2">
                        <img src="<?= $cartProduct['images'][0] ?>" alt="" width="70" class="img-fluid rounded shadow-sm">
                        <div class="ml-3 d-inline-block align-middle">
                          <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block"><?= $cartProduct['title'] ?></a>
                          </h5><span class="text-muted font-weight-normal font-italic">Category:
                            <?= $cartProduct['category'] ?></span>
                        </div>
                      </div>
                    <td class="align-middle"><strong>$<?= $cartProduct['price'] ?></strong></td>
                    <td class="align-middle"><strong><?= $cartProduct['quantity']; ?></strong></td>
                    <form action="cart.php" method="post">
                      <input hidden type="text" name="deletedProduct" value="<?= $cartProduct['id']; ?>">
                      <td class="align-middle"><button href="#" class="text-dark button" type="submit" name="delete"><i
                            class="fa fa-trash"></i></button>
                    </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- End -->
          <h5 class="font-weight-bold">Total $<?= $sumPrices; ?></h5>
          <a href="home.php">
            <p>back to shopping <i class="fa-solid fa-arrow-left"></i></p>
          </a>
        </div>
      </div>

      <div class="row py-5 p-4 bg-white rounded shadow-sm ps-5">
        <div class="col-lg-6">
          <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Coupon code</div>
          <div class="p-4">
            <p class="font-italic mb-4">If you have a coupon code, please enter it in the box below</p>
            <div class="input-group mb-4 border rounded-pill p-2">
              <input type="text" placeholder="Apply coupon" aria-describedby="button-addon3"
                class="form-control border-0">
              <div class="input-group-append border-0">
                <button id="button-addon3" type="button" class="btn btn-dark px-4 rounded-pill"><i
                    class="fa fa-gift mr-2"></i>Apply coupon</button>
              </div>
            </div>
          </div>
          <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Instructions for seller</div>
          <div class="p-4">
            <p class="font-italic mb-4">If you have some information for the seller you can leave them in the box below
            </p>
            <textarea name="" cols="30" rows="2" class="form-control"></textarea>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Order summary </div>
          <div class="p-4">
            <p class="font-italic mb-4">Shipping and additional costs are calculated based on values you have entered.
            </p>
            <ul class="list-unstyled mb-4">
              <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Order Subtotal
                </strong><strong>$<?= $sumPrices; ?></strong></li>
              <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Shipping and
                  handling</strong><strong>$10.00</strong></li>
              <li class="d-flex justify-content-between py-3 border-bottom"><strong
                  class="text-muted">Tax</strong><strong>$0.00</strong></li>
              <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                <h5 class="font-weight-bold">$<?= (int)$sumPrices + 10; ?></h5>
              </li>
            </ul><a href="#" class="btn btn-dark rounded-pill py-2 btn-block">Procceed to checkout</a>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>
</body>

</html>