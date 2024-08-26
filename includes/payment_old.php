<?php

// orderID, amount, acceptURL, cancelURL, callBackURL

function makePayment($arr) {
	$accessToken = "eYgzn2wfvScb1aIf3QLs";
	$merchantNumber = "T511564901";
	$secretToken = "1qcvgmCNmSvP1ikAG38uSoAPr7ePByuMcWuMWKsa";

	$apiKey = base64_encode(
		$accessToken . "@" . $merchantNumber . ":" . $secretToken
	);

	$checkoutUrl = "https://api.v1.checkout.bambora.com/sessions";

	$request = array();
	$request["order"] = array();
	$request["order"]["id"] = $arr['orderID'];
	$request["order"]["amount"] = $arr['amount'];
	$request["order"]["currency"] = "NOK";

	$request["url"] = array();
	$request["url"]["accept"] = $arr['acceptURL'];
	$request["url"]["cancel"] = $arr['cancelURL'];
	$request["url"]["callbacks"] = array();
	$request["url"]["callbacks"][] = array("url" => $arr['callbackURL']);

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
	curl_setopt($curl, CURLOPT_URL, $checkoutUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_FAILONERROR, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$rawResponse = curl_exec($curl);
	$response = json_decode($rawResponse);
	
	return $response;
}




?>