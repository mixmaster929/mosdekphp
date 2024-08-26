<?php
session_start();
date_default_timezone_set('Europe/Oslo');
ini_set('max_execution_time', 0);
set_time_limit(0);

include('includes/functions.php');

$method = '';
if(isset($_POST['method'])) { $method = p($_POST['method']); }

switch ($method) {
	case 'logoutCustomer':
		logoutCustomer();
		break;
	case 'loginCustomer':
		loginCustomer();
		break;
	case 'registerCustomer':
		registerCustomer();
		break;
	case 'tyreChangeDekkhotellOrderWithoutLogin':
		tyreChangeDekkhotellOrderWithoutLogin();
		break;
	case 'tyreChangeCheckForTyreOffers':
		tyreChangeCheckForTyreOffers();
		break;
	case 'verifyOrgNr':
		verifyOrgNr();
		break;
	case 'saveContact':
		saveContact();
		break;
	case 'tyreOrderWithoutLogin':
		tyreOrderWithoutLogin();
		break;
	case 'getTimeSlots':
		getTimeSlots();
		break;
	case 'getServices':
		getServices();
		break;
	case 'fetchFrontTyres':
		fetchFrontTyres();
		break;
	default: echo '<script> alert("You are now being Tracked"); </script>'; die;
}

function logoutCustomer() {
	$con = dbCon();
	session_destroy();
	$r = ['success'];
	echo json_encode($r);
	return;
}

function loginCustomer() {
	$con = dbCon();
	$username = p($_POST['username']);
	$password = md5(p($_POST['password']));
	
	if($username == '' || $password == '') {
		$r = ['empty'];
		echo json_encode($r);
		return;
	}

	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE username = '$username' AND password='$password'");
	if(mysqli_num_rows($q) > 0) {
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		$customerID = $f['id'];
		
		$_SESSION['customerID'] = (int)$customerID;
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	$r = ['incorrect'];
	echo json_encode($r);
	return;
	
}

function registerCustomer() {
	foreach($_POST as $key=>$value) {
		if(empty($value)) {
			$r = ['empty'];
			echo json_encode($r);
			return;
		}
	}
	
	$con = dbCon();
	$username = p($_POST['username']);
	$password = md5(p($_POST['password']));
	$fullName = p($_POST['fullName']);
	$regNr = p($_POST['regNr']);
	$mobile = p($_POST['mobile']);
	$postCode = p($_POST['postCode']);
	$address = p($_POST['address']);
	$city = p($_POST['city']);
	$email = p($_POST['email']);
	$today = date('Y/m/d H:i');
	
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE username='$username'");
	if(mysqli_num_rows($q) > 0) {
		$r = ['exists'];
		echo json_encode($r);
		return;
	}
	
	$q = mysqli_query($con, "INSERT INTO shop_customers (`id`, `createdOn`, `username`, `password`, `fullName`, `email`, `regNr`, `mobile`, `postCode`, `address`, `city`, `misc`) 
												VALUES (NULL, '$today', '$username', '$password', '$fullName', '$email', '$regNr', '$mobile', '$postCode', '$address', '$city', '')");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function tyreChangeDekkhotellOrderWithoutLogin() {
	foreach($_POST as $key=>$value) {
		if($key == 'serviceIDs' || $key = 'email' || $key == 'orgNr' || $key == 'orgNrDekk') { continue; }
		if(p($value) == '') {
			$r = ['empty fields'];
			echo json_encode($r);
			return;
		}
	}
	$con = dbCon();
	
	$regNr = p($_POST['regNr']);
	$name = p($_POST['name']);
	$mobile = p($_POST['mobile']);
	$date = p($_POST['date']);
	$type = p($_POST['workType']);
	$serviceIDs = p($_POST['serviceIDs']);
	$time = p($_POST['time']);
	$price = p($_POST['price']);
	$totalTime = (int)p($_POST['totalTime']);
	$workType = 'Tyre Change';
	$privateCustomerID = (int)p($_POST['pCID']);
	$offerID = (int)p($_POST['offerID']);
	$tyreID = (int)p($_POST['tyreID']);
	$tyreIDs = $tyreID.',';
	$selType = p($_POST['selType']);
	$email = p($_POST['email']);
	$paymentDone = (int)p($_POST['paymentDone']);
	$paymentMode = p($_POST['paymentMode']);
	$orgNr = p($_POST['orgNr']);
	
	$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
	$postData = [
		'method' => 'tyreChangeDekkhotellOrderWithoutLoginForTyreShop',
		'regNr' => $regNr,
		'name' => $name,
		'mobile' => $mobile,
		'date' => $date,
		'type' => $type,
		'serviceIDs' => $serviceIDs,
		'time' => $time,
		'price' => $price,
		'totalTime' => $totalTime,
		'workType' => $workType,
		'pCID' => $privateCustomerID,
		'offerID' => $offerID,
		'tyreID' => $tyreID,
		'tyreIDs' => $tyreIDs,
		'selType' => $selType,
		'email' => $email,
		'paymentDone' => $paymentDone,
		'paymentMode' => $paymentMode,
		'orgNr' => $orgNr
	];
	
	$response = get_web_page($url, $postData);
	$resArr = json_decode($response);
	if(!is_object($resArr)) {
		$r = ['api error'];
		echo json_encode($r);
		return;
	}
	
	if($resArr->result == 'customer not found') {
		$r = ['customer not found'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'failed' || $resArr->result == 'api error') {
		$r = ['failed'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'already ordered') {
		$r = ['already ordered'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'no work') {
		$r = ['no work'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'no employee') {
		$r = ['no employee'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'success') {
		
			if($paymentDone == 1) {
				$r = ['success'];
				echo json_encode($r);
				return;
			}
			else {
				$orderID = random_string(10);
				$postData['orderID'] = $orderID;
				$_SESSION['pD'] = $postData;
				
				$acceptURL = 'https://example.org/accept';
				$cancelURL = 'https://example.org/cancel';
				$callbackURL = 'https://example.org/callback';
				
				$arr = [
					'orderID' => $orderID,
					'amount' => (int)$price*100,
					'acceptURL' => $acceptURL,
					'cancelURL' => $cancelURL,
					'callbackURL' => $callbackURL
				];
				$response = makePayment($arr);
				if($response->meta->result) {
					$token = $response->token;
					$url = $response->url;
					$r = ['paySessionSuccess', $token, $url];
					echo json_encode($r);
					return;
				}else {
					// api error
					$r = ['failed'];
					echo json_encode($r);
					return;
				}
			}
		
	}
	
	
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function tyreChangeCheckForTyreOffers() {
	foreach($_POST as $key=>$value) {
		if($key == 'serviceIDs' || $key == 'orgNr' || $key == 'orgNrDekk') { continue; }
		if(p($value) == '') {
			$r = ['empty fields'];
			echo json_encode($r);
			return;
		}
	}
	$con = dbCon();
	
	$regNr = p($_POST['regNr']);
	$name = p($_POST['name']);
	$mobile = p($_POST['mobile']);
	$date = p($_POST['date']);
	$type = p($_POST['workType']);
	$serviceIDs = p($_POST['serviceIDs']);
	//$time = p($_POST['time']);
	$price = p($_POST['price']);
	$totalTime = (int)p($_POST['totalTime']);
	$workType = 'Tyre Change Dekkhotell';
	$privateCustomerID = 0;
	$tyreOfferTr = '';
	
	$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
	$postData = [
		'method' => 'tyreChangeCheckForTyreOffersForTyreShop',
		'regNr' => $regNr
	];
	
	$response = get_web_page($url, $postData);
	$resArr = array();
	$resArr = json_decode($response);
	if(!is_object($resArr)) {
		$r = ['api error'];
		echo json_encode($r);
		return;
	}
	
	if($resArr->result == 'failed') {
		$r = ['failed'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'no offer') {
		$r = ['no offer', $resArr->privateCustomerID, $resArr->tyreID];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'already ordered') {
		$r = ['already ordered'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'customer not found') {
		$r = ['customer not found'];
		echo json_encode($r);
		return;
	}else if($resArr->result == 'success') {
		$r = ['success', $resArr->tableHtml, $resArr->privateCustomerID, $resArr->tyreID];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function verifyOrgNr() {
	$con = dbCon();
	
	$orgNr = p($_POST['orgNr']);
	
	$url = 'http://autobutler.no/management/api/functions.php';
	$postData = [
		'method' => 'verifyOrgNrForTyreShop',
		'orgNr' => $orgNr
	];
	$response = get_web_page($url, $postData);
	$resArr = array();
	$resArr = json_decode($response);

	if(!is_object($resArr)) {
		$r = ['failed'];
		echo json_encode($r);
		return;
	}
	
	if($resArr->result == 'success') {
		$r = ['success'];
		echo json_encode($r);
		return;
	}else {
		$r = ['incorrect'];
		echo json_encode($r);
		return;
	}
	
}

function saveContact() {
	$con = dbCon();
	
	$name = p($_POST['name']);
	$email = p($_POST['email']);
	$phone = p($_POST['phone']);
	$sub = p($_POST['sub']);
	$msg = p($_POST['msg']);
	$date = date('Y/m/d H:i');
	
	if($name == '' || $email == '' || $phone == '' || $sub == '' || $msg == '') {
		echo 'empty fields'; return;
	}
	
	$q = mysqli_query($con, "INSERT INTO shop_contact (`id`, `date`, `name`, `email`, `phone`, `subject`, `message`, `misc`) VALUES (NULL, '$date', '$name', '$email', '$phone', '$sub', '$msg', '')");
	if($q) {
		
		// send email notification
		echo 'success'; return;
	}
	
	echo 'failed';
	return;
	
}

function tyreOrderWithoutLogin() {
	foreach($_POST as $key=>$value) {
		if($key == 'serviceIDs' || $key == 'orgNr' || $key == 'orgNrDekk') { continue; }
		if(p($value) == '') {
			$r = ['empty fields'];
			echo json_encode($r);
			return;
		}
	}
	$con = dbCon();
	
	$regNr = p($_POST['regNr']);
	$name = p($_POST['name']);
	$mobile = p($_POST['mobile']);
	$date = p($_POST['date']);
	$type = p($_POST['workType']);
	$serviceIDs = p($_POST['serviceIDs']);
	$time = p($_POST['time']);
	$price = p($_POST['price']);
	$totalTime = (int)p($_POST['totalTime']);
	$tyres = (int)p($_POST['tyres']);
	$paymentDone = (int) p($_POST['paymentDone']);
	$email = p($_POST['email']);
	$tyreID = (int) p($_POST['tyreID']);
	$paymentMode = p($_POST['paymentMode']);
	$orgNr = p($_POST['orgNr']);
	
	$customerID = 0;
	if(isset($_SESSION['customerID'])) {
		$customerID = (int)$_SESSION['customerID'];
	}
	
	if($paymentMode == 'orgNr' || $paymentMode == 'payAtShop') {
		$paymentDone = 1;
	}
	
	// dekkhotell orders
	if(isset($_POST['dekk'])) {
		$dekk = (int)p($_POST['dekk']);
		if($dekk == 1) {
			
			$pay = array();
			if($paymentDone == 1 && $paymentMode == 'payNow') {
				$pay['amount'] = p($_POST['amount']);
				$pay['cardNo'] = p($_POST['cardNo']);
				$pay['currency'] = p($_POST['currency']);
				$pay['payDate'] = p($_POST['payDate']);
				$pay['eci'] = p($_POST['eci']);
				$pay['feeID'] = p($_POST['feeID']);
				$pay['hash'] = p($_POST['hash']);
				$pay['issuerCountry'] = p($_POST['issuerCountry']);
				$pay['orderID'] = p($_POST['orderID']);
				$pay['paymentType'] = p($_POST['paymentType']);
				$pay['reference'] = p($_POST['reference']);
				$pay['payTime'] = p($_POST['time']);
				$pay['txnID'] = p($_POST['txnID']);
				
			}
			
			$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
			$postData = [
				'method' => 'tyreOrderWithoutLogin',
				'paymentDone' => $paymentDone,
				'tyreID' => $tyreID,
				'email' => $email,
				'totalTime' => $totalTime,
				'workType' => $type,
				'price' => $price,
				'regNr' => $regNr,
				'name' => $name,
				'mobile' => $mobile,
				'date' => $date,
				'serviceIDs' => $serviceIDs,
				'time' => $time,
				'tyres' => $tyres,
				'paymentMode' => $paymentMode,
				'orgNr' => $orgNr,
				'pay' => json_encode($pay),
				'otherPOST' => json_encode($_POST)
			];
			
			$response = get_web_page($url, $postData);
			$resArr = array();
			$resArr = json_decode($response);

			if(!is_object($resArr)) {
				$r = ['failed'];
				echo json_encode($r);
				return;
			}
			if($resArr->result == 'success' && $paymentDone == 1) {
				if($customerID != 0) {
					$misc = array();
					$orderedOn = date('Y/m/d H:i');
					$status = 'Pending';
					$changeDate = $date.' '.$time;
					if($paymentMode == 'payNow') {
						$mode = 'Kort betaling/delbetaling';
					}else if($paymentMode == 'payAtShop') {
						$mode = 'Betaling i butikk';
					}else {
						$mode = 'Firmakunde';
					}
					
					$misc['paymentMode'] = $mode;
					if($paymentMode == 'orgNr') {
						$misc['orgNr'] = $orgNr;
					}
					if(isset($resArr->workOrderID)) {
						$misc['managementWorkOrderID'] = $resArr->workOrderID;
					}
					$misc['orderType'] = 'dekk';
					$misc['workType'] = $type;
					$misc['serviceIDs'] = $serviceIDs;
					$misc = json_encode($misc);
					
					//$orderID = $_SESSION['orderID'];
					
					$q = mysqli_query($con, "INSERT INTO `shop_orders` (`id`, `orderID`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `tyres`, `price`, `changeDate`, `status`, `misc`, `tyreID`, `customerID`) VALUES (NULL, '', '$orderedOn', '$name', '$regNr', '$mobile', '$email', '', '', '$tyres', '$price', '$changeDate', '$status', '$misc', $tyreID, $customerID)");

				}
				
				if($email != '') {
					$workType = $resArr->workType;
					$services = $resArr->services;
					
					$today = date('Y/m/d H:i');
					$msg = '<html><head></head><body>
		
						Hey '.$name.', <br>
						You have successfully made a '.$workType.' order. <br><br>
						
						Details of order are:<br>
						Customer Name: <b> '.$name.' </b><br>
						Mobile: <b> '.$mobile.'</b><br>
						Reg Nr: <b>'.$regNr.'</b> <br>
						Additional Services: <b>'.$services.'</b><br>
						Date & Time of tyre change: <b>'.$date.' '.$time.' </b> <br>
						Ordered On: <b>'.$today.'</b><br>
						Price: <b>Kr '.$price.'</b><br>
						</body></html>';
					
					$arr = array();
					$arr['to'] = $email; //'dekkhotell.autobutler@gmail.com';
					$arr['toName'] = $name;
					$arr['subject'] = 'Successfully place your order';
					$arr['body'] = $msg;
					$mail = mailSend($arr);
				}
				
				$r = ['success'];
				echo json_encode($r);
				return;
			}else
			if($resArr->result == 'success' && $paymentDone == 0) {
				// PAYMENT START ########################################################################
				$orderID = random_string(10);
				$postData['orderID'] = $orderID;
				$_SESSION['pD'] = $postData;
				
				$acceptURL = 'https://example.org/accept';
				$cancelURL = 'https://example.org/cancel';
				$callbackURL = 'https://example.org/callback';
				
				$arr = [
					'orderID' => $orderID,
					'amount' => (int)$price*100,
					'acceptURL' => $acceptURL,
					'cancelURL' => $cancelURL,
					'callbackURL' => $callbackURL
				];
				$response = makePayment($arr);
				if($response->meta->result) {
					$token = $response->token;
					$url = $response->url;
					$r = ['paySessionSuccess', $token, $url];
					echo json_encode($r);
					return;
				}else {
					$r = ['api error'];
					echo json_encode($r);
					return;
				}
				// PAYMENT END #########################################################################
			}else if($resArr->result == 'already ordered') {
				$r = ['already ordered'];
				echo json_encode($r);
				return;
			}else if($resArr->result == 'no work') {
				$r = ['no work'];
				echo json_encode($r);
				return;
			}else if($resArr->result == 'no employee') {
				$r = ['no employee'];
				echo json_encode($r);
				return;
			}else if($resArr->result == 'failed') {
				$r = ['failed'];
				echo json_encode($r);
				return;
			}
		}
	}
	
	if($type == 'tyreChange') {
		$workType = 'Tyre Change';
	}else if($type == 'tyreBalancing') {
		$workType = 'Tyre Balancing';
	}else if($type == 'tyreRepair') {
		$workType = 'Tyre Repair';
	}else if($type == 'newTyre') {
		$workType = 'New Tyre';
	}else if($type == 'tyreChangeDekkhotell') {
		$workType = 'Tyre Change Dekkhotell';
	}
	
	
	$services = '';
	if($serviceIDs != '') {
		$sIDs = explode(',', $serviceIDs);
		foreach($sIDs as $sID) {
			if($sID == '' || $sID == 'undefined') { continue; }
			$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_misc WHERE property='services' AND id=$sID"), MYSQLI_ASSOC);
			$servicePrice = (int)$fs['attribute2'];
			$totalService = (int)$_POST['service'.$sID] / $servicePrice;
			$services .= $fs['attribute1'].'('.$totalService.'), ';
		}
	}
	
	$brand = $model = $size = $season = $load = $category = $runFlat = '';
	$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID");
	if(mysqli_num_rows($q) == 0) {
		$r = ['no tyre'];
		echo json_encode($r);
		return;
	}else {
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		$brand = $f['brand'];
		$model = $f['model'];
		$size = $f['size'];
		$season = $f['season'];
		$load = $f['load'];
		$category = $f['category'];
		if($f['misc'] != '') {
			$miscT = json_decode($f['misc'], true);
			if(isset($miscT['runFlat'])) {
				$runFlat = $miscT['runFlat'];
			}
		}
	}
	
	$pay = array();
	if($paymentDone == 1 && $paymentMode == 'payNow') {
		$pay['amount'] = p($_POST['amount']);
		$pay['cardNo'] = p($_POST['cardNo']);
		$pay['currency'] = p($_POST['currency']);
		$pay['payDate'] = p($_POST['payDate']);
		$pay['eci'] = p($_POST['eci']);
		$pay['feeID'] = p($_POST['feeID']);
		$pay['hash'] = p($_POST['hash']);
		$pay['issuerCountry'] = p($_POST['issuerCountry']);
		$pay['orderID'] = p($_POST['orderID']);
		$pay['paymentType'] = p($_POST['paymentType']);
		$pay['reference'] = p($_POST['reference']);
		$pay['payTime'] = p($_POST['time']);
		$pay['txnID'] = p($_POST['txnID']);
		
	}
	
	$url = 'http://autobutler.no/management/api/functions.php';
	$postData = [
		'method'=>'tyreShopTyreOrder',
		'workType' => $workType,
		'regNr' => $regNr,
		'name' => $name,
		'mobile' => $mobile,
		'date' => $date,
		'serviceIDs' => $serviceIDs,
		'services' => $services,
		'time' => $time,
		'price' => $price,
		'totalTime' => $totalTime,
		'paymentDone' => $paymentDone,
		'numberOfTyres' => $tyres,
		'tyreSize' => $size,
		'brand' => $brand,
		'model' => $model,
		'pay' => json_encode($pay),
		'paymentMode' => $paymentMode,
		'orgNr' => $orgNr
	];
	
	$response = get_web_page($url, $postData);
	$resArr = array();
	$resArr = json_decode($response);
	if(!is_object($resArr)) {
		$r = ['failed'];
		echo json_encode($r);
		return;
	}else {
		//if($resArr->result == 'success' && $paymentDone == 1 && $paymentMode == 'payNow') {
		if($resArr->result == 'success' && $paymentDone == 1) {
			$misc = array();
			$orderedOn = date('Y/m/d H:i');
			$status = 'Pending';
			$changeDate = $date.' '.$time;
			if($paymentMode == 'payNow') {
				$mode = 'Kort betaling/delbetaling';
			}else if($paymentMode == 'payAtShop') {
				$mode = 'Betaling i butikk';
			}else {
				$mode = 'Firmakunde';
			}
			if(isset($resArr->workOrderID)) {
				$misc['managementWorkOrderID'] = $resArr->workOrderID;
			}
			$misc['paymentMode'] = $mode;
			if($paymentMode == 'orgNr') {
				$misc['orgNr'] = $orgNr;
			}
			$misc['model'] = $model;
			$misc['season'] = $season;
			$misc['runFlat'] = $runFlat;
			$misc['load'] = $load;
			$misc['category'] = $category;
			$misc = json_encode($misc);
			
			$orderID = $_SESSION['orderID'];
			
			$q = mysqli_query($con, "INSERT INTO `shop_orders` (`id`, `orderID`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `tyres`, `price`, `changeDate`, `status`, `misc`, `tyreID`, `customerID`) VALUES (NULL, '$orderID', '$orderedOn', '$name', '$regNr', '$mobile', '$email', '$brand', '$size', '$tyres', '$price', '$changeDate', '$status', '$misc', $tyreID, $customerID)");
			
			$f = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
			$oldStock = $f['stock'];
			$newStock = $oldStock - $tyres;
			
			$q = mysqli_query($con, "UPDATE shop_stock SET stock = $newStock WHERE tyreID=$tyreID");
			if($email != '') {
					$today = date('Y/m/d H:i');
					$msg = '<html><head></head><body>
		
						Hey '.$name.', <br>
						You have successfully made a Dekkskift Dekkhotell Kunde order. <br><br>
						
						Details of order are:<br>
						Customer Name: <b> '.$name.' </b><br>
						Mobile: <b> '.$mobile.'</b><br>
						Reg Nr: <b>'.$regNr.'</b> <br>
						Additional Services: <b>'.$services.'</b><br>
						Date & Time of tyre change: <b>'.$date.' '.$time.' </b> <br>
						Ordered On: <b>'.$today.'</b><br>
						Number of tyres: <b>'.$tyres.'</b></br>
						Price: <b>Kr '.$price.'</b><br>
						</body></html>';
					
					$arr = array();
					$arr['to'] = $email; //'dekkhotell.autobutler@gmail.com';
					$arr['toName'] = $name;
					$arr['subject'] = 'Successfully place your order';
					$arr['body'] = $msg;
					$mail = mailSend($arr);
				}
			
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'already ordered') {
			$r = ['already ordered'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'no work') {
			$r = ['no work'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'no employee') {
			$r = ['no employee'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'success' && $paymentDone != 1) {
			// PAYMENT START ########################################################################
			$orderID = random_string(10);
			$postData['orderID'] = $orderID;
			//$_SESSION['pD'] = $postData;
			$_SESSION['orderID'] = $orderID;
			
			$acceptURL = 'https://example.org/accept';
			$cancelURL = 'https://example.org/cancel';
			$callbackURL = 'https://example.org/callback';
			
			$arr = [
				'orderID' => $orderID,
				'amount' => (int)$price*100,
				'acceptURL' => $acceptURL,
				'cancelURL' => $cancelURL,
				'callbackURL' => $callbackURL
			];
			$response = makePayment($arr);
			if($response->meta->result) {
				$token = $response->token;
				$url = $response->url;
				$r = ['paySessionSuccess', $token, $url];
				echo json_encode($r);
				return;
			}else {
				$r = ['api error'];
				echo json_encode($r);
				return;
			}
			// PAYMENT END #########################################################################
		}
	}

	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function getTimeSlots() {
	$con = dbCon();
	//$orderType = p($_POST['orderType']);
	$orderType = 'Tyre Change';
	$date = p($_POST['date']);
	$day = p($_POST['day']);
	$serviceIDs = p($_POST['serviceIDs']);
	$timeSlots = array();
	$currentTime = date('Hi');
	$currentDate = date('Y/m/d');
	
	$dekkDone = 0;
	$totalTime = 0;
	
	if(isset($_POST['type'])) {
		$type = p($_POST['type']);
		if($type == 'dekk') {
			
			$workType = p($_POST['workType']);
			if($workType == 'tyreChange') { $work = 'Tyre Change'; }
			elseif($workType == 'tyreBalancing') { $work = 'Tyre Balancing'; }
			else if($workType == 'tyreRepair') { $work = 'Tyre Repair'; }
			else if($workType == 'tyreChangeDekkhotell') { $work = 'Tyre Change Dekkhotell'; }
	
			$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
			$postData = ['method'=>'getTimeSlotsForTyreShop', 'workType'=>$work, 'serviceIDs'=>$serviceIDs, 'day'=>$day, 'date'=>$date];
			
			$response = get_web_page($url, $postData);
			$resArr = array();
			$resArr = json_decode($response);

			if(!is_object($resArr)) {
				$r = ['failed'];
				echo json_encode($r);
				return;
			}
			
			if($resArr->result == 'closed') {
				$r = ['closed'];
				echo json_encode($r);
				return;
			}
			$dekkDone = 1;
			$orderType = $work;
			$totalTime = $resArr->totalTime;
			$timeSlots = $resArr->timeSlots;
		}
	}
	
	if($dekkDone == 0) {
		if($serviceIDs != '') {
			$serviceIDs = explode(',', $serviceIDs);
			foreach($serviceIDs as $sid) {
				if($sid == '' || $sid == '0' || $sid == 'undefined') { continue; }
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_misc WHERE property='services' AND id=$sid"), MYSQLI_ASSOC);
				$time = (int)trim($fs['attribute3']);
				$totalTime += $time;
			}
		}
		
		//####--make new tyreChangePrice input in admin or fetch from other site?
		//$fw = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_misc WHERE property='workPriceWithoutLogin' AND attribute1='$orderType'"), MYSQLI_ASSOC);
		//$time = (int)$fw['attribute3'];
		//$totalTime += $time;
		
		$q = mysqli_query($con, "SELECT * FROM shop_misc WHERE property='timesForNormalTyreChangeOrder' AND attribute1='$day'");
		if(mysqli_num_rows($q) > 0) {
			$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
			if($f['attribute2'] != '') {
				$times = explode(',', $f['attribute2']);
				foreach($times as $time) {
					$e = explode(':', $time);
					$hr = $e[0];
					$min = $e[1];
					if($currentTime > $hr.$min && $currentDate == $date) { continue; }
					$timeSlots[] = $hr.$min.'/'.$time;
				}
			}else {
				$r = ['closed'];
				echo json_encode($r);
				return;
			}
		}
		if(count($timeSlots) == 0) { 
			$r = ['closed'];
			echo json_encode($r);	
			return;
		}
		
		sort($timeSlots);
	}
	
	$url = 'http://autobutler.no/management/api/functions.php';
	$postData = [
		'method'=>'checkAvailableTimeSlots', 
		'workType'=>$orderType, 
		'date' => $date,
		'totalTime' => $totalTime,
		'timeSlots' => json_encode($timeSlots),
		'forTyreShop' => $dekkDone
	];
	$response = get_web_page($url, $postData);
	$resArr = array();
	$resArr = json_decode($response);
	
	if(is_object($resArr)) {
		if($resArr->result == 'success') {
			if($resArr->data == '') {
				$r = ['no employee'];
				echo json_encode($r);
				return;
			}else {
				$r = ['success', $resArr->data, $totalTime];
				echo json_encode($r);
				return;
			}
		}
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
		
}

function getServices() {
	$con = dbCon();
	
	if(isset($_POST['type'])) {
		$type = p($_POST['type']);
		if($type == 'dekk') {
			
			$workType = p($_POST['workType']);
			$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
			$postData = ['method'=>'getServicesForTyreShop', 'workType'=>$workType];
			
			$response = get_web_page($url, $postData);
			$resArr = array();
			$resArr = json_decode($response);
			if(!is_object($resArr)) {
				$r = ['failed'];
				echo json_encode($r);
				return;
			}
			
			$r = ['success', $resArr->services, $resArr->price];
			echo json_encode($r);
			return;
		}
	}
	
	$q = mysqli_query($con, "SELECT * FROM shop_misc WHERE property='services'");
	if(mysqli_num_rows($q) > 0) {
		$html = '';
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
			if($f['attribute1'] == '') { continue; }
			
			$maxNum = (int)$f['attribute4'];
			$maxNumOptions = '';
			for($i=1; $i<=$maxNum; $i++) {
				$maxNumOptions .= '<option value="'.$i.'">'.$i.'</option>';
			}
			
			$price = (int)$f['attribute2'];
			$html .= '<div class="serviceBar inactiveService service'.$f['id'].'" onclick="saveService('.$f['id'].', '.$price.')" data-price="'.$price.'" style="" >
						'.$f['attribute1'].'
					<div style="display:inline-block; margin-left:10px; padding-left:10px; padding-right:10px; border-left:1px solid #ccc; border-right:1px solid #ccc;">
						Kr '.$price.'
					</div>
					<select id="maxNum'.$f['id'].'" class="maxNum" data-id="'.$f['id'].'" data-price="'.$price.'" onchange="saveMaxNum('.$f['id'].','.$price.')" onclick="saveService(0,0)" style="color:#333; display:inline-block; margin-left:5px; border:none;">
						'.$maxNumOptions.'
					</select>
						
				</div>';
		}
		
		$r = ['success', $html];
		echo json_encode($r);
		return;
	}
	
	//$r = ['failed'];
	//echo json_encode($r);
	//return;
}

/*
function fetchFrontTyres() {
	
	$season = p($_POST['season']);
	$sizeOne = p($_POST['sizeOne']);
	$sizeTwo = p($_POST['sizeTwo']);
	$sizeThree = p($_POST['sizeThree']);
	$size = $sizeOne.'/'.$sizeTwo.'-'.$sizeThree;
	
	$con = dbCon();
	
	$q = mysqli_query($con, "SELECT `id` FROM shop_tyres");
	if(mysqli_num_rows($q) > 0) {
		
		$budgetTyre = '';
		$mellomTyre = '';
		$premiumTyre = '';
		
		//budget tyre
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE `season`='$season' AND `size` LIKE '%$size%' AND `category`='budget' ");
		if(mysqli_num_rows($q) > 0) {
			
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
				
				$border = '';
				$badge = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = 'border-danger';
					$badge = '<span class="badge  badge-danger p-2" style="position:absolute; top:0; left:0; border-radius:0; ">RECOMMENDED</span>';
				}
				
				$budgetTyre .= '<div class="card mx-1 '.$border.'" style="max-width:250px; border-radius:2px; box-shadow:0px 2px 2px #aaa;">
								  <!--<div class="card-header text-center" style="font-weight:bold;">BUDGET</div>-->
									<img class="card-img-top" src="uploads/tyreImg/'.$f['image'].'" style="max-width:230px; margin:auto; width:auto;">
									<!--<div class="" style="width:100px; height:20px; background-color:red; color:#fff; position:absolute; margin-top:50px;">Abefalt</div>-->
									<div class="card-body">
										'.$badge.'
										<h5 class="card-title" style="font-weight:500; margin-bottom:5px;">'.$f['brand'].'</h5>
										<h6 style="">'.$f['size'].'</h6>
										<p class="card-text" style="margin-top:15px; margin-bottom:5px;">Egenskaper fra</p>
										  
										<div style="border:0px solid #aaa; border-right:none; border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/fuel.jpg" title="Drivstofforbruk" style="width:35px;"></img>
										</div><div style="text-transform:uppercase; border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block; margin-right:10px;">
											'.$f['fuel'].'
										</div>
										  
										<div style="border:0px solid #aaa; border-right:none;  border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/noise.jpg" title="Støynivå" style="width:35px;"></img>
										</div><div style="border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block;  margin-right:10px;">
											'.$f['noise'].'
										</div> 
										 
										<div style="border:0px solid #aaa; border-right:none;border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/grip.jpg" title="Våtgrep" style="width:35px;"></img>
										</div><div style="text-transform:uppercase; border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block;">
											'.$f['grip'].'
										</div>
										
										<p class="card-text" style="font-weight:500; margin-top:10px;">NOK '.$f['price'].'.-</p>
									  
									  <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
									  <!--<button class="btn btn-sm btn-warning btn-block font-weight-bold rounded-0" href="#" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Se Tilbud</button>-->
									  <a class="btn btn-sm btn-warning btn-block font-weight-bold rounded-0" href="?p=product&pID='.$f['id'].'" style="">Se Tilbud</a>
									</div>
								  </div>';
			}
		}
		
		//mellom tyres
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE season='$season' AND `size` LIKE '%$size%' AND category='mellom' ");
		if(mysqli_num_rows($q) > 0) {
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
				
				$border = '';
				$badge = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = 'border-danger';
					$badge = '<span class="badge  badge-danger p-2" style="position:absolute; top:0; left:0; border-radius:0; ">RECOMMENDED</span>';
				}
				
				$mellomTyre .= '<div class="card mx-1 '.$border.'" style="max-width:250px; border-radius:2px; box-shadow:0px 2px 2px #aaa;">
								  <!--<div class="card-header text-center" style="font-weight:bold;">BUDGET</div>-->
									<img class="card-img-top" src="uploads/tyreImg/'.$f['image'].'" style="max-width:230px; margin:auto; width:auto;">
									<!--<div class="" style="width:100px; height:20px; background-color:red; color:#fff; position:absolute; margin-top:50px;">Abefalt</div>-->
									<div class="card-body">
										'.$badge.'
										<h5 class="card-title" style="font-weight:500; margin-bottom:5px;">'.$f['brand'].'</h5>
										<h6 style="">'.$f['size'].'</h6>
										<p class="card-text" style="margin-top:15px; margin-bottom:5px;">Egenskaper fra</p>
										  
										<div style="border:0px solid #aaa; border-right:none; border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/fuel.jpg" title="Drivstofforbruk" style="width:35px;"></img>
										</div><div style="text-transform:uppercase; border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block; margin-right:10px;">
											'.$f['fuel'].'
										</div>
										  
										<div style="border:0px solid #aaa; border-right:none;  border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/noise.jpg" title="Støynivå" style="width:35px;"></img>
										</div><div style="border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block;  margin-right:10px;">
											'.$f['noise'].'
										</div> 
										 
										<div style="border:0px solid #aaa; border-right:none;border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/grip.jpg" title="Våtgrep" style="width:35px;"></img>
										</div><div style="text-transform:uppercase; border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block;">
											'.$f['grip'].'
										</div>
										
										<p class="card-text" style="font-weight:500; margin-top:10px;">NOK '.$f['price'].'.-</p>
									  
									  <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
									 <!--<button class="btn btn-sm btn-warning btn-block font-weight-bold rounded-0" href="#" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Se Tilbud</button>-->
									  <a class="btn btn-sm btn-warning btn-block font-weight-bold rounded-0" href="?p=product&pID='.$f['id'].'" style="">Se Tilbud</a>
									</div>
								  </div>';
			}
		}
		
		//premium tyres
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE season='$season' AND `size` LIKE '%$size%' AND category='premium' ");
		if(mysqli_num_rows($q) > 0) {
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
				
				$border = '';
				$badge = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = 'border-danger';
					$badge = '<span class="badge  badge-danger p-2" style="position:absolute; top:0; left:0; border-radius:0; ">RECOMMENDED</span>';
				}
				
				$premiumTyre .= '<div class="card mx-1 '.$border.'" style="max-width:250px; border-radius:2px; box-shadow:0px 2px 2px #aaa;">
								  <!--<div class="card-header text-center" style="font-weight:bold;">BUDGET</div>-->
									<img class="card-img-top" src="uploads/tyreImg/'.$f['image'].'" style="max-width:230px; margin:auto; width:auto;">
									<!--<div class="" style="width:100px; height:20px; background-color:red; color:#fff; position:absolute; margin-top:50px;">Abefalt</div>-->
									<div class="card-body">
										'.$badge.'
										<h5 class="card-title" style="font-weight:500; margin-bottom:5px;">'.$f['brand'].'</h5>
										<h6 style="">'.$f['size'].'</h6>
										<p class="card-text" style="margin-top:15px; margin-bottom:5px;">Egenskaper fra</p>
										  
										<div style="border:0px solid #aaa; border-right:none; border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/fuel.jpg" title="Drivstofforbruk" style="width:35px;"></img>
										</div><div style="text-transform:uppercase; border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block; margin-right:10px;">
											'.$f['fuel'].'
										</div>
										  
										<div style="border:0px solid #aaa; border-right:none;  border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/noise.jpg" title="Støynivå" style="width:35px;"></img>
										</div><div style="border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block;  margin-right:10px;">
											'.$f['noise'].'
										</div> 
										 
										<div style="border:0px solid #aaa; border-right:none;border-radius:2px 0px 0px 2px; background-color:#ddd; display:inline-block;">
											<img src="images/grip.jpg" title="Våtgrep" style="width:35px;"></img>
										</div><div style="text-transform:uppercase; border:1px solid #aaa; font-size:13px; padding:0px 5px 2px 4px; vertical-align:top; border-radius:2px 2px; background-color:#fff; display:inline-block;">
											'.$f['grip'].'
										</div>
										
										<p class="card-text" style="font-weight:500; margin-top:10px;">NOK '.$f['price'].'.-</p>
									  
									  <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
									  <!--<button class="btn btn-sm btn-warning btn-block font-weight-bold rounded-0" href="#" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Se Tilbud</button>-->
									  <a class="btn btn-sm btn-warning btn-block font-weight-bold rounded-0" href="?p=product&pID='.$f['id'].'" style="">Se Tilbud</a>
									  
									</div>
								  </div>';
			}
		}
		
		if($budgetTyre != '' || $mellomTyre != '' || $premiumTyre != '') {
			$r = ['success', $budgetTyre, $mellomTyre, $premiumTyre];
			echo json_encode($r);
			return;
		}else {
			$r = ['no entry'];
			echo json_encode($r);
			return;
		}
	}
	
	$r = ['error'];
	echo json_encode($r);
	return;
}
*/


function fetchFrontTyres() {
	
	$season = p($_POST['season']);
	$sizeOne = p($_POST['sizeOne']);
	$sizeTwo = p($_POST['sizeTwo']);
	$sizeThree = p($_POST['sizeThree']);
	$size = $sizeOne.'/'.$sizeTwo.'-'.$sizeThree;
	
	$con = dbCon();
	
	$q = mysqli_query($con, "SELECT `id` FROM shop_tyres");
	if(mysqli_num_rows($q) > 0) {
		
		$budgetTyre = '';
		$mellomTyre = '';
		$premiumTyre = '';
		$budgetNum = $mellomNum = $premiumNum = 0;
		
		//budget tyre
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE `season`='$season' AND `size` LIKE '%$size%' AND `category`='budget'");
		if(mysqli_num_rows($q) > 0) {
			
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
				
				$tyreID = $f['id'];
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
				$stock = $fs['stock'];
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="" >Buy</div>';
				}
				
				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = 'box-shadow:0px 6px 6px 2px #405823; border:2px solid #000;';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}
				
				$budgetTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url(\'uploads/tyreImg/'.$f['image'].'\') no-repeat center; background-size:contain; background-color:#fff;">
											
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$f['brand'].' </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> '.$f['size'].' '.$f['load'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="text-transform:uppercase; display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['fuel'].'</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['noise'].'</div>
												</div><div class="iconContainer icon3" style="">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="text-transform:uppercase; display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['grip'].'</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK '.$f['price'].'</b></div>
											<div class="tyreButtons" style="">
												'.$buyButton.'
												<a href="?p=productDetails&pID='.$f['id'].'"><div class="detailsButton" style="">Details</div></a>
											</div>
										</div>
								</div>';
				$budgetNum++;
			}
		}
		
		//mellom tyres
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE season='$season' AND `size` LIKE '%$size%' AND category='mellom' ");
		if(mysqli_num_rows($q) > 0) {
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
				
				$tyreID = $f['id'];
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
				$stock = $fs['stock'];
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="" >Buy</div>';
				}
				
				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = 'box-shadow:0px 6px 6px 2px #405823; border:2px solid #000;';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}
				
				$mellomTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url(\'uploads/tyreImg/'.$f['image'].'\') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$f['brand'].' </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> '.$f['size'].' '.$f['load'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="text-transform:uppercase; display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['fuel'].'</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['noise'].'</div>
												</div><div class="iconContainer icon3" style="">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="text-transform:uppercase; display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['grip'].'</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK '.$f['price'].'</b></div>
											<div class="tyreButtons" style="">
												'.$buyButton.'
												<a href="?p=productDetails&pID='.$f['id'].'"><div class="detailsButton" style="">Details</div></a>
											</div>
										</div>
								</div>';
				$mellomNum++;
			}
		}
		
		//premium tyres
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE season='$season' AND `size` LIKE '%$size%' AND category='premium' ");
		if(mysqli_num_rows($q) > 0) {
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
				
				$tyreID = $f['id'];
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
				$stock = $fs['stock'];
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="">Buy</div>';
				}
				
				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = 'box-shadow:0px 6px 6px 2px #405823; border:2px solid #000;';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}
				
				$premiumTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url(\'uploads/tyreImg/'.$f['image'].'\') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$f['brand'].' </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> '.$f['size'].' '.$f['load'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="text-transform:uppercase; display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['fuel'].'</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['noise'].'</div>
												</div><div class="iconContainer icon3" style="">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="text-transform:uppercase; display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">'.$f['grip'].'</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK '.$f['price'].'</b></div>
											<div class="tyreButtons" style="">
												'.$buyButton.'
												<a href="?p=productDetails&pID='.$f['id'].'"><div class="detailsButton" style="">Details</div></a>
											</div>
										</div>
								</div>';
				$premiumNum++;
			}
		}
		
		if($budgetTyre != '' || $mellomTyre != '' || $premiumTyre != '') {
			$r = ['success', $budgetTyre, $mellomTyre, $premiumTyre, $budgetNum, $mellomNum, $premiumNum];
			echo json_encode($r);
			return;
		}else {
			$r = ['no entry'];
			echo json_encode($r);
			return;
		}
	}
	
	$r = ['error'];
	echo json_encode($r);
	return;
}












?>