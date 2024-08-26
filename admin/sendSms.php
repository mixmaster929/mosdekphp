<?php

   //Send an SMS using Gatewayapi.com
    $url = "https://gatewayapi.com/rest/mtsms";
    $api_token = "ymZ5l7NdTl2uPEnO-uymgG_iU-PEQekMZ0si0C0Xs6-5ujNtsvkbJkgUP5Cfx-nM";
    
    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData, true);


    $mobileNumber = $jsonData['mobileNumber'];
    $smsMessage = $jsonData['smsMessage'];
    $sender = $jsonData['sender'];

    if ($sender == '') {
        $sender = 'Mossdekk';
    }

    $recipients = $mobileNumber;

    // Set SMS recipients and content
    $json = [
        'sender' => $sender,
        'message' => $smsMessage,
        'recipients' => [],
    ];

    foreach ($recipients as $msisdn) {
        $json['recipients'][] = ['msisdn' => $msisdn];
    }

    // Make and execute the http request
    // Using the built-in 'curl' library
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_USERPWD, $api_token . ":");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Hämta HTTP-statuskoden
    curl_close($ch);

    $response = [
        'status' => $http_status,
        'result' => json_decode($result),
    ];

    echo json_encode($response);

?>