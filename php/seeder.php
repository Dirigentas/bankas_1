<?php

$ibans = [
    ['vardas' => 'Bebras', 'pavarde' => 'Bebrauskas', 'asmens kodas' => rand(100, 999), 'IBAN' => 'LT' . rand(10000, 99999)],
    ['vardas' => 'Caitlyn', 'pavarde' => 'Lionheard', 'asmens kodas' => rand(100, 999), 'IBAN' => 'LT' . rand(10000, 99999)],
    ['vardas' => 'Sion', 'pavarde' => 'Garenijum', 'asmens kodas' => rand(100, 999), 'IBAN' => 'LT' . rand(10000, 99999)],
];

// file_put_contents(__DIR__ . '/ibans', json_encode($ibans));


$users = [
    ['name' => 'Aras', 'psw' => md5('123')],
    ['name' => 'Albis', 'psw' => md5('123')],
    ['name' => 'Audrius', 'psw' => md5('123')],
    ['name' => 'Marius', 'psw' => md5('123')]
];

file_put_contents(__DIR__ . '/users', serialize($users));
