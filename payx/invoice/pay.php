<?php

$ch = curl_init();

$summa = $_GET['summa'];
$getid = $_GET['id'];

curl_setopt($ch, CURLOPT_URL, 'https://backend.payx.uz/api/v1/invoice');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$data = [
    "amount" => $summa,
    "description" => "payX bot uchun.",
    "payer_reference" => "$getid"
];
$payload = json_encode($data);

curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$headers = [
    'Authorization: Bearer TOKEN',
    'Content-Type: application/json',
    'Accept: application/json',
    'Accept-Language: uz'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Xatolik: ' . curl_error($ch);
} else {
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo $response;
}

curl_close($ch);
