<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://tokoku.itemku.com:81/dashboard',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjozODczNzA1LCJkZXZpY2VfaWQiOiJzZWxsZXItd2ViX2Q5NDY5ODFiYTAwZjIxNTAwNGQzNmM0MmZmYzlhNjAyIiwiaXNfc3RhZmYiOmZhbHNlfSwiaWF0IjoxNjI3ODMzOTA5LCJleHAiOjE2Mjc4NzcxMDl9.JcjrnJv0su2ebbxsNyAY73c8pGK1rotN41p8iEFpYzk',
        'Client-ID: seller-web_d946981ba00f215004d36c42ffc9a602',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
