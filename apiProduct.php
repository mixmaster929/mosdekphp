<?php
set_time_limit(18000);

require_once('../includes/functions.php');

$con = dbCon();

require_once("curl_helper.php");
$access_token = CurlHelper::get_http_token()->access_token;
print_r("calling the api data...");
if(!empty($access_token)) {
	$data = false;
	$method = "GET";
	$url = "https://amr-production-api.azurewebsites.net/api/Articles/GetProducts";
	$request = CurlHelper::callAPI($method, $url, $data, $access_token); // Send or retrieve data
}
print_r("inserting data into db....");
for ($i=0; $i < sizeof($request['Result']) ; $i++) {
	$quote = array("'");
	$repalce_quote   = array("\'");
	
	$brand = str_replace($quote, $repalce_quote, $request['Result'][$i]['Brand']);
	$model = str_replace($quote, $repalce_quote, $request['Result'][$i]['Pattern']);
	$speed = str_replace($quote, $repalce_quote, $request['Result'][$i]['SpeedIndex']);
	$load = str_replace($quote, $repalce_quote, $request['Result'][$i]['LoadIndex']);
	$width = str_replace($quote, $repalce_quote, $request['Result'][$i]['Width']);
	$profile = str_replace($quote, $repalce_quote, $request['Result'][$i]['Profile']);
	$inches = str_replace($quote, $repalce_quote, $request['Result'][$i]['Inches']);
	$price = str_replace($quote, $repalce_quote, $request['Result'][$i]['PriceWarehouse1']);
	$fuel = str_replace($quote, $repalce_quote, $request['Result'][$i]['FuelGripNoise']);
	$noise = str_replace($quote, $repalce_quote, $request['Result'][$i]['Noise']);
	$image = str_replace($quote, $repalce_quote, $request['Result'][$i]['ImageUrl']);
	$season = str_replace($quote, $repalce_quote, $request['Result'][$i]['Season']);

	$sql = "insert  into `shop_tyres_api`
	 	(`brand`, `model`, `speed`, `load`, `width`, `profile`, `inches`, `price`, `fuel`, `noise`, `image`, `season`) values 
		('".$brand."', '".$model."', '".$speed."', '".$load."', '".$width."', '".$profile."', '".$inches."', '".$price."', '".$fuel."', '".$noise."', '".$image."', '".$season."')";
	 if(mysqli_query($con, $sql)){
	 	echo "$i Records inserted into shop_tyres_api successfully.";
	 } else {
	 	 echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	 	 exit;
	 }

	$tyreid = $i+1;
	$productdesc = str_replace($quote, $repalce_quote, $request['Result'][$i]['ProdTypeDesc']);

	$stock = str_replace($quote, $repalce_quote, $request['Result'][$i]['StockValueWarehouse1']);
	$purchaseprice = str_replace($quote, $repalce_quote, $request['Result'][$i]['PriceWarehouse1']);
	$delay = 0;
	$sql = "insert  into `shop_stock_api`
	 	(`tyreid`, `productdesc`, `stock`, `purchaseprice`, `delay`) values 
		('".$tyreid."', '".$productdesc."', '".$stock."', '".$purchaseprice."', '".$delay		."')";

	 if(mysqli_query($con, $sql)){
	 	echo "$i Records inserted into shop_stock_api successfully.";
	 } else {
	 	 echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	 	 exit;
	 }

}
print_r("inserted successfully");