<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_GET['logout'])) {
        unset($_SESSION['user']);
        header('Location: http://localhost/bankas_1/php/log.php');
        die;
    }

    $users = unserialize(file_get_contents(__DIR__ . '/users'));

    foreach ($users as $user) {
        if ($user['name'] == $_POST['name']) {
            if ($user['psw'] == md5($_POST['psw'])) {
                $_SESSION['user'] = $user;
                header('Location: http://localhost/bankas_1/php/iban_list.php');
                die;
            }
        }
    }
    header('Location: http://localhost/bankas_1/php/log.php?error');
    die;
}

if (isset($_GET['error'])) {
    $error = 'User name and password combination is incorrect, please try again';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-7 col-12">
                <div class="card m-4">
                    <div class="card-header" style="text-align: center">
                        Login to Bankas-1
                    </div>
                    <div class="card-body">
                        <form action="http://localhost/bankas_1/php/log.php" method="post">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="psw" class="form-control" placeholder="password">
                                <button type="submit" class="btn btn-outline-info mt-4">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if (isset($error)) : ?>
                <div class="col-6">
                    <div class="alert alert-danger m-4" role="alert">
                        <?= $error ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</body>

</html>