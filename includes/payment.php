<?php

// orderID, amount, acceptURL, cancelURL, callBackURL

function makePayment($arr) {
    // TEST: $apiKey = "NDRjNWFkOTAtYzVhZi00ZWZhLTlhZGMtNzQ3YjQzN2QzMzM3OjkzMGVhYTQzLTBkMGUtNGIxZC1iM2ZlLWU5NTUxYzI4MmExZg==";
    $apiKey = "NGY5ZjBlZDQtZjU3OC00NWJmLThmNGQtMzk2NGZlMGUyODg5OmNlZWFlOTdmLTFjNmItNDM5MS1iZWIzLTI1NzgyNWM5YzRkZA==";
    
    $merchNr = "P11112753";
    // TEST: $profile_id = $merchNr . ".4XpYcaZJ3TCaUbGd7AQfzy";
    $profile_id = $merchNr . ".4Xq4m2xfrmCtKqv7AD1D5o";
    
    // Authorization
    $authUrl = "https://checkout.dintero.com/v1/accounts/".$merchNr."/auth/token";
    $request = array();
    $request["grant_type"] = "client_credentials";
    $request["audience"] = "https://api.dintero.com/v1/accounts/" . $merchNr;
    
    $requestJson = json_encode($request);
    
    $contentLength = isset($requestJson) ? strlen($requestJson) : 0;
    
    $headers = array(
        'Content-Type: application/json',
        'Content-Length: ' . $contentLength,
        'Accept: application/json',
        'Authorization: Basic ' . $apiKey
    );
    
    $curl = curl_init();

	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_POSTFIELDS, $requestJson);
	curl_setopt($curl, CURLOPT_URL, $authUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$rawResponse = curl_exec($curl);
	$response = json_decode($rawResponse, true);
    
	$accessToken = $response["access_token"];
	$token_type = $response["token_type"];

    // Session
	$checkoutUrl = "https://checkout.dintero.com/v1/sessions-profile";

	$request = array();
	$request["customer"] = array(); //added 2023-04-14
	$request["customer"]["customer_id"] = $arr['regNr']; //added 2023-04-14
	$request["order"] = array();
	$request["order"]["merchant_reference"] = $arr['orderID'];
	$request["order"]["amount"] = $arr['amount'];
	$request["order"]["currency"] = "NOK";

	$request["order"]["items"] = array();
	$request["order"]["items"][0]["line_id"] = "1"; // for multiple items, needs to be unique for each
	$request["order"]["items"][0]["description"] = $arr['dateTime']; //added 2023-04-15
	$request["order"]["items"][0]["quantity"] = 1;
	$request["order"]["items"][0]["amount"] = $arr['amount'];

	$request["url"] = array();
	$request["url"]["return_url"] = $arr['acceptURL'];
	$request["url"]["callback_url"] = $arr['callbackURL'];
	/*$request["url"]["callbacks"] = array();
	$request["url"]["callbacks"][] = array("url" => $arr['callbackURL']);*/
	
	$request["profile_id"] = $profile_id;

	$requestJson = json_encode($request);

	$contentLength = isset($requestJson) ? strlen($requestJson) : 0;

	$headers = array(
		'Content-Type: application/json',
		'Content-Length: ' . $contentLength,
		'Accept: application/json',
		'Authorization: ' . $token_type . ' ' . $accessToken
	);

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_POSTFIELDS, $requestJson);
	curl_setopt($curl, CURLOPT_URL, $checkoutUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$rawResponse = curl_exec($curl);
	$response = json_decode($rawResponse);
	
	// response format: https://docs.dintero.com/checkout-api.html#operation/checkout_session_profile_post
	
	return $response;
}




?>