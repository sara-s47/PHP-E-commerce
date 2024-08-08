<?php
if (isset($_POST['submit'])) {

    $userExist = false;


    $users = json_decode(file_get_contents('user.json'), true);
    $password = $_POST['password'];
    $email = $_POST['email'];
    $loginUser = [];


    foreach ($users as $user) {
        if (password_verify($password, $user['password']) && $_POST['email'] == $user['email']) {
            $loginUser = $user;
            $userExist = true;
            break;

        }  

    }

    if ($userExist == true) {
        session_start();

        $_SESSION['username'] = $loginUser['username'];
        $_SESSION['role'] = $loginUser['role'];

        // echo $loginUser['role'];

        header('location:home.php');

    }



}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="images/klipartz.com.png">

    <link rel="stylesheet" href="home.css">
    <title>login</title>
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

            </ul>
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link" href="#">
<!-- 
                        </i>
                        cart
                        <i class="fa-solid fa-cart-shopping cart"></i> -->
                        <!-- <span class="badge badge-info"></span> -->
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


    <section class="p-5 h-100">

        <div class="wrapper">
            <h2>Login</h2>
            <form action="login.php" method="post">

                <div class="input-box">
                    <input type="text" placeholder="Enter your email" name="email"
                        value="<?= isset($data['email']) ? $data['email'] : '' ?>">
                </div>

                <?php if (isset($errors['email'])): ?>
                    <p class="red"><?= $errors['email'] ?></p>
                <?php endif; ?>

                <div class="input-box">
                    <input type="password" placeholder="Confirm password" name="password">

                </div>

                <?php if (isset($errors['password'])): ?>
                    <p class="red"><?= $errors['password'] ?></p>
                <?php endif; ?>
                <div class="input-box button">
                    <input type="Submit" name="submit" value="login">
                </div>
                <div class="text">
                    <h3>Don't have an account? <a href="signup.php">signup now</a></h3>
                </div>
            </form>

    </section>

    <!-- <div class="sara"></div> -->
</body>

</html>