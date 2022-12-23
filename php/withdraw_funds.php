<?php
// print_r($_SERVER['REQUEST_METHOD']);
// echo '<br>';
// print_r($_GET);
// echo '<br>';
// print_r($_POST);
// echo '<br>';

$arr = json_decode(file_get_contents(__DIR__ . '/ibans.json'), 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($arr as $key => $value) {
        if ($value['ID'] == $_GET['ID']) {
            $post = $_POST['pokytis'];
            if (str_contains($post, ',')) {
                $post = str_replace(',', '.', $post);
            }
            if ($post > 0 && (float) $post * 1000 % 10 === 0) {
                if ($value['likutis'] - $post < 0) {
                    header('Location: http://localhost/pingvinai/bankas_1/php/withdraw_funds.php?ID=' . ($value['ID']) . '&error2');
                    die;
                }
                $arr[$key]['likutis'] -= (float) $post;
                file_put_contents(__DIR__ . '/ibans.json', json_encode($arr));
                header("Location: http://localhost/pingvinai/bankas_1/php/withdraw_funds.php?ID=" . ($value['ID']) . '&success');
                die;
            } else {
                header('Location: http://localhost/pingvinai/bankas_1/php/withdraw_funds.php?ID=' . ($value['ID']) . '&error');
                die;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nusiimti lėšas</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg" style="background-color: deepskyblue; font-weight: bold">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="http://localhost/pingvinai/bankas_1/php/iban_list.php">Sąskaitų sąrašas</a>
                    <a class="nav-link" href="http://localhost/pingvinai/bankas_1/php/new_iban.php">Pridėti naują sąskaitą</a>
                </div>
            </div>
        </div>
    </nav>

    <h6 <?= isset($_GET['error']) ? 'class="alert alert-danger" role="alert"'  : '' ?>><?= isset($_GET['error']) ? 'Nusiimama suma turi būti didesnė už 0 eur, formatas turi būti iki 2 skaičių po kablelio' : '' ?></h6>
    <h6 <?= isset($_GET['error2']) ? 'class="alert alert-danger" role="alert"'  : '' ?>><?= isset($_GET['error2']) ? 'Sąskaitos likutis negali būti mažesnis už 0 eur' : '' ?></h6>
    <h6 <?= isset($_GET['success']) ? 'class="alert alert-success" role="alert"'  : '' ?>><?= isset($_GET['success']) ? 'Lėšos nuskaičiuotos sėkmingai' : '' ?></h6>

    <div class=" container" style='padding-top:100px; text-align:center'>
        <div class="row">
            <?php foreach ($arr as $value) :
                if ($_GET['ID'] == $value['ID']) : ?>
                    <div class="col">
                        Vardas: <?= $value['vardas'] ?>
                    </div>
                    <div class="col">
                        Pavardė: <?= $value['pavarde'] ?>
                    </div>
                    <div class="col">
                        Sąskaitos likutis: <?= number_format($value['likutis'], 2) ?> eur
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>

    <form action="http://localhost/pingvinai/bankas_1/php/withdraw_funds.php?ID=<?= $_GET['ID'] ?>" method="post" style='padding:100px'>
        <div class="mb-3">
            <label class="form-label">Suma, eur</label>
            <input type="text" name="pokytis" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Nusiimti lėšas</button>
    </form>
</body>

</html>