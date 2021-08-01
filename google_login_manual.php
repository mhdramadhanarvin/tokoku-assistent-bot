<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://tokoku.itemku.com:81/user/google-login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
    "social_id_token": "eyJhbGciOiJSUzI1NiIsImtpZCI6IjNkZjBhODMxZTA5M2ZhZTFlMjRkNzdkNDc4MzQ0MDVmOTVkMTdiNTQiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiOTEyNjM2MzUwOTA1LW0wbTA4a2FhbWk3ZHNsdW11b3NpOG9nYTZiZjB0aTVmLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiOTEyNjM2MzUwOTA1LW0wbTA4a2FhbWk3ZHNsdW11b3NpOG9nYTZiZjB0aTVmLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTE2NDI2OTYyMzk5MTE5MTc0NTM4IiwiZW1haWwiOiJtcmFtYWRoYW42ODdAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiJrcUtibUROYXg3dnF3RFFYZHJPVmxRIiwibmFtZSI6Ik1oZCBSYW1hZGhhbiBBcnZpbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQU9oMTRHaGxlTExUSUNIdzhEQWpSTGJnWEM3WkswbGFCaURxTk9kWFJqNGs9czk2LWMiLCJnaXZlbl9uYW1lIjoiTWhkIFJhbWFkaGFuIiwiZmFtaWx5X25hbWUiOiJBcnZpbiIsImxvY2FsZSI6ImlkIiwiaWF0IjoxNjI3ODMyNzQ5LCJleHAiOjE2Mjc4MzYzNDksImp0aSI6IjllMDhhYTU4MDZmY2QzZjRlODlmNjRjZjViYzU4YTM1NjEzZjc3OTMifQ.an9G3JRMPF51e427K_rWDpe6s0FBgDc7XCMluW6wHAe07Z7qyEWCHizOS7hflyEanLCbDQ50u1BKrmFNH9mlifSTDjUBOfbntZlTzo7HouXUvahNe0tIau-vefEOhfcDflj9u_ZSIh1BlWb4w0JTYN9pIyeJh8PHL11Ij8sjgNYLwlXN9kXTqexrPiVjy4dU4WwURtRCygu0rxhosrWf7sMlBN2QgWjmwZfL2gHH92v1boZiODJusnnBhUAX13YLUETqHyT7Id72D4zEWv_D48_KuvkP8H8nzWn8sQxE37a5u6AEnYH8_I_N3ZTmSPoCsOF9zQaVI4yOaXhSVa2zxw"
}',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer undefined',
        'Client-ID: seller-web_d946981ba00f215004d36c42ffc9a602',
        'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
        'sec-ch-ua-mobile: ?0',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
