<?php
// print_r($_SERVER['REQUEST_METHOD']);
// echo '<br>';

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: http://localhost/bankas_1/php/log.php');
    die;
}

$arr = json_decode(file_get_contents(__DIR__ . '/ibans.json'), 1);

usort($arr, fn ($a, $b) => $a['pavarde'] <=> $b['pavarde']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($arr as $key => $value) {
        if ($value['ID'] == $_POST['ID']) {
            if ($value['likutis'] == 0) {
                unset($arr[$key]);
                file_put_contents(__DIR__ . '/ibans.json', json_encode($arr));
                header('Location: http://localhost/bankas_1/php/iban_list.php?success');
                die;
            } else {
                header('Location: http://localhost/bankas_1/php/iban_list.php?error');
                die;
            }
        }
    }
    header('Location: http://localhost/bankas_1/php/iban_list.php');
    die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IBAN list</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav class="navbar navbar-expand-sm" style="background-color: deepskyblue; font-weight: bold">
        <div class="container-fluid">
            <div class="navbar-nav" style="display:flex; gap: 10%; width: 100%; justify-content:space-around">
                <div style="color: #fff">Logged in as <br><?= $_SESSION['user']['name'] ?></div>
                <a class="nav-link active" aria-current="page" href="http://localhost/bankas_1/php/iban_list.php">Sąskaitų sąrašas</a>
                <a class="nav-link" href="http://localhost/bankas_1/php/new_iban.php">Pridėti naują sąskaitą</a>
                <form action="http://localhost/bankas_1/php/log.php?logout" method="post">
                    <button type="submit" class="btn btn-success">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-sm py-5 px-5" style="text-align:center">
        <div>
            <div style="display:flex; justify-content: space-between; flex-direction: column; gap: 10px; border: solid 3px skyblue">
                <?php foreach ($arr as $value) : ?>
                    <h6 style="display:flex; justify-content: space-between; border-bottom: dashed 2px skyblue; flex-wrap: wrap; gap: 5px; padding: 3px">
                        <div class="col-12 col-sm-12 col-md-5 col-lg-2 col-xl-1"><?= $value['asmens_kodas'] ?></div>
                        <div class="col-12 col-sm-12 col-md-5 col-lg-2 col-xl-1"><?= $value['pavarde'] ?></div>
                        <div class="col-12 col-sm-12 col-md-5 col-lg-3 col-xl-3"><?= $value['IBAN'] ?></div>
                        <div class="col-12 col-sm-12 col-md-5 col-lg-2 col-xl-1"><?= number_format($value['likutis'], 2, ',', ' ') ?></div>
                        <a class="col-12 col-sm-12 col-md-5 col-lg-2 col-xl-1" href="http://localhost/bankas_1/php/add_funds.php?ID=<?= $value['ID'] ?>">Pridėti lėšų</a>
                        <a class="col-12 col-sm-12 col-md-5 col-lg-2 col-xl-1" href="http://localhost/bankas_1/php/withdraw_funds.php?ID=<?= $value['ID'] ?>">Nuskaičiuoti lėšas</a>
                        <form class="col-12 col-sm-12 col-md-5 col-lg-2 col-xl-1" action="http://localhost/bankas_1/php/iban_list.php" method="post">
                            <input hidden name="ID" value="<?= $value['ID'] ?>">
                            <button type="submit" class="btn btn-success">Ištrinti</button>
                        </form>
                    </h6>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <h6 <?= isset($_GET['success']) ? 'class="alert alert-success" role="alert"'  : '' ?>><?= isset($_GET['success']) ? 'Sąskaita ištrinta' : '' ?></h6>
    <h6 <?= isset($_GET['error']) ? 'class="alert alert-danger" role="alert"'  : '' ?>><?= isset($_GET['error']) ? 'Sąskaita nėra tuščia, ištrinti negalima' : '' ?></h6>
</body>

</html>