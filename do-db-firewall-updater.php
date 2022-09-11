<?php

$apiKey = "";
$databaseId = "";

$doAPICall = function ($endpoint, $additionalHeaders = [], $method = "GET", $postBody = NULL) use ($apiKey, $databaseId) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(["Content-Type: application/json"], $additionalHeaders));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    if ($method != "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postBody));
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return json_decode($response);
};

$currentIp = $doAPICall("https://ipinfo.io")->ip;
$databaseInfo = $doAPICall("https://api.digitalocean.com/v2/databases/" . $databaseId . "/firewall", ["Authorization: Bearer " . $apiKey]);

if ($databaseInfo->rules[0]->value != $currentIp) {
    $doAPICall("https://api.digitalocean.com/v2/databases/" . $databaseId . "/firewall", ["Authorization: Bearer " . $apiKey], "PUT", ["rules" => [["type" => "ip_addr", "value" => $currentIp]]]);
}