<?php
$errors = [];
$data = [];



if (isset($_POST['submit'])) {

    $userData = [];

    if (!preg_match("/^[A-z]+/", $_POST['username'])) {
        $errors['username'] = "username is not valid";

    } else {


        $data['username'] = $_POST['username'];
        $userData['username'] = $_POST['username'];
    }

  
    if (empty($_POST['email'])) {
        $errors['email'] = "email is required";

    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "email is not valid";

    } else {
        $data['email'] = $_POST['email'];
        $userData['email'] = $_POST['email'];

    }


    if (empty($_POST['password1'])) {
        $errors['password1'] = "password is required";

    }

    if ($_POST['password2'] !== $_POST['password1']) {

        $errors['password2'] = "passwords are not matched";
    } elseif ($_POST['password2'] == $_POST['password1']) {
        $userData['password'] = password_hash($_POST['password2'], PASSWORD_BCRYPT);
    }

    $userData['role'] = $_POST['role'];

    if ($errors !== []) {
        $userData = [];
    }

    // var_export($userData);

    $users = json_decode(file_get_contents('user.json'), true);
    if ($userData !== []) {
        $users[] = $userData;
    }

    $users = json_encode($users);
    // var_export($users);  

    file_put_contents('user.json', $users);

    if ($errors == []) {
        header('location:login.php');

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
    <title>signUp</title>
</head>

<body>
<!------ Include the above in your HEAD tag ---------->

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

                        <!-- </i>
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



    <section class="p-5">

        <div class="wrapper">
            <h2>signup</h2>
            <form action="signup.php" method="post">
                <div class="input-box">
                    <input type="text" placeholder="Enter your name" required name="username"
                        value="<?= isset($data['username']) ? $data['username'] : '' ?>">
                </div>
                <?php if (isset($errors['username'])): ?>
                    <p class="red"><?= $errors['username'] ?></p>
                <?php endif; ?>
                <div class="input-box">
                    <input type="text" placeholder="Enter your email" name="email"
                        value="<?= isset($data['email']) ? $data['email'] : '' ?>">
                </div>

                <?php if (isset($errors['email'])): ?>
                    <p class="red"><?= $errors['email'] ?></p>
                <?php endif; ?>
                <div class="input-box">
                    <input type="password" placeholder="Create password" name="password1">
                </div>
                <?php if (isset($errors['password1'])): ?>
                    <p class="red"><?= $errors['password1'] ?></p>
                <?php endif; ?>
                <div class="input-box">
                    <input type="password" placeholder="Confirm password" name="password2">
                    <input hidden type="text" value="user" name="role">
                </div>

                <?php if (isset($errors['password2'])): ?>
                    <p class="red"><?= $errors['password2'] ?></p>
                <?php endif; ?>
                <div class="input-box button">
                    <input type="Submit" name="submit" value="signUp Now">
                </div>
                <div class="text">
                    <h3>Already have an account? <a href="login.php">Login now</a></h3>
                </div>
            </form>

    </section>
</body>

</html>