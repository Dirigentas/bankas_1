<?php
// print_r($_SERVER['REQUEST_METHOD']);
// echo '<br>';
// print_r($_GET);
// echo '<br>';
// print_r($_POST);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: http://localhost/bankas_1/php/log.php');
    die;
}

if (!file_exists(__DIR__ . '/ibans.json')) {
    $arr = [];
} else {
    $arr = json_decode(file_get_contents(__DIR__ . '/ibans.json'), 1);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (strlen($_POST['vardas']) < 4 || strlen($_POST['pavarde']) < 4) {
        header('Location: http://localhost/bankas_1/php/new_iban.php?error');
        die;
    }
    foreach ($arr as $value) {
        if ($_POST['asmens_kodas'] == $value['asmens_kodas']) {
            header('Location: http://localhost/bankas_1/php/new_iban.php?error2');
            die;
        }
    }

    $postAK = $_POST['asmens_kodas'];
    if (
        !is_numeric($postAK) ||
        in_array($postAK[0], [3, 4, 5, 6]) == 0 ||
        in_array($postAK[3], range(0, 1)) == 0 ||
        in_array($postAK[5], range(0, 3)) == 0 ||
        strlen($postAK) != 11
    ) {
        echo '<br>';
        echo 'error3';
        header('Location: http://localhost/bankas_1/php/new_iban.php?error3');
        die;
    }
    $arr[] = $_POST;
    file_put_contents(__DIR__ . '/ibans.json', json_encode($arr));
    header('Location: http://localhost/bankas_1/php/new_iban.php?success');
    die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New IBAN</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav class="navbar navbar-expand-sm" style="background-color: deepskyblue; font-weight: bold">
        <div class="container-fluid">
            <div class="navbar-nav" style="display:flex; gap: 10%; width: 100%; justify-content:space-around">
                <div style="color: #fff">Logged in as <br><?= $_SESSION['user']['name'] ?></div>
                <a class="nav-link" href="http://localhost/bankas_1/php/iban_list.php">Sąskaitų sąrašas</a>
                <a class="nav-link active" aria-current="page" href="http://localhost/bankas_1/php/new_iban.php">Pridėti naują sąskaitą</a>
                <form action="http://localhost/bankas_1/php/log.php?logout" method="post">
                    <button type="submit" class="btn btn-success">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container row justify-content-center" style="margin-top: 100px;">
        <form action="http://localhost/bankas_1/php/new_iban.php?form=1" method="post" class="m-4 col-12 col-sm-8 col-md-6">
            <input hidden value="<?= rand(0, 99999) ?>" name='ID'>
            <div class="mb-3">
                <label class="form-label">Vardas</label>
                <input required type="text" name='vardas' class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Pavardė</label>
                <input required type="text" name='pavarde' class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Asmens kodas</label>
                <input required type="code" name='asmens kodas' class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Sąskaitos numeris</label>
                <input readonly value="LT<?= rand(10, 99) . ' 7044 0' . rand(100, 999) . ' ' . rand(1000, 9999) . ' ' . rand(1000, 9999) ?>" name='IBAN' class="form-control">
            </div>
            <input hidden value="0.00" name='likutis'>
            <button type="submit" class="btn btn-success">Pateikti</button>
        </form>
    </div>

    <h6 <?= isset($_GET['success']) ? 'class="alert alert-success" role="alert"'  : '' ?>><?= isset($_GET['success']) ? 'Sąskaita pridėta sėkmingai' : '' ?></h6>
    <h6 <?= isset($_GET['error']) ? 'class="alert alert-danger" role="alert"'  : '' ?>><?= isset($_GET['error']) ? 'vardą ir pavardę turi sudaryti bent 4 simboliai' : '' ?></h6>
    <h6 <?= isset($_GET['error2']) ? 'class="alert alert-danger" role="alert"'  : '' ?>><?= isset($_GET['error2']) ? 'klientas su tokiu asmens kodu jau egzistuoja' : '' ?></h6>
    <h6 <?= isset($_GET['error3']) ? 'class="alert alert-danger" role="alert"'  : '' ?>><?= isset($_GET['error3']) ? 'netinkamas asmens kodo formatas' : '' ?></h6>
</body>

</html>