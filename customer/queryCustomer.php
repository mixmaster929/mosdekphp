<?php
session_start();
date_default_timezone_set('Europe/Oslo');
ini_set('max_execution_time', 0);
set_time_limit(0);
 ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../includes/functions.php');

$method = '';
if(isset($_POST['method'])) { $method = p($_POST['method']); }

switch ($method) {
	case 'showAllDekkhotelTyres':
		showAllDekkhotelTyres();
		break;
	case 'showAllTyreChangeOrders':
		showAllTyreChangeOrders();
		break;
	case 'showAllPurchaseOrders':
		showAllPurchaseOrders();
		break;
	case 'orderSearchForCustomer':
		orderSearchForCustomer();
		break;
	case 'saveCustomerInfo':
		saveCustomerInfo();
		break;
	case 'sendMessage':
		sendMessage();
		break;
	case 'logoutCustomer':
		logoutCustomer();
		break;
	default: echo '<script> alert("You are now being Tracked"); </script>'; die;
}

function showAllDekkhotelTyres() {
	$con = dbCon();
	$customerID = (int)$_SESSION['customerID'];
	
	$fc = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_customers WHERE id=$customerID"), MYSQLI_ASSOC);
	$regNr = $fc['regNr'];
	
	$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
	$postData = [
		'method' => 'getDekkhotellTyresForTyreShopCustomer',
		'regNr' => $regNr
		];
	$response = get_web_page($url, $postData);
	$resArr = json_decode($response);
	if(!is_object($resArr)) {
		$r = ['api error'];
		echo json_encode($r);
		return;
	}
	
	if($resArr->result == 'success') {
		$r = ['success', $resArr->html];
		echo json_encode($r);
		return;
	}
	else if($resArr->result = 'no entry') {
		$r = ['no entry'];
		echo json_encode($r);
		return;
	}
	else {
		$r = ['failed'];
		echo json_encode($r);
		return;
	}
	
	
}

function showAllTyreChangeOrders() {
	$con = dbCon();
	$customerID = (int)$_SESSION['customerID'];

	$regNrs = array();
	$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE customerID=$customerID AND brand='' AND size=''");
	if(mysqli_num_rows($q) > 0) {
		
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
			
			$misc = json_decode($f['misc'], true);
			$workOrderID = $misc['managementWorkOrderID'];
			
			$regNrs[] = $f['regNr'].'-'.$workOrderID;
		}
	}else {
		$r = ['no entry'];
		echo json_encode($r);
		return;
	}

	$url = 'http://autobutler.no/management/api/functions.php';
	$postData = [
		'method' => 'fetchTyreChangeOrderDetailsForTyreShop',
		'regNrs' => json_encode($regNrs),
		'type' => 'shopDekk'
		];
	$response = get_web_page($url, $postData);
	$resArr = json_decode($response);
	if(!is_object($resArr)) {
		$r = ['api error'];
		echo json_encode($r);
		return;
	}

	if($resArr->result == 'success') {
		$r = ['success', $resArr->html];
		echo json_encode($r);
		return;
	}
	else if($resArr->result == 'failed') {
		$r = ['failed'];
		echo json_encode($r);
		return;
	}	
}

function showAllPurchaseOrders() {
	$con = dbCon();
	$customerID = (int)$_SESSION['customerID'];

	$tr = '';
	$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE customerID = $customerID AND brand != '' AND size != ''");
	if(mysqli_num_rows($q) > 0) {
		
		$i = 1;
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
			
			$model = $runFlat = $load = $category = $season = '';
			if($f['misc'] != '') {
				$misc = json_decode($f['misc'], true);
				if(isset($misc['model'])) {
					$model = $misc['model'];
					$runFlat = $misc['runFlat'];
					$load = $misc['load'];
					$category = $misc['category'];
					
					if($misc['season'] == 'summer') { $season = 'SommerDekk'; }
					else if($misc['season'] == 'winter') { $season = 'Winter-Piggfrie'; }
					else if($misc['season'] == 'winterStudded') { $season = 'Winter-PiggDekk'; }
				}
			
			}
			
			$tr .= '<tr>';
			$tr .= '<td>'.$i.'</td>';
			$tr .= '<td>'.$f['orderedOn'].'</td>';
			$tr .= '<td>'.$f['tyres'].'</td>';
			$tr .= '<td>'.$f['size'].'</td>';
			$tr .= '<td>'.$f['brand'].'</td>';
			$tr .= '<td>'.$model.'</td>';
			$tr .= '<td>'.$season.'</td>';
			$tr .= '<td>'.$runFlat.'</td>';
			$tr .= '<td>'.$load.'</td>';
			$tr .= '<td>'.$category.'</td>';
			$tr .= '</tr>';
			$i++;
		}
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	else {
		$r = ['no entry'];
		echo json_encode($r);
		return;
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function orderSearchForCustomer() {
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	$customerID = (int)$_SESSION['customerID'];
	
	if(empty($type) || empty($value)) {
		$r = ['empty'];
		echo json_encode($r);
		return;
	}
	$con = dbCon();
	
	if($type == 'purchaseDate') {
		$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE customerID=$customerID AND orderedOn LIKE '%$value%'");
	}
	else {
		$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE brand LIKE '%$value%' AND customerID = $customerID");
	}
	if(mysqli_num_rows($q) == 0) {
		$r = ['no entry'];
		echo json_encode($r);
		return;
	} else {
	
		$tr = '';
		$i = 1;
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
				
				if($f['misc'] != '') {
					$misc = json_decode($f['misc'], true);
					$model = $misc['model'];
					$runFlat = $misc['runFlat'];
					$load = $misc['load'];
					$category = $misc['category'];
					
					if($misc['season'] == 'summer') { $season = 'SommerDekk'; }
					else if($misc['season'] == 'winter') { $season = 'Winter-Piggfrie'; }
					else if($misc['season'] == 'winterStudded') { $season = 'Winter-PiggDekk'; }
				
				}
				
				$tr .= '<tr>';
				$tr .= '<td>'.$i.'</td>';
				$tr .= '<td>'.$f['orderedOn'].'</td>';
				$tr .= '<td>'.$f['tyres'].'</td>';
				$tr .= '<td>'.$f['size'].'</td>';
				$tr .= '<td>'.$f['brand'].'</td>';
				$tr .= '<td>'.$model.'</td>';
				$tr .= '<td>'.$season.'</td>';
				$tr .= '<td>'.$runFlat.'</td>';
				$tr .= '<td>'.$load.'</td>';
				$tr .= '<td>'.$category.'</td>';
				$tr .= '</tr>';
				$i++;
			}
			
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
		
}

function saveCustomerInfo() {
	$con = dbCon();
	
	if(p($_POST['password']) != '') {
		$passQuery = "password='".md5(p($_POST['password']))."', ";
	}else {
		$passQuery = "";
	}
	$password = md5(p($_POST['password']));
	$fullName = p($_POST['fullName']);
	$email = p($_POST['email']);
	$mobile = p($_POST['mobile']);
	$address = p($_POST['address']);
	$city = p($_POST['city']);
	$postCode = p($_POST['postCode']);
	$customerID = (int)p($_POST['id']);
	
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE id=$customerID");
	if(mysqli_num_rows($q) == 0) {
		$r = ['no customer'];
		echo json_encode($r);
		return;
	}
	
	$q = mysqli_query($con, "UPDATE shop_customers SET ".$passQuery." fullName='$fullName', mobile='$mobile', email='$email', address='$address', city='$city', postCode='$postCode' WHERE id=$customerID ");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function sendMessage()
{
	
	$con = dbCon();
	$customerID = p($_POST['id']);
	$subject = p($_POST['subject']);
	$message = p($_POST['message']);
	$regNr = p($_POST['regNr']);
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE id=$customerID");

	if(mysqli_num_rows($q) == 0){
		$r = ['error'];
		echo json_encode($r);
		return;
	}
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);

	$name = $fetch[0]['username'];
	$email = $fetch[0]['email'];
	$mobile = $fetch[0]['mobile'];
	$t = time();
	$date = date("Y/m/d h:m",$t);
	$q = mysqli_query($con, "INSERT INTO shop_contact (`id`, `name`, `email`, `date`, `phone`, `subject`, `message`, `reg_nr`, `misc`,`customerID`) VALUES (NULL, '$name', '$email', '$date','$mobile','$subject','$message','$regNr','',$customerID)");
    
	if($q)
	{
		echo 'success';
		
	}
	else{
		echo "error";
	}
	return;
}
function logoutCustomer() {
	session_destroy();
	echo 'success';
	return;
}

?>