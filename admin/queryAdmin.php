<?php
session_start();
date_default_timezone_set('Europe/Oslo');
ini_set('max_execution_time', 0);
set_time_limit(0);

include('../includes/functions.php');

$method = '';
if(isset($_POST['method'])) { $method = p($_POST['method']); }

switch ($method) {
	case 'customerSearch':
		customerSearch();
		break;
	case 'showAllCustomer':
		showAllCustomer();
		break;
	case 'deleteAdmin':
		deleteAdmin();
		break;
	case 'saveAdmin':
		saveAdmin();
		break;
    case 'addRows':
        addRows();
        break;
	case 'addAdmin':
		addAdmin();
		break;
	case 'logoutAdmin':
		logoutAdmin();
		break;
	case 'deleteRow':
		deleteRow();
		break;
    case 'getLocation':
        getLocation();
        break;
	case 'stockSearch':
		stockSearch();
		break;
	case 'saveStockInfo':
		saveStockInfo();
		break;
	case 'saveCustomerInfo':
		saveCustomerInfo();
		break;
	case 'massDelete':
		massDelete();
		break;
	case 'showStock':
		showStock();
		break;
	case 'showAllOrder':
		showAllOrder();
		break;
	case 'showQueries':
		showQueries();
		break;
	case 'querySearch':
		querySearch();
		break;
	case 'querySeen':
		querySeen();
		break;
	case 'saveTyreInfo':
		saveTyreInfo();
		break;
	case 'orderSearch':
		orderSearch();
		break;
	case 'tyreSearch':
		tyreSearch();
		break;
	case 'tyreSearchApi';
		tyreSearchApi();
		break;	
    case 'invoiceSearch':
        invoiceSearch();
        break;
	case 'pdfDownloadSummary':
		pdfDownloadSummary();
		break;
    case 'pdfDownload':
        pdfDownloadSingle();
        break;
    case 'pdfDownloadMultiple':
        pdfDownloadMultiple();
        break;
    case 'doPay':
        doPay();
        break;
	case 'addTyre':
		addTyre();
		break;
	case 'saveTime':
		saveTime();
		break;
	case 'saveService':
		saveService();
		break;
    case 'saveBank':
        saveBank();
        break;
	case 'addService':
		addService();
		break;
	case 'brand':
		brandService();
		break;
	case 'saveBrandInfo':
		saveBrandInfo();
		break;
	case 'sendMessage':
		sendMessage();
		break;
	case 'saveNorgesDekk':
		saveNorgesDekk();
		break;	
	case 'addBrandDiscount':
		addBrandDiscount();
		break;		
	case 'getTyresFromSFTP':
		getTyresFromSFTP();
		break;
	case 'listTyresFromSFTP':
		listTyresFromSFTP(); 
		break;		
		
		
	default: echo '<script> alert("You are now being Tracked"); </script> '; die;
}
function RegNr($value){
     //check if regnr has stored tyre
    // remove , from $value
    $value = str_replace(',', '', $value);
    $url = 'http://autobutler.no/dekkhotell/query_mossdekk.php';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'value' => $value
    )
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    // return the result returned by the other server 1 or 0.
      return $response;
}
function saveBrandInfo(){
	$con = dbCon();
	$id = p($_POST['id']);
	$brand = p($_POST['brand']);
	$model = p($_POST['model']);	
	$speed = p($_POST['speed']);
	$load = p($_POST['load']);
	$category = p($_POST['category']);
	$forbru = p($_POST['forbru']);
	$niva = p($_POST['niva']);
	$grep = p($_POST['grep']);
	$season = p($_POST['season']);
	$tyreInfo = p($_POST['tyreInfo']);
	$uploadImage = 1;
	if(!empty($_FILES)) {	
		if($_FILES['image']['error'] != 0 || $_FILES['image']['size'] == 0) {
			$uploadImage = 0;
		}else {
			$type = explode('/', $_FILES['image']['type']);
			if($type[0] != 'image') { 
				$r = ['image required'];
				echo json_encode($r);
				return; 
			}
		}
	}

	$q = mysqli_query($con, "UPDATE shop_brand SET `brand_name`='$brand', `model_name`='$model',
	 `category`='$category', `speed`='$speed', `load`='$load', `drivstoff_forbruk`='$forbru', 
	 `stoy_niva`='$niva', `vat_grep`='$grep' , `season`='$season', `tyre_info` = '$tyreInfo' WHERE id=$id");
	if($q) {

		if(!empty($_FILES)) {	
			if($uploadImage == 1) {
				$today = date('Y-m-d-Hi');
				if($type[1] == 'png') { $ext = 'png'; } else { $ext = 'jpg'; }
				$picName ='@'.$today.'.'.$ext.'';
				move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/tyreImg/'.$picName);
				
				$q = mysqli_query($con, "UPDATE shop_brand SET `picture`='$picName' WHERE id=$id");
			}
		}
		$r = ['success'];
		echo json_encode($r);
		return;
	}

	$r = ['Faild'];
		echo json_encode($r);
		return;

	return;

}

function showAllCustomer() {
	$con = dbCon();
	$tr = '';
	$i = 1;
	$storedCounter = 0;
	$notStoredCounter = 0;
    $sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "WHERE location = 'Moss Dekk AS'";
    $q = mysqli_query($con, "SELECT * FROM shop_customers $sqlWhere");
	if(mysqli_num_rows($q) > 0) {
		
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
		    $storedTire = RegNr($f['regNr']);
		    if($storedTire >= 1){ 
		        
		        $storedCounter++;
		        $storedStatusDiv = "<div class='greencircle'>".$storedCounter."</div>";
		    }
		    else {
		    $notStoredCounter++;    
            $storedStatusDiv = "<div class='redcircle'>".$notStoredCounter."</div>";
		    }
			$button = '<input type="checkbox" class="customerCheckbox" data-mobile="'.$f['mobile'].'">&nbsp;
			            <button class="btn btn-sm btn-warning text-white py-0 m-1 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
						<button class="btn btn-sm btn-success text-white py-0 m-1 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;
						<button class="btn btn-sm btn-danger text-white py-0 m-1 deleteButton'.$f['id'].' button'.$f['id'].'" onclick="deleteCustomer('.$f['id'].')">Delete</button>';
			
			$tr .= '<tr id="tr'.$f['id'].'">';
			$tr .= '<td>'.$i.'</td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['location'].'</span></td>';
			$tr .= '<td>'.$storedStatusDiv.'</td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['fullName'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['fullName'].'" id="fullName'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['username'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['username'].'" id="username'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td title="'.$f['password'].'"><input type="password" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="" id="password'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['email'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['email'].'" id="email'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span id="spanPhoneNr" class="txtField' . $f['id'] . '" onclick="sendSMS(\'' . $f['mobile'] . '\')">' . $f['mobile'] . '</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['mobile'].'" id="mobile'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['regNr'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['regNr'].'" id="regNr'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['postCode'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['postCode'].'" id="postCode'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['address'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['address'].'" id="address'.$f['id'].'" style="display:none;"></td>';		
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['city'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['city'].'" id="city'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['price'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['price'].'" id="price'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['carType'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['carType'].'" id="carType'.$f['id'].'" style="display:none;"></td>';
			// $tr .= '<td>'.$f['email'].'</td>';
			// $tr .= '<td>'.$f['mobile'].'</td>';
			// $tr .= '<td>'.$f['regNr'].'</td>';
			// $tr .= '<td>'.$f['postCode'].'</td>';
			// $tr .= '<td>'.$f['address'].' '.$f['city'].'</td>';
			
			$tr .= '<td>'.$button.'</td>';
			$tr .= '</tr>';
			$i++;
		}
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	$r = ['no entries'];
	echo json_encode($r);
	return;
}


function customerSearch() {
	$con = dbCon();
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	
	if($type == '' || $value == '') {
		$r = ['empty'];
		echo json_encode($r);
		return;
	}
    $sqlWhere = ($_SESSION['superAdmin'] == 1) ? "WHERE" : "WHERE location = 'Moss Dekk AS' AND ";

	$q = mysqli_query($con, "SELECT * FROM shop_customers $sqlWhere $type LIKE '%$value%'");
	if(mysqli_num_rows($q) > 0) {
		$tr = '';
		$i = 1;
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		// foreach($fetch as $f) {
		// 	$button = '<button class="btn btn-sm btn-danger text-white py-0 m-0 deleteButton'.$f['id'].' button'.$f['if'].'" onclick="deleteCustomer('.$f['id'].')">Delete</button>';
		
		// 	$tr .= '<tr id="tr'.$f['id'].'">';
		// 	$tr .= '<td>'.$i.'</td>';
		// 	$tr .= '<td>'.$f['fullName'].'</td>';
		// 	$tr .= '<td>'.$f['username'].'</td>';
		// 	$tr .= '<td title="'.$f['password'].'"></td>';
		// 	$tr .= '<td>'.$f['email'].'</td>';
		// 	$tr .= '<td>'.$f['mobile'].'</td>';
		// 	$tr .= '<td>'.$f['regNr'].'</td>';
		// 	$tr .= '<td>'.$f['postCode'].'</td>';
		// 	$tr .= '<td>'.$f['address'].'</td>';
		// 	$tr .= '<td>'.$f['city'].'</td>';
		// 	//$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span><input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		// 	//$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span><input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
		// 	$tr .= '<td>'.$button.'</td>';
		// 	$tr .= '</tr>';
		// 	$i++;
		// }
		foreach($fetch as $f) {
		    $storedTire = RegNr($f['regNr']);
		    if($storedTire >= 1){ 
		        
		        $storedCounter++;
		        $storedStatusDiv = "<div class='greencircle'>".$storedCounter."</div>";
		    }
		    else {
		    $notStoredCounter++;    
            $storedStatusDiv = "<div class='redcircle'>".$notStoredCounter."</div>";
		    }
			$button = '<input type="checkbox" class="customerCheckbox" data-mobile="'.$f['mobile'].'">&nbsp;
			            <button class="btn btn-sm btn-warning text-white py-0 m-1 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
						<button class="btn btn-sm btn-success text-white py-0 m-1 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;
						<button class="btn btn-sm btn-danger text-white py-0 m-1 deleteButton'.$f['id'].' button'.$f['id'].'" onclick="deleteCustomer('.$f['id'].')">Delete</button>';
			
			$tr .= '<tr id="tr'.$f['id'].'">';
			$tr .= '<td>'.$i.'</td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['location'].'</span></td>';
			$tr .= '<td>'.$storedStatusDiv.'</td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['fullName'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['fullName'].'" id="fullName'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['username'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['username'].'" id="username'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td title="'.$f['password'].'"><input type="password" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="" id="password'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['email'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['email'].'" id="email'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span id="spanPhoneNr" class="txtField' . $f['id'] . '" onclick="sendSMS(\'' . $f['mobile'] . '\')">' . $f['mobile'] . '</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['mobile'].'" id="mobile'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['regNr'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['regNr'].'" id="regNr'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['postCode'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['postCode'].'" id="postCode'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['address'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['address'].'" id="address'.$f['id'].'" style="display:none;"></td>';		
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['city'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['city'].'" id="city'.$f['id'].'" style="display:none;"></td>';		
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['price'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['price'].'" id="price'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['carType'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['carType'].'" id="carType'.$f['id'].'" style="display:none;"></td>';
			$tr .= '<td>'.$button.'</td>';
			$tr .= '</tr>';
			$i++;
		}
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	$r = ['no entries'];
	echo json_encode($r);
	return;
}


function deleteAdmin() {
	$con = dbCon();
	$adminID = (int)p($_POST['i']);
	$site = p($_POST['site']);
	
	if($site == 'warehouse') {
		$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
		$postData = [
			'method' => 'deleteAdmin',
			'adminID' => $adminID
		];
		$response = get_web_page($url, $postData);
		$resArr = json_decode($response);
		if(!is_object($resArr)) {
			$r = ['api error'];
			echo json_encode($r);
			return;
		}
		if($resArr->result == 'success') {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
	}
	else if($site == 'management') {
		$url = 'http://autobutler.no/management/api/functions.php';
		$postData = [
			'method' => 'deleteAdmin',
			'adminID' => $adminID
		];
		$response = get_web_page($url, $postData);
		$resArr = json_decode($response);
		if(!is_object($resArr)) {
			$r = ['api error'];
			echo json_encode($r);
			return;
		}
		if($resArr->result == 'success') {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function addRows() {
    $con = dbCon();
    $locationID = p($_POST['locationID']);

    $q = mysqli_query($con, "SELECT * FROM shop_misc WHERE property='timesForNormalTyreChangeOrder' AND locationID='$locationID'");
    if(mysqli_num_rows($q) > 0) {
        $r = ['exist'];
        echo json_encode($r);
        return;
    }

    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Monday', '', '', '', '')");
    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Tuesday', '', '', '', '')");
    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Wednesday', '', '', '', '')");
    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Thursday', '', '', '', '')");
    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Friday', '', '', '', '')");
    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Saturday', '', '', '', '')");
    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'timesForNormalTyreChangeOrder', 'Sunday', '', '', '', '')");
    if($q) {
        $r = ['success'];
        echo json_encode($r);
        return;
    }

    $r = ['failed'];
    echo json_encode($r);
    return;
}

function saveAdmin() {
	$con = dbCon();
	$username = p($_POST['username']);
	$password = md5(p($_POST['password']));
	$passwordSHA = sha1(p($_POST['password']));
	//$email = p($_POST['email']);
	$site = p($_POST['site']);
	$adminID = (int)p($_POST['id']);
	
	if($username == '' || $username == '' ) { $r = ['empty']; echo json_encode($r); return; }
	
	if($site == 'Tyre') {
		$q = mysqli_query($con, "SELECT * FROM shop_admins WHERE id=$adminID");
		if(mysqli_num_rows($q) == 0) {
			$r = ['not found'];
			echo json_encode($r);
			return;
		}
		$q = mysqli_query($con, "SELECT * FROM shop_admins WHERE username='$username' AND password='$password' AND id != $adminID");
		if(mysqli_num_rows($q) > 0) {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		$q = mysqli_query($con, "UPDATE shop_admins SET `username`='$username', `password`='$password' WHERE id=$adminID");
		if($q) {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
	}
	else if($site == 'Dekk') {
		$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
		$postData = [
			'method' => 'saveAdmin',
			'username' => $username,
			'password' => $password,
			//'email' => $email,
			'adminID' => $adminID
		];
		$response = get_web_page($url, $postData);
		$resArr = json_decode($response);
		if(!is_object($resArr)) {
			$r = ['api error'];
			echo json_encode($r);
			return;
		}
		if($resArr->result == 'success') {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'not found') {
			$r = ['not found'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'exists') {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		
	}
	else if($site == 'Manage') {
		$url = 'http://autobutler.no/management/api/functions.php';
		$postData = [
			'method' => 'saveAdmin',
			'username' => $username,
			'password' => $password,
			//'email' => $email,
			'adminID' => $adminID
		];
		$response = get_web_page($url, $postData);
		$resArr = json_decode($response);
		if(!is_object($resArr)) {
			$r = ['api error'];
			echo json_encode($r);
			return;
		}
		if($resArr->result == 'success') {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'not found') {
			$r = ['not found'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'exists') {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function addAdmin() {
	$con = dbCon();
	$username = p($_POST['username']);
	$password = md5(p($_POST['password']));
	$location = p($_POST['location']);
	$superAdmin = p($_POST['superAdmin']);
	//$email = p($_POST['email']);
	$site = p($_POST['site']);
	
	if($username == '' || $username == '' ) { $r = ['empty']; echo json_encode($r); return; }
	
	if($site == 'tyreShop') {
		$q = mysqli_query($con, "SELECT * FROM shop_admins WHERE username='$username' AND password='$password' and location = '$location'");
		if(mysqli_num_rows($q) > 0) {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		
		$q = mysqli_query($con, "INSERT INTO shop_admins (`id`, `username`, `password`, `location`,`superAdmin`) VALUES (NULL, '$username', '$password', '$location','$superAdmin' )");
		if($q) {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		$r = ['failed'];
		echo json_encode($r);
		return;
	}
	else if($site == 'warehouse') {
		$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
		$postData = [
			'method' => 'addAdmin',
			'username' => $username,
			'password' => $password,
			//'email' => $email
		];
		
		$response = get_web_page($url, $postData);
		$resArr = json_decode($response);
		if(!is_object($resArr)) {
			$r = ['api error'];
			echo json_encode($r);
			return;
		}
		if($resArr->result == 'success') {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'exists') {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		else {
			$r = ['failed'];
			echo json_encode($r);
			return;
		}
	}
	else if($site == 'management') {
		$url = 'http://autobutler.no/management/api/functions.php';
		$postData = [
			'method' => 'addAdmin',
			'username' => $username,
			'password' => $password,
			//'email' => $email
		];
		
		$response = get_web_page($url, $postData);
		$resArr = json_decode($response);
		if(!is_object($resArr)) {
			$r = ['api error'];
			echo json_encode($r);
			return;
		}
		if($resArr->result == 'success') {
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		else if($resArr->result == 'exists') {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		else {
			$r = ['failed'];
			echo json_encode($r);
			return;
		}
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function logoutAdmin() {
	session_destroy();
	echo 'success';
	return;
}

function deleteRow() {
	$con = dbCon();
	$table = p($_POST['table']);
	$id = (int)p($_POST['id']);
	
	if($table == 'tyres') {
		$q = mysqli_query($con, "DELETE FROM shop_stock WHERE tyreID=$id");
	}
	$q = mysqli_query($con, "DELETE FROM shop_".$table." WHERE id=$id");
	if($q) {
		if($table == 'tyres') {
			$q = mysqli_query($con, "DELETE FROM shop_stock WHERE tyreID=$id");
		}
		echo 'success';
		return;
	}
	
	echo 'failed';
	return;
}

function stockSearch() {
	$con = dbCon();
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	$sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "AND location = 'Moss Dekk AS'";
	
	if($type == '' || $value == '') { $r = ['empty']; echo json_encode($r); return; }
	if($type == 'brandModel') {
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE (brand LIKE '%$value%' OR model LIKE '%$value%') $sqlWhere ");
	}else {
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE size LIKE '%$value%' $sqlWhere");
	}
	
	$tr = '';
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $ft) {
		$tyreID = (int)$ft['id'];
		$brand = $ft['brand'];
		$model = $ft['model'];
		$tyreSize = $ft['size'];
		$productDesc = $brand.' '.$model.' - '.$tyreSize;
		
		$q = mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID");
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		$location = $f['location'];
		$purchasePrice = 0;
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['purchasePrice'])) {
				$purchasePrice = $misc['purchasePrice'];
			}
		}
		
		$button = '<button class="btn btn-sm btn-warning text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;		';
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td style="max-width:200px;">'.$location.'</td>';
		$tr .= '<td style="max-width:200px;">'.$productDesc.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
        $tr .= '<td><span class="txtField'.$f['id'].'">'.$f['delay'].'</span>
                    <input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['delay'].'" id="delay'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td>'.$button.'</td>';
		$tr .= '</tr>';
		$i++;
		
	}
	$r = ['success', $tr];
	echo json_encode($r);
	return;
	
}

function getLocation() {
    $con = dbCon();

    if(isset($_POST['type'])) {
        $type = p($_POST['type']);
        if(($type == 'dekk') || ($type == 'location')) {

            $workType = p($_POST['workType']);
            $locationID = p($_POST['locationID']);
            $resArr = array();

            //use api for location
            $url = 'http://autobutler.no/management/api/functions.php';
            $postData = ['method'=>'fetchServicesLocationAdminShop', 'workType'=>$workType, 'locationID'=>$locationID];

            $response = get_web_page($url, $postData);
            $resHtml = json_decode($response);
            if(!is_object($resHtml)) {
                $r = ['api error'];
                echo json_encode($r);
                return;
            }

            $r = ['success', $resHtml->html];
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
            // if 'Omlegg og avbalansering':
            /*if((int)$f['id'] === 4) {
                $html .= '<div class="serviceBar activeService service'.$f['id'].'" data-price="'.$price.'" style="" >
                        '.$f['attribute1'].'
                    <div style="display:inline-block; margin-left:10px; padding-left:10px; padding-right:10px; border-left:1px solid #ccc; border-right:1px solid #ccc;">
                        Kr '.$price.'
                    </div>
                    <select id="maxNum'.$f['id'].'" class="maxNum" data-id="'.$f['id'].'" data-price="'.$price.'" onchange="saveMaxNum('.$f['id'].','.$price.')" onclick="saveService(0,0)" style="color:#333; display:inline-block; margin-left:5px; border:none;">
                        '.$maxNumOptions.'
                    </select>

                </div>
                <script type="text/javascript">
                    saveService('.$f['id'].', '.$price.');
                </script>';
            } else {*/
            $html .= '<div class="serviceBar inactiveService service'.$f['id'].'" onclick="saveService('.$f['id'].', '.$price.')" data-price="'.$price.'" style="" >
    						'.$f['attribute1'].'
    					<div style="display:inline-block; margin-left:10px; padding-left:10px; padding-right:10px; border-left:1px solid #ccc; border-right:1px solid #ccc;">
    						Kr '.$price.'
    					</div>
    					<select id="maxNum'.$f['id'].'" class="maxNum" data-id="'.$f['id'].'" data-price="'.$price.'" onchange="saveMaxNum('.$f['id'].','.$price.')" onclick="saveService(0,0)" style="color:#333; display:inline-block; margin-left:5px; border:none;">
    						'.$maxNumOptions.'
    					</select>
    						
    				</div>';
            //}
        }

        $r = ['success', $html];
        echo json_encode($r);
        return;
    }

    //$r = ['failed'];
    //echo json_encode($r);
    //return;
}

function saveStockInfo() {
	$con = dbCon();
	$stock = (int)p($_POST['stock']);
	$stockID = (int)p($_POST['id']);
	$purchasePrice = p($_POST['purchasePrice']);
    $delay = (int)p($_POST['delay']);
	
	$q = mysqli_query($con, "SELECT * FROM shop_stock WHERE id=$stockID");
	if(!$q) { $r = ['no tyre']; echo json_encode($r); return; }
	
	$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
	if($f['misc'] != '') {
		$misc = json_decode($f['misc'], true);
		$misc['purchasePrice'] = $purchasePrice;
	}else {
		$misc = array();
		$misc['purchasePrice'] = $purchasePrice;
	}
	$misc = json_encode($misc);
	
	$q = mysqli_query($con, "UPDATE shop_stock SET stock=$stock, delay='$delay', misc='$misc' WHERE id=$stockID");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function saveCustomerInfo() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }

	$con = dbCon();
	$customerID = (int)p($_POST['id']);
	$fullName = p($_POST['fullName']);
	$username = p($_POST['username']);
	$password = md5(p($_POST['password']));
	$email = p($_POST['email']);
	$mobile = p($_POST['mobile']);
	$regNr = p($_POST['regNr']);
	$postCode = p($_POST['postCode']);
	$address = p($_POST['address']);
	$city = p($_POST['city']);
	$price = p($_POST['price']);
	$carType = p($_POST['carType']);
	
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE id=$customerID");
	if(!$q) { $r = ['no entries']; echo json_encode($r); return; }
	
	$q = mysqli_query($con, "UPDATE shop_customers SET `fullName`='$fullName',`username`='$username',`password`='$password',`email`='$email',`mobile`='$mobile',`regNr`='$regNr',`postCode`='$postCode',`address`='$address',`city`='$city',`price`='$price',`carType`='$carType' WHERE id=$customerID");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function massDelete() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }

	$con = dbCon();
	$idArray = json_decode($_POST['id'],true);
	$from = p($_POST['from']);

	for($i=0; $i<count($idArray);$i++){
		$id = $idArray[$i];
		$q = mysqli_query($con, "DELETE FROM `".$from."` WHERE `id`='".$id."';");
		if(!$q) {
			$r = ['failed'];
			echo json_encode($r);
			return;
		}
	}

	$r = ['success'];
	echo json_encode($r);
	return;
}

function showStock() {
	$con = dbCon();
	$type = p($_POST['type']);
	
	if($type == '' || ($type != 'in' && $type != 'out')) {
		$r = ['invalid type'];
		echo json_encode($r);
		return;
	}
	
	$stockQuery = "stock = 0";
	if($type == 'in') {
		$stockQuery = "stock > 0";
	}
	$sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "AND location = 'Moss Dekk AS'";
	$q = mysqli_query($con, "SELECT * FROM shop_stock WHERE ".$stockQuery." $sqlWhere");
	if(mysqli_num_rows($q) == 0) {
		$r = ['no entries'];
		echo json_encode($r);
		return;
	}
	
	$tr = '';
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		$tyreID = $f['tyreID'];
		
		$purchasePrice = 0;
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['purchasePrice'])) {
				$purchasePrice = $misc['purchasePrice'];
			}
		}
		
		$ft = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID"), MYSQLI_ASSOC);
		$brand = $ft['brand'];
		$model = $ft['model'];
		$tyreSize = $ft['size'];
		$productDesc = $brand.' '.$model.' - '.$tyreSize;
		
		$button = '<button class="btn btn-sm btn-warning text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;		';
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['location'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$productDesc.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span>
					<input type="number" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
        $tr .= '<td><span class="txtField'.$f['id'].'">'.$f['delay'].'</span>
                    <input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['delay'].'" id="delay'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td>'.$button.'</td>';
		$tr .= '</tr>';
		$i++;
		
	}
	$r = ['success', $tr];
	echo json_encode($r);
	return;
	
}

function showAllOrder() {
	$con = dbCon();
	
	$tr = '';
	$i = 1;
	$sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "Where location = 'Moss Dekk AS'";
	$q = mysqli_query($con, "SELECT * FROM shop_orders $sqlWhere");
	if(mysqli_num_rows($q) > 0) {
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
			$status = '';
			$paymentMode = '';

			//fetch status from management site
			if($f['misc'] != '') {
				$misc = json_decode($f['misc'], true);
				if(isset($misc['managementWorkOrderID'])) {
					$workOrderID = (int)$misc['managementWorkOrderID'];
					
					$url = 'http://autobutler.no/management/api/functions.php';
					$postData = [ 'method'=>'fetchTyreChangeStatusOfTyreShop', 'regNr'=>$f['regNr'], 'workOrderID'=>$workOrderID];
					
					$response = get_web_page($url, $postData);
					$resArr = array();
					$resArr = json_decode($response);
			
					if(is_object($resArr)) {
						$status = $resArr->status;
					}
				}
				if(isset($misc['paymentMode'])) {
					$paymentMode = $misc['paymentMode'];
				}
			}
			
			$tr .= '<tr>
					<td>'.$i.'</td>
					<td>'.$f['location'].'</td>
					<td>'.$f['name'].'</td>
					<td>'.$f['regNr'].'</td>
					<td>'.$f['mobile'].'</td>
					<td>'.$f['brand'].'</td>
					<td>'.$f['size'].'</td>
					<td>'.$f['tyres'].'</td>
					<td>'.$f['price'].'</td>
					<td>'.$paymentMode.'</td>
					<td>'.$f['changeDate'].'</td>
					<td>'.$status.'</td>
					<td></td>
					</tr>';
			$i++;
		}
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	$r = ['no entries'];
	json_encode($r);
	return;
}

function showQueries() {
	$con = dbCon();
	$type = p($_POST['type']);
	
	if($type != 'new' && $type != 'old') { 
		$r = ['invalid type'];
		echo json_encode($r);
		return;
	}
	
	if($type == 'new') {
		$miscQuery = "misc = ''";
	}else { 
		$miscQuery = "misc != ''";
	}
    $sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "AND location = 'Moss Dekk AS'";	
	$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE ".$miscQuery." $sqlWhere");
	if(mysqli_num_rows($q) == 0) {
		$r = ['no entries'];
		echo json_encode($r);
		return;
	}
	
	$tr = '';
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		$seenButton = '';
		$seen = 0;
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['seen'])) {
				$seen = $misc['seen'];
			}
		}
		$replyButton = '<button class="btn btn-sm btn-outline-danger py-0 m-0 p-10 replyButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" data-toggle="modal" data-target="#sendMessage" onclick="reply('.$f['id'].')" >Reply</button>';
		if($seen == 0)
		$seenButton = '<button class="btn btn-sm btn-outline-success py-0 p-10 m-0 seenButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="seen('.$f['id'].')" >Seen</button>';
		
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>'.$f['date'].'</td>';
		$tr .= '<td>'.$f['location'].'</td>';
		$tr .= '<td class="username">'.$f['name'].'</td>';
		$tr .= '<td class="email">'.$f['email'].'</td>';
		$tr .= '<td>'.$f['reg_nr'].'</td>';
		$tr .= '<td>'.$f['phone'].'</td>';
		$tr .= '<td style="max-width:100px;">'.$f['subject'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['message'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['replyDate'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['replyBy'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['replyMessage'].'</td>';
		$tr .= '<td>'.$replyButton.$seenButton.'</td>';
		$tr .= '</tr>';
		$i++;
	}
	$r = ['success', $tr];
	echo json_encode($r);
	return;
	
}

function querySearch() {
	$con = dbCon();
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	
	if($type == '' || $value == '') {
		$r = ['empty field'];
		echo json_encode($r);
		return;
	}
    $sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "AND location = 'Moss Dekk AS'";		
	$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE ".$type." LIKE '%".$value."%' $sqlWhere");
	if(mysqli_num_rows($q) == 0) {
		$r = ['no entries'];
		echo json_encode($r);
		return;
	}
	
	$tr = '';
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		$seenButton = '';
		$seen = 0;
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['seen'])) {
				$seen = $misc['seen'];
			}
		}
		if($seen == 0) {
			$seenButton = '<button class="btn btn-sm btn-outline-success py-0 m-0 seenButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="seen('.$f['id'].')" >Seen</button>';
		}
		
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>'.$f['date'].'</td>';
		$tr .= '<td>'.$f['location'].'</td>';
		$tr .= '<td>'.$f['name'].'</td>';
		$tr .= '<td>'.$f['email'].'</td>';
		$tr .= '<td>'.$f['phone'].'</td>';
		$tr .= '<td style="max-width:100px;">'.$f['subject'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['message'].'</td>';
		$tr .= '<td>'.$seenButton.'</td>';
		$tr .= '</tr>';
		$i++;
	}
	$r = ['success', $tr];
	echo json_encode($r);
	return;
	
}

function querySeen() {
	$con = dbCon();
	$id = (int)p($_POST['id']);
	
	$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE id=$id");
	if(mysqli_num_rows($q) == 0) { echo 'not found'; return; }
	
	$f = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_contact WHERE id=$id"), MYSQLI_ASSOC);
	if($f['misc'] != '') {
		$misc = json_decode($f['misc'], true);
		$misc['seen'] = 1;
	}else {
		$misc = array();
		$misc['seen'] = 1;
	}
	$misc = json_encode($misc);
	$q = mysqli_query($con, "UPDATE shop_contact SET misc = '$misc' WHERE id=$id");
	if($q) { echo 'success'; return; }
	
	echo 'failed';
	return;
	
}

function saveTyreInfo() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	$con = dbCon();
	
	$category = p($_POST['category']);
	$location= p($_POST['location']);
	$brand = p($_POST['brand']);
	$model = p($_POST['model']);
	$speed = p($_POST['speed']);
	$load = p($_POST['load']);
	$size = p($_POST['size']);
	$price = p($_POST['price']);
	$fuel = p($_POST['fuel']);
	$grip = p($_POST['grip']);
	$noise = p($_POST['noise']);
	$tyreID = (int)p($_POST['id']);
	$tyreInfo = p($_POST['tyreInfo']);
	$season = p($_POST['season']);
	$runFlat = p($_POST['runFlat']);
	
	
	$recommended = p($_POST['recommended']);
	$uploadImage = 1;
	if(!empty($_FILES)) {	
		if($_FILES['image']['error'] != 0 || $_FILES['image']['size'] == 0) {
			$uploadImage = 0;
		}else {
			$type = explode('/', $_FILES['image']['type']);
			if($type[0] != 'image') { 
				$r = ['image required'];
				echo json_encode($r);
				return; 
			}
		}
	}

	$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID");
	if(mysqli_num_rows($q) > 0) {
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			$misc['tyreInfo'] = $tyreInfo;
			$misc['runFlat'] = $runFlat;
			$misc = json_encode($misc, JSON_UNESCAPED_UNICODE);
		}else {
			$misc = array();
			$misc['tyreInfo'] = $tyreInfo;
			$misc['runFlat'] = $runFlat;
			$misc = json_encode($misc);
		}

		$q = mysqli_query($con, "UPDATE shop_tyres SET `category`='$category',`location`='$location', `brand`='$brand', `model`='$model', `speed`='$speed', `load`='$load', `size`='$size', `price`='$price', `fuel`='$fuel', `grip`='$grip',`recommended`='$recommended', `noise`='$noise', `misc`='$misc', `season`='$season' WHERE id=$tyreID");
		if($q) {
			if(!empty($_FILES)) {	
				if($uploadImage == 1) {
					$today = date('Y-m-d-Hi');
					if($type[1] == 'png') { $ext = 'png'; } else { $ext = 'jpg'; }
					$picName = $tyreID.'@'.$today.'.'.$ext.'';
					move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/tyreImg/'.$picName);
					
					$q = mysqli_query($con, "UPDATE shop_tyres SET `image`='$picName' WHERE id=$tyreID");
				}
			}
			
			$r = ['success'];
			echo json_encode($r);
			return;
		}
		$r = ['failed'];
		echo json_encode($r);
		return;
	}
	$r = ['no tyre'];
	echo json_encode($r);
	return;
}

function orderSearch() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	$con = dbCon();
	
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	
	$sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "AND location = 'Moss Dekk AS'";
	$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE $type LIKE '%$value%' $sqlWhere");
	if(mysqli_num_rows($q) > 0) {
		
		$tr = '';
		$i = 1;
		
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
			
			$status = '';
			$paymentMode = '';
			//fetch status from management site
			if($f['misc'] != '') {
				$misc = json_decode($f['misc'], true);
				if(isset($misc['managementWorkOrderID'])) {
					$workOrderID = (int)$misc['managementWorkOrderID'];
					
					$url = 'http://autobutler.no/management/api/functions.php';
					$postData = [ 'method'=>'fetchTyreChangeStatusOfTyreShop', 'regNr'=>$f['regNr'], 'workOrderID'=>$workOrderID];
					
					$response = get_web_page($url, $postData);
					$resArr = array();
					$resArr = json_decode($response);
			
					if(is_object($resArr)) {
						$status = $resArr->status;
					}
				}
				if(isset($misc['paymentMode'])) {
					$paymentMode = $misc['paymentMode'];
				}
			}
			
			$tr .= '<tr>
					<td>'.$i.'</td>
					<td>'.$f['location'].'</td>	
					<td>'.$f['name'].'</td>
					<td>'.$f['regNr'].'</td>
					<td>'.$f['mobile'].'</td>
					<td>'.$f['brand'].'</td>
					<td>'.$f['size'].'</td>
					<td>'.$f['tyres'].'</td>
					<td>'.$f['price'].'</td>
					<td>'.$paymentMode.'</td>
					<td>'.$f['changeDate'].'</td>
					<td>'.$status.'</td>
					<td></td>
					</tr>';
			$i++;
		}
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	$r = ['no entry'];
	echo json_encode($r);
	return;
}

function invoiceSearch() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	$con = dbCon();
	
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	$column = p($_POST['column']);
	$sort_order = p($_POST['sort_order']);
	
    if($seen == 0) {
        $paidButton = '<button class="btn btn-sm btn-outline-success py-0 m-0" >Paid</button>';
    }
    $nopaidButton = '<button class="btn btn-sm btn-outline-danger py-0 m-0" >No Paid</button>';
	
	$q = mysqli_query($con, "SELECT * FROM shop_invoice WHERE locationID=21 and $type LIKE '%$value%' ORDER BY " .  $column . " " . $sort_order);
	if(mysqli_num_rows($q) > 0) {
		
		$tr = '';
		$i = 1;
		$price = 0;

		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);

		// for summary
		$trSummary = '';
		$tyrechangeAntall = $tyrechangeAmount  = 0;
		$tyrechangeDekkAntall = $tyrechangeDekkAmount  = 0;
		$newtyreAntall = $newtyreAmount  = 0;
		$tyrerepairAntall = $tyrerepairAmount  = 0;
		$tyrebalancingAntall = $tyrebalancingAmount  = 0;

		foreach($fetch as $f) {

            $imageLink = '<i class="fa fa-file-pdf-o"></i>';
            $paymentMode = $f['paymentMode'];
			$workType = $f['workType'];

			if($workType == 'Tyre Change Dekkhotell'){
				$tyrechangeDekkAntall += 1;
				$tyrechangeDekkAmount += $f['price'];
			} elseif($workType == 'Tyre Change'){
				$tyrechangeAntall += 1;
				$tyrechangeAmount += $f['price'];
			} else if($workType == 'New Tyre'){
				$newtyreAntall += 1;
				$newtyreAmount += $f['price'];
			} else if($workType == 'Tyre Balancing'){
				$tyrebalancingAntall += 1;
				$tyrebalancingAmount += $f['price'];
			} else if($workType == 'Tyre Repair'){
				$tyrerepairAntall += 1;
				$tyrerepairAmount += $f['price'];
			}

            $tr .= '<tr>				
				<td style="vertical-align: middle;">
					<span id="pdfMaker' . $f['id'] . '" onclick="pdfdownload('.$f['id'].')" style="cursor:pointer;" class="txtField' . $f['id'] . '">' . $imageLink . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $i . '</span>					
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . ((int)$f['id'] + 10000) . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $f['orderedOn'] . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '" style="background-color:#f6c3c5; border-radius: 4px; padding:2px; margin:0px 1px;">' . $f['changeDate'] . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $f['regNr'] . '</span>
					<input type="text" class="form-control form-control-sm txtInput editField' . $f['id'] . '" value="' . $f['load'] . '" id="load' . $f['id'] . '" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $f['workType'] . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $f['name'] . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $paymentMode . '</span>
				</td>
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '">' . $f['price'] . '</span>
				</td>			
			    <td style="vertical-align: middle;">
			        <span class="txtField' . $f['id'] . '">' . ((int)($f['price'])*1/5) . '</span>	
				</td>	
				<td style="vertical-align: middle;">
					<span class="txtField' . $f['id'] . '" onclick="doPay('.$f['id'].')">' . ((int)($f['isPay']) > 0 ? $paidButton:$nopaidButton) . '</span>					
				</td>				
				</tr>';
			$i++;
			$price +=$f['price'];
		}
		$tr .= '<tr><td colspan="8"></td>';
        $tr .= '<td colspan="1">Total amount</td>';
        $tr .= '<td colspan="1">'.$price.'</td>';
        $tr .= '<td colspan="1">'.((int)$price*1/5).'</td>';
        $tr .= '</tr>';

		if($tyrechangeDekkAntall>0) $trSummary .= '<tr><td style="vertical-align: middle;">
													<span class="txtField">Tyre Change Dekkhotell</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">' . $tyrechangeDekkAntall . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . $tyrechangeDekkAmount . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . (int)$tyrechangeDekkAmount*1/5 . '</span>
												</td></tr>';
		if($tyrechangeAntall>0) $trSummary .= '<tr><td style="vertical-align: middle;">
												<span class="txtField">Tyre Change</span>
											</td>
											<td style="vertical-align: middle;">
												<span class="txtField">' . $tyrechangeAntall . '</span>
											</td>
											<td style="vertical-align: middle;">
												<span class="txtField">Kr. ' . $tyrechangeAmount . '</span>
											</td>
											<td style="vertical-align: middle;">
												<span class="txtField">Kr. ' . (int)$tyrechangeAmount*1/5 . '</span>
											</td></tr>';
		if($newtyreAntall>0) $trSummary .= '<tr><td style="vertical-align: middle;">
													<span class="txtField">New Tyre</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">' . $newtyreAntall . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . $newtyreAmount . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . (int)$newtyreAmount*1/5 . '</span>
												</td></tr>';
		if($tyrebalancingAntall>0) $trSummary .= '<tr><td style="vertical-align: middle;">
													<span class="txtField">Tyre Balancing</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">' . $tyrebalancingAntall . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . $tyrebalancingAmount . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . (int)$tyrebalancingAmount*1/5 . '</span>
												</td></tr>';
		if($tyrerepairAntall>0) $trSummary .= '<tr><td style="vertical-align: middle;">
													<span class="txtField">Tyre Repair</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">' . $tyrerepairAntall . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . $tyrerepairAmount . '</span>
												</td>
												<td style="vertical-align: middle;">
													<span class="txtField">Kr. ' . (int)$tyrerepairAmount*1/5 . '</span>
												</td></tr>';
		$trSummary .= '<tr style="border-top: 2px solid #000;"><td style="vertical-align: middle;">
							<span class="txtField">Total: </span>
						</td>
						<td style="vertical-align: middle;">
							<span class="txtField">' . ($tyrechangeDekkAntall+ $tyrechangeAntall+ $newtyreAntall+ $tyrebalancingAntall+ $tyrerepairAntall). '</span>
						</td>
						<td style="vertical-align: middle;">
							<span class="txtField">Kr. ' . ($tyrechangeDekkAmount+ $tyrechangeAmount+ $newtyreAmount+ $tyrebalancingAmount+ $tyrerepairAmount) . '</span>
						</td>
						<td style="vertical-align: middle;">
							<span class="txtField">Kr. ' . (int)($tyrechangeDekkAmount+ $tyrechangeAmount+ $newtyreAmount+ $tyrebalancingAmount+ $tyrerepairAmount)*1/5 . '</span>
						</td></tr>';

		$r = ['success', $tr, $trSummary];
		echo json_encode($r);
		return;
	}
	$r = ['no entry'];
	echo json_encode($r);
	return;
}

function doPay(){
    $con = dbCon();
    $id = p($_POST['id']);

    $qs = mysqli_query($con, "SELECT * FROM shop_invoice WHERE id=$id");
    if(mysqli_num_rows($qs) > 0) {
        $fs = mysqli_fetch_array_n($qs, MYSQLI_ASSOC);
        $mode = $fs['paymentMode'];
        if($mode == 'Firmakunde'){
            $q = mysqli_query($con, "UPDATE shop_invoice SET isPay='1' WHERE id=$id");
            if($q) {
                $r = ['success'];
                echo json_encode($r);
                return;
            }
        }
        else{
            $r = ['other'];
            echo json_encode($r);
            return;
        }
    }
    $r = ['failed'];
    echo json_encode($r);
    return;
}

// private function -- used in pdfDownloadSingle and pdfDownloadMultiple
function pdfGenerate($id){
    $con = dbCon();

    $qo = mysqli_query($con, "SELECT * FROM shop_invoice WHERE id=$id");
    if(mysqli_num_rows($qo) > 0) {
        $fo = mysqli_fetch_array_n($qo, MYSQLI_ASSOC);
        $nr = 10000 + (int)$fo['id'];//invoice nr
        $name = $fo['name'];//customer name
        $regNr = $fo['regNr'];// customer nr
        $price = $fo['price'];
        $invoice_date = $fo['orderedOn'];
        $workType = $fo['workType'];
        $antal = $fo['tyres'];
        $customerID = $fo['customerID'];
        $locationID = $fo['locationID'];
        $serviceIDs = $fo['serviceIDs'];
        $serviceCounts = $fo['serviceCounts'];
        $reference = $fo['reference'];
        $locationName = '';
        $size = $fo['brand'].' '.$fo['model'].' '.$fo['size'];
        $model = $fo['model'];
        $count = $fo['count'];
        $offerPrice = $fo['offerPrice'];
        $qc = mysqli_query($con, "SELECT * FROM shop_customers WHERE id='$customerID' LIMIT 1");
        if(mysqli_num_rows($qc) > 0) {
            $fc = mysqli_fetch_array_n($qc, MYSQLI_ASSOC);
            $address = $fc['address'];
            $postCode = $fc['postCode'];
            $city = $fc['city'];
        }
        $qb = mysqli_query($con, "SELECT * FROM shop_bank LIMIT 1");
        if(mysqli_num_rows($qb) > 0) {
            $fb = mysqli_fetch_array_n($qb, MYSQLI_ASSOC);
            $due_period = $fb['due_period'];
            $account_in = $fb['account_in'];
        }
    }
    //get location name
    $url = 'http://autobutler.no/management/api/functions.php';
    $postData = [
        'method' => 'fetchLocation',
        'locationID' => $locationID
    ];
    $response = get_web_page($url, $postData);
    $resArr = json_decode($response);
    if(!is_object($resArr)) {
        $r = ['api error'];
        return json_encode($r);
    }
    if($resArr->result == 'success') {
        $locationName = $resArr->locationName;
    }

    //get service name
    $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
    $postData = [
        'method' => 'fetchService',
        'serviceIDs' => $serviceIDs
    ];
    $response = get_web_page($url, $postData);
    $resDekArr = json_decode($response);
    if(!is_object($resDekArr)) {
        $r = ['dekk api error'];
        return json_encode($r);
    }
    if($resDekArr->result == 'success') {
        $serviceNames = $resDekArr->serviceNames;
        $servicePrices = $resDekArr->servicePrices;
    }
    else{
        $serviceNames = "";
        $servicePrices = "";
    }

    //make array from serviceIDs and serviceCounts
    $idArray = explode(',', $serviceIDs);
    $idArray = array_filter($idArray);
    $countArray = explode(',', $serviceCounts);
    $countArray = array_filter($countArray);
    $countArrayResult = [];
    foreach ($countArray as $value) {
        if (strlen($value)) {
            $countArrayResult[] = $value;
        }
    }
    $serviceNameArray = explode(',', $serviceNames);
    $serviceNameArray = array_filter($serviceNameArray);
    $servicePriceArray = explode(',', $servicePrices);
    $servicePriceArray = array_filter($servicePriceArray);

    $pdfcontent = "<style type='text/css'>";
    $pdfcontent .= ".bb td, .bb th {";
    $pdfcontent .= "border-bottom: 1px solid black !important;";
    $pdfcontent .= "}";
    $pdfcontent .= "</style>";

    $pdfcontent .= '<table border="0" style="width: 100%;">';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '<td style="width: 80%;text-align: right;">';
    $pdfcontent .= '<h2>MOSS DEKK AS</h2>';
    $pdfcontent .= '<hr>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td style="width: 10%"></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;">';
    $pdfcontent .= 'Skredderveien 5, 1537 Moss, Norge';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;">';
    $pdfcontent .= 'Telefon:45022450';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;">';
    $pdfcontent .= 'E-postadresse: post@mossdekk.no';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;">';
    $pdfcontent .= 'Foretaksregisteret: NO 921836686 MVA';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;">';
    $pdfcontent .= 'www.mosdekk.no';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '</table>';
    $pdfcontent .= '<br>';
    $pdfcontent .= '<br>';
    $pdfcontent .= '<br>';
    $pdfcontent .= '<table border="0" style="width: 100%;">';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '<td style="width: 20%;text-align: left;">';
    if(isset($name))
        $pdfcontent .= $name.'<br>';
    if(isset($address))
        $pdfcontent .= $address.'<br>';
    if(isset($postCode)&&isset($city))
        $pdfcontent .= $postCode.' '.$city.'<br>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td style="width: 20%;text-align: right;"></td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;"></td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;"></td>';
    $pdfcontent .= '<td style="width: 20%;">Faktura</td>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: left;vertical-align: bottom;">';
    $pdfcontent .= 'Refrence: '.$reference;
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= 'faktura: '.$nr.'<br>';
    $pdfcontent .= 'Fakturadato: '.$invoice_date.'<br>';
    $pdfcontent .= 'Kundenr: '.$regNr;
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: left;">';
    $pdfcontent .= 'Deres kontakt:<br>';
    $pdfcontent .= 'Leveransedato:<br>';
    $pdfcontent .= 'Leveransested:<br>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= 'Adrian Kanwar<br>';
    $pdfcontent .= $invoice_date.'<br>';
    $pdfcontent .= $locationName;
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td></td>';
    //$pdfcontent .= '<td>Due date:<br>';
    //$pdfcontent .= $due_period.' days'.'</td>';
    $pdfcontent .= '<td>BETALT<br>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '</table>';
    $pdfcontent .= '<br>';

    $pdfcontent .= '<table border="0" style="width: 100%;">';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= '<table border="0" style="width: 100%;">';
    $pdfcontent .= '<tr class="bb">';
    $pdfcontent .= '<td style="width: 30%;text-align: left;">Beskrivelse</td>';
    $pdfcontent .= '<td style="width: 20%;">Size</td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;">Antal</td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;">Enh.pris</td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;">Belop</td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;">Mva(25%)</td>';
    $pdfcontent .= '<td style="width: 10%;text-align: right;">Belop</td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr class="bb">';
    $pdfcontent .= '<td style="text-align: left;">'.$workType.'</td>';
    if($workType == 'New Tyre'){
        $pdfcontent .= '<td>'.$size.'</td>';
    }
    else{
        $antal = 1;
        $pdfcontent .= '<td></td>';
    }
    //recalculate price
    $length = count($idArray);
    $priceCalcSum = 0;
    for ($i = 0; $i < $length; $i++) {
        $priceCalcSum = $priceCalcSum + (int)$countArrayResult[$i] * $servicePriceArray[$i];
    }
    $price = (int)$price - $priceCalcSum;
    $price = (int)($price - $offerPrice);
    $pdfcontent .= '<td style="text-align: right;">'.$antal.'</td>';
    $pdfcontent .= '<td style="text-align: right;">'.$price.'</td>';
    $pdfcontent .= '<td style="text-align: right;">'.(int)($price*4/5).'</td>';
    $pdfcontent .= '<td style="text-align: right;">'.(int)($price*20/100).'</td>';
    $pdfcontent .= '<td style="text-align: right;">'.$price.' </td>';
    $pdfcontent .= '</tr>';
    //add New Tyres in dekkhotell
    if($workType == 'Tyre Change Dekkhotell') {
        $pdfcontent .= '<tr class="bb">';
        $pdfcontent .= '<td style="text-align: left;">' . 'New Tyres' . '</td>';
        $pdfcontent .= '<td>' . $size . '</td>';
        //recalculate price
        $length = count($idArray);
        $priceCalcSum = 0;
        for ($i = 0; $i < $length; $i++) {
            $priceCalcSum = $priceCalcSum + (int)$countArrayResult[$i] * $servicePriceArray[$i];
        }
        $pdfcontent .= '<td style="text-align: right;">' . $count . '</td>';
        $pdfcontent .= '<td style="text-align: right;">' . $offerPrice . '</td>';
        $pdfcontent .= '<td style="text-align: right;">' . (int)($offerPrice * 4 / 5) . '</td>';
        $pdfcontent .= '<td style="text-align: right;">' . (int)($offerPrice * 20 / 100) . '</td>';
        $pdfcontent .= '<td style="text-align: right;">' . $offerPrice . ' </td>';
        $pdfcontent .= '</tr>';
    }
    //add services
    $length = count($idArray);
    $priceSum1 = 0;
    $priceSum2 = 0;
    $priceSum3 = 0;
    for ($i = 0; $i < $length; $i++) {
        $priceCalc = (int)$countArrayResult[$i]*$servicePriceArray[$i];
        $priceSum1 = $priceSum1 + (int)($priceCalc*4/5);
        $priceSum2 = $priceSum2 + (int)($priceCalc*20/100);
        $priceSum3 = $priceSum3 + (int)$priceCalc;
        $pdfcontent .= '<tr class="bb">';
        $pdfcontent .= '<td style="text-align: left;">'.$serviceNameArray[$i].'</td>';
        $pdfcontent .= '<td></td>';
        $pdfcontent .= '<td style="text-align: right;">'.$countArrayResult[$i].'</td>';
        $pdfcontent .= '<td style="text-align: right;">'.$priceCalc.'</td>';
        $pdfcontent .= '<td style="text-align: right;">'.(int)($priceCalc*4/5).'</td>';
        $pdfcontent .= '<td style="text-align: right;">'.(int)($priceCalc*20/100).'</td>';
        $pdfcontent .= '<td style="text-align: right;">'.$priceCalc.' </td>';
        $pdfcontent .= '</tr>';
    }
    $pdfcontent .= '<tr class="bb">';
    $pdfcontent .= '<td style="text-align: left;">Sum</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;">'.($priceSum1 + (int)(($price+$offerPrice)*4/5)).'</td>';
    $pdfcontent .= '<td style="text-align: right;">'.($priceSum2 + (int)(($price+$offerPrice)*20/100)).'</td>';
    $pdfcontent .= '<td style="text-align: right;">'.($price + $offerPrice + $priceSum3).'</td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr class="bb">';
    $pdfcontent .= '<td style="text-align: left;">Betaling:</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td style="text-align: right;"></td>';
    $pdfcontent .= '<td style="text-align: right;">NOK</td>';
    $pdfcontent .= '<td style="text-align: right;">'.($price + $offerPrice + $priceCalcSum).'</td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '</table>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '<tr>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= '<br>';
    $pdfcontent .= 'Bank Account nr:';
    $pdfcontent .= $account_in.'</td>';
    $pdfcontent .= '<td style="width: 10%;"></td>';
    $pdfcontent .= '</tr>';
    $pdfcontent .= '</table>';
    
    return $pdfcontent;
}

function pdfDownloadSummary(){
	$month = p($_POST['month']);
	$table = p($_POST['table']);

	$table = str_replace("\r\n","",$table);
	$table = html_entity_decode($table);


	 // Include mpdf library file
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    
	// generate table content
    $pdfcontent = "<style type='text/css'>";
    $pdfcontent .= '.table {
		width: 100%;
		margin-bottom: 1rem;
		background-color: transparent;
	  }
	  
	  .table th,
	  .table td {
		padding: 0.75rem;
		vertical-align: top;
		border-top: 1px solid #dee2e6;
		text-align: center;
	  }
	  
	  .table thead th {
		vertical-align: bottom;
		
	  }
	  
	  .table tbody + tbody {
		border-top: 2px solid #dee2e6;
	  }
	  
	  .table .table {
		background-color: #fff;
	  }
	  .borter-t-2{
		border-top: 2px solid #000;
	}';
    $pdfcontent .= "</style>";
    
	$pdfcontent .= '<span>Customer: MOSS DEKK AS</span><br/><br/>';
	$pdfcontent .= '<span>'.$month.'</span><br/>';
	$pdfcontent .= '<table class="table table-hover table-sm" style="margin-top:30px; font-size:13px;" >
						<thead class="thead" >
							<tr>
								<th scope="col">Produkt</th>
								<th scope="col">Antall</th>
								<th scope="col">Total amount</th>
								<th scope="col">Total Mva</th>
							</tr>
						</thead>
						<tbody>'.
	  					$table.
					'</tbody></table>';



    $mpdf->WriteHTML($pdfcontent);

    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;

//output in browser
    $mpdf->Output('../uploads/pdf/invoice.pdf');
    $r = ['success'];
    echo json_encode($r);
    return;
}

function pdfDownloadSingle(){
    $id = p($_POST['id']);
    
    // Include mpdf library file
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
    
    $pdfOutput = pdfGenerate($id);
    
    $mpdf->WriteHTML($pdfOutput);

    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;

//output in browser
    $mpdf->Output('../uploads/pdf/invoice.pdf');
    $r = ['success'];
    echo json_encode($r);
    return;
}

function pdfDownloadMultiple(){
    $ids = p($_POST['ids']);
    $ids = explode(',', $ids);
    
    // Include mpdf library file
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();

    foreach($ids as $id){
        $pdfOutput = pdfGenerate($id);
        $mpdf->WriteHTML($pdfOutput);
        $mpdf->AddPage();
    }

    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;

    //output in browser
    $mpdf->Output('../uploads/pdf/invoice.pdf');
    $r = ['success'];
    echo json_encode($r);
    return;
}

function tyreSearch() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	$con = dbCon();

	$type = p($_POST['type']);
	$value = p($_POST['value']);
    $sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "AND location = 'Moss Dekk AS'";
	$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE $type LIKE '%$value%' $sqlWhere");
	if(mysqli_num_rows($q) > 0) {

		$tr = '';
		$i = 1;

		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {

			$bSelect = $mSelect = $pSelect = '';
			if($f['category'] == 'budget') { $bSelect = 'selected'; }
			else if($f['category'] == 'mellom') { $mSelect = 'selected'; }
			else if($f['premium'] == 'premium') { $pSelect = 'selected'; }

			$noiseSelect = '<select id="noise'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">';
			for($j=50; $j <=100; $j++) {
				$noiseSelect .= '<option value="'.$j.'">'.$j.'</option>';
			}
			$noiseSelect .= '</select>';

			$selectOptions = '<option value="a">A</option>';
			$selectOptions .= '<option value="b">B</option>';
			$selectOptions .= '<option value="c">C</option>';
			$selectOptions .= '<option value="d">D</option>';
			$selectOptions .= '<option value="e">E</option>';
			$selectOptions .= '<option value="f">F</option>';
			$selectOptions .= '<option value="g">G</option>';
			$selectOptions .= '<option value="h">H</option>';

			$imageLink = '';
			if($f['image'] != '') {
				$imageLink = '<a href="../uploads/tyreImg/'.$f['image'].'" target="_blank">View</a>';
			}

			$tyreInfo = $readInfoLink = $editInfoLink = $runFlatSelect = $runFlatNoSelect = $runFlatYesSelect = $runFlat = '';
			if($f['misc'] != '') {
				$misc = json_decode($f['misc'], true);
				if(isset($misc['tyreInfo'])) {
					$tyreInfo = $misc['tyreInfo'];
					$readInfoLink = '<button href="#" class="btn btn-sm py-0 m-0 btn-outline-primary editButton'.$f['id'].' button'.$f['id'].'" data-toggle="modal" data-target="#tyreInfoModal" data-tyreid="'.$f['id'].'" data-mode="readonly">View</button>';
					$editInfoLink = '<button href="#" class="btn btn-sm py-0 m-0 btn-outline-warning saveButton'.$f['id'].' button'.$f['id'].'" data-toggle="modal" data-target="#tyreInfoModal" data-tyreid="'.$f['id'].'" data-mode="edit" style="display:none;">Change</button>';
				}

				if(isset($misc['runFlat'])) {
					$runFlat = $misc['runFlat'];
					if($runFlat == 'yes') {
						$runFlatYesSelect = 'selected';
					}else {
						$runFlatNoSelect = 'selected';
					}
					$runFlatSelect = '<select id="runFlat'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
									<option value="yes" '.$runFlatYesSelect.'>Yes</option>
									<option value="no" '.$runFlatNoSelect.'>No</option>
								</select>';
				}

			}

			$select = '<select id="category'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
							<option value="budget" '.$bSelect.'>Budget</option>
							<option value="mellom" '.$mSelect.'>Mellom</option>
							<option value="premium" '.$pSelect.'>Premium</option>
						</select>';

			$seasonS = $seasonW = $seasonWS = '';
			if($f['season'] == 'summer') { $seasonS = 'selected'; }
			else if($f['season'] == 'winter') { $seasonW = 'selected'; }
			else { $seasonWS = 'selected'; }
			$seasonSelect = '<select id="season'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
							<option value="summer" '.$seasonS.'>Sommerdekk</option>
							<option value="winter" '.$seasonW.'>Vinterdekk - piggfrie</option>
							<option value="winterStudded" '.$seasonWS.'>Vinterdekk - piggdekk</option>
						</select>';

			$season = '';
			if($f['season'] == 'winter') {	$season = 'Vinterdekk - piggfrie'; }
			else if($f['season'] == 'summer') { $season = 'Sommerdekk'; }
			else if($f['season'] == 'winterStudded') { $season = 'Vinterdekk - piggdekk'; }

			$tr .= '<tr>
					<textarea id="tyreInfo'.$f['id'].'" hidden="hidden">'.$tyreInfo.'</textarea>
					<td>'.$i.'</td>
    				<td>
    					<span class="txtField'.$f['id'].'">'.$f['location'].'</span>
    					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['location'].'" id="location'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">	
    				</td>	
					<td>
						<span class="txtField'.$f['id'].'">'.$f['category'].'</span>
						'.$select.'
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['brand'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['brand'].'" id="brand'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['model'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['model'].'" id="model'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['speed'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['speed'].'" id="speed'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['load'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['load'].'" id="load'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['size'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['size'].'" id="size'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['price'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['price'].'" id="price'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['fuel'].'</span>
						<select class="form-control form-control-sm editField'.$f['id'].'" id="fuel'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">
							'.$selectOptions.'
						</select>
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['grip'].'</span>
						<select class="form-control form-control-sm editField'.$f['id'].'" id="grip'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">
							'.$selectOptions.'
						</select>
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['noise'].'</span>
						'.$noiseSelect.'
					</td>
						<td>
						<span class="txtField'.$f['id'].'">'.$runFlat.'</span>
						'.$runFlatSelect.'
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$season.'</span>
						'.$seasonSelect.'
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$imageLink.'</span>
						<input type="file" class="form-control-file editField'.$f['id'].'" id="image'.$f['id'].'" style="display:none;">
						<div id="imageUploadBar'.$f['id'].'" style="display:none;"><img src="../images/Rolling.gif" id="uploadLoading'.$f['id'].'" style="width:20px; margin-right:10px;" /> <span id="uploadPerc'.$f['id'].'" style=""></span>%</div>
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$readInfoLink.'</span>
						'.$editInfoLink.'
					</td>
					<td>
						<button class="btn btn-sm btn-outline-warning py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="edit('.$f['id'].')">Edit</button>
						<button class="btn btn-sm btn-outline-success py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="saveNew('.$f['id'].')" style="display:none;">Save</button>
						<button class="btn btn-sm btn-outline-danger py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="deleteRow(\'tyres\', '.$f['id'].', 1)">Delete</button>
					</td>
					<td>
						<input type="checkbox" class="form-control-sm massdel" id="'.$f['id'].'">
					</td>
					</tr>';
			$i++;
		}
		$r = ['success', $tr];
		echo json_encode($r);
		return;
	}
	$r = ['no entry'];
	echo json_encode($r);
	return;
}
//brand part
function brandService(){
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	
	foreach($_POST as $post) {
		if(p($post) == '' || p($post) == 'undefined') { $r = ['empty']; echo json_encode($r); exit; }
	}


	if($_FILES['image']['error'] != 0 || $_FILES['image']['size'] == 0) {
		$r = ['no image']; echo json_encode($r); exit; 
	}
	$type = explode('/', $_FILES['image']['type']);
	if($type[0] != 'image') { 
		$r = ['image required'];
		echo json_encode($r);
		return; 
	}
	
	
	$con = dbCon();
	$location = p($_POST['location']);
	$brandName = p($_POST['brandName']);
	$model_name = p($_POST['model_name']);
	$Category = p($_POST['Category']);
	$Speed = p($_POST['Speed']);
	$Load = p($_POST['Load']);
	$Drivstoff_forbruk = p($_POST['Drivstoff_forbruk']);
	$Stoy_niva = p($_POST['Stoy_niva']);
	$vat_grep = p($_POST['vat_grep']);
	$Season = p($_POST['Season']);

	$today = date('Y-m-d-Hi');
	$picName = '@'.$today.'.jpg';
	move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/tyreImg/'.$picName);
	#$image = p($_FILE['image']);

	$Season = p($_POST['Season']);
	$Tyre_Info = p($_POST['Tyre_Info']);	
	
	//check for same tyre then input
// 	$q = mysqli_query($con, "SELECT * FROM shop_brand WHERE brand_name='$brandName'");
// 	if(mysqli_num_rows($q) > 0) {
// 		$r = ['already added'];
// 		echo json_encode($r);
// 		return;
// 	}
	$q = mysqli_query($con, "INSERT INTO shop_brand (`id`, `brand_name`, `model_name`, `category`, `speed`, `load`, `drivstoff_forbruk`, `stoy_niva`,`vat_grep`, `picture`, `season`, `tyre_info`, `location`) VALUES
											( null, '$brandName', '$model_name', '$Category', '$Speed', '$Load', '$Drivstoff_forbruk', '$Stoy_niva','$vat_grep', '$picName', '$Season', '$Tyre_Info','$location')" );
	if($q) {
		
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}
function addTyre() {	
	$isNewImage = false;
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	
	foreach($_POST as $post) {
		if(p($post) == '' || p($post) == 'undefined') { $r = ['empty']; echo json_encode($r); exit; }
		
	}
	
	if($_FILES['image']['error'] != 0 || $_FILES['image']['size'] == 0) {
		$image = p($_POST['originalImage']);
		$isNewImage = true;
		if($image == '' || $image == null)
		{
			$r = ['no image']; echo json_encode($r); exit; 
		}
			
	}
	else 
	{
		$image = p($_FILE['image']);
		$type = explode('/', $_FILES['image']['type']);
		if($type[0] != 'image') { 
		$r = ['image required'];
		echo json_encode($r);
		return; 
	}
	}
	
	
	$con = dbCon();
	$brand = p($_POST['brand_name']);
	$model = p($_POST['model']);
	$speed = p($_POST['speed']);
	$load = p($_POST['load']);
	// $size = p($_POST['size']);
	// $price = p($_POST['price']);
	$fuel = p($_POST['fuel']);
	$grip = p($_POST['grip']);
	$noise = p($_POST['noise']);
	$location = p($_POST['locationInfo']);
	// $image = p($_FILE['image']);
	// $image = '';
	$category = p($_POST['category']);
	$season = p($_POST['season']);
 	if(p($_POST['recommended']) == 'on') { $recommended = 1; } else { $recommended = 0; }

	$tyreInfo = p($_POST['tyreInfo']);
	$runFlat = p($_POST['runFlat']);
	$misc = array();
	$misc['tyreInfo'] = $tyreInfo;
	$misc['runFlat'] = $runFlat;
	$misc = json_encode($misc, JSON_UNESCAPED_UNICODE);
	$num = (int)$_POST['totalLocations'];
	for ($i = 0; $i < $num ;$i++ ){

		$size = $size = $_POST['row-'.($i+1)];

	}
	
	//check for same tyre then input
	$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE brand='$brand' AND model='$model' AND size='$size'");
	if(mysqli_num_rows($q) > 0) {
		$r = ['already added'];
		echo json_encode($r);
		return;
	}
	
	
	for ($i = 0; $i < $num ;$i++ ){			
	
		$price = $_POST['rack-'.($i+1)];
		$size = $_POST['row-'.($i+1)];
	
		$q = mysqli_query($con, "INSERT INTO shop_tyres (`id`, `category`, `brand`, `model`, `speed`, `load`, `size`, `price`, `fuel`, `grip`, `noise`, `image`, `recommended`, `season`, `misc`, `location`) VALUES
											(NULL, '$category', '$brand', '$model', '$speed', '$load', '$size', '$price', '$fuel', '$grip', '$noise', '$image', $recommended, '$season', '$misc', '$location')" );
		
		if($q) {
			$newTyreID = (int)mysqli_insert_id($con);
			
			$q = mysqli_query($con, "INSERT INTO shop_stock (`id`, `tyreID`, `stock`, `ordered`, `misc`,`location`) VALUES (NULL, $newTyreID, 0, 0, '','$location')");
			
			$today = date('Y-m-d-Hi');
			if($isNewImage)
			{
				$picName = $image;	
			}
			else 
				{$picName = $newTyreID.'@'.$today.'.jpg';}

			move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/tyreImg/'.$picName);
			
			$q = mysqli_query($con, "UPDATE shop_tyres SET image='$picName' WHERE id=$newTyreID");
		   if($i == $num-1)
		   {
			   $r = ['success'];
			   echo json_encode($r);
			   return;
		   }	
		}			
	}	
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function saveTime() {
	//if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	
	$con = dbCon();
	$day = p($_POST['day']);
	$time = p($_POST['time']);
	$locationID = p($_POST['locationID']);
	$miscID = (int)p($_POST['id']);
	
	$q = mysqli_query($con, "UPDATE shop_misc SET attribute2='$time' WHERE id=$miscID AND attribute1='$day' AND locationID='$locationID'");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function saveService() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	
	$con = dbCon();
	$service = p($_POST['service']);
	$serviceID = (int)p($_POST['id']);
	$price = p($_POST['price']);
	$timeSlots = p($_POST['timeSlots']);
	$maxNum = (int)p($_POST['maxNum']);
	
	$q = mysqli_query($con, "UPDATE shop_misc SET attribute1='$service', attribute2='$price', attribute3='$timeSlots', attribute4='$maxNum' WHERE id=$serviceID");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function saveBank() {
    if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }

    $con = dbCon();
    $serviceID = (int)p($_POST['id']);
    $due_period = p($_POST['due_period']);
    $bank_account = p($_POST['bank_account']);


    $q = mysqli_query($con, "UPDATE shop_bank SET due_period='$due_period', account_in='$bank_account' WHERE id=$serviceID");
    if($q) {
        $r = ['success'];
        echo json_encode($r);
        return;
    }

    $r = ['failed'];
    echo json_encode($r);
    return;
}

function addService() {
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	
	$con = dbCon();
	$service = p($_POST['service']);
    $locationID = p($_POST['locationID']);
	$price = p($_POST['price']);
	$timeSlots = p($_POST['timeSlots']);
	$maxNum = (int)p($_POST['maxNum']);
	
	$q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', 'services', '$service', '$price', '$timeSlots', '$maxNum', '')");
	
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function adminLoggedIn() {
	if(isset($_SESSION['adminID'])) { return true; }
	return true;
}

function sendMessage() {
	$con = dbCon();
	$today = date('Y/m/d H:i');
	$admin = $_SESSION['adminID'];
	$id = (int)p($_POST['id']);
	$adminName = mysqli_fetch_array_n(mysqli_query($con, "SELECT username FROM shop_admins WHERE id=$admin"), MYSQLI_ASSOC)['username'];
	$location = mysqli_fetch_array_n(mysqli_query($con, "SELECT location FROM shop_contact WHERE id=$id"), MYSQLI_ASSOC)['location'];
	$message = p($_POST['message']);
    $email = p($_POST['email']);
    
	$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE id=$id");
	if(mysqli_num_rows($q) == 0) { echo 'not found'; return; }
	
	$q = mysqli_query($con, "UPDATE shop_contact SET replyMessage = '$message', replyBy = '$adminName', replyDate='$today' WHERE id=$id");
	if($q) { 
	    	if($email != '') {
    			$workType = $resArr->workType;
    			$services = $resArr->services;
    			
    		
    			$body = "<html><head></head><body>";
    			$body .= '<b><p>'.$message.'</p></b><br>';
    			$body .= '</body></html>';
    			
    			$arr = array();
    			$arr['location'] = $location;
    			$arr['to'] = $email;
    			$arr['toName'] = $name;
    			$arr['subject'] = 'Melding fra '.$location.'';
    			$arr['body'] = $body;
    			$mail = mailSend($arr);
    		}
	    echo 'success'; return;
	    }
	
	echo 'failed';
	return;
	
}


function saveNorgesDekk() {
	$con = dbCon();
	$attribute1 = p($_POST['attribute1']);
	$attribute2 = (int)p($_POST['attribute2']);
	$attribute3 = p($_POST['attribute3']);
	$miscID = (int)p($_POST['id']);
	
	$q = mysqli_query($con, "UPDATE shop_misc SET attribute1='$attribute1',attribute2=$attribute2, attribute3='$attribute3' WHERE id=$miscID");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	
	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function getTyresFromSFTP() {

    $con = dbCon();

    // CLEAN tabel
    $sql = "TRUNCATE TABLE shop_tyres_api";
    if (!mysqli_query($con, $sql)) {
        echo "Error emptying table: " . mysqli_error($con);
    }
    // get delay from shop_misc-tabel
    $sql = "SELECT `attribute3` FROM `shop_misc` WHERE `property` = 'shopTyresApiDelay'";
    $result = mysqli_query($con, $sql);
    
    $delay = 3; //standard
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $delay = $row['attribute3'];
    }    
    
    
    // get recomended brands shop_misc-tabel
    $sql = "SELECT `attribute1` FROM `shop_misc` WHERE `property` = 'shopTyresApiRecomendedBrands'";
    $result = mysqli_query($con, $sql);
    
    $dbRecBrands = ''; //standard
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dbRecBrands = $row['attribute1'];
    }     
    
    // get brands shop_misc-tabel
    $sql = "SELECT `attribute1` FROM `shop_misc` WHERE `property` = 'shopTyresApiBrands'";
    $result = mysqli_query($con, $sql);
    
    $allowedProdusent = array(); // Initiera som en tom array
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $brands = array_map('trim', explode(",", $row['attribute1']));
            
            // Lägg till märkena i arrayen utan dubletter
            foreach ($brands as $brand) {
                if (!in_array($brand, $allowedProdusent)) {
                    $allowedProdusent[] = $brand;
                }
            }
        }
    }
    
    $allowedCities = ['Oslo', 'Tønsberg'];
    // Fetch stock data from second SFTP link
    $stockData = [];
    if (($handle = fopen("https://sftp.dekkpro.no/web/client/pubshares/LZfT8ixVhrVu7hW3rfkXym?compress=false", "r")) !== FALSE) {
        while (($dataRow = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $city = $dataRow[0];
            $varenr = $dataRow[2];
            $stock = $dataRow[3];

            if (in_array($city, $allowedCities) || strpos($city, 'nsberg') !== false) {
                if (!isset($stockData[$varenr])) {
                    $stockData[$varenr] = 0;
                }
                $stockData[$varenr] += (int) $stock;
            }
        }
        fclose($handle);
    }
    
    $data = [];
    $row = 1;
    $rad = 1;
    
    $allowedKategori = ['Sommerdekk', 'M+S Pigg', 'M+S'];
   // $allowedProdusent = array_map('trim', explode(",", $dbBrands));
    $allowedProdusentLowercase = array_map('strtolower', $allowedProdusent);
    $recProdusent = array_map('trim', explode(",", $dbRecBrands));
    $recProdusentLowercase = array_map('strtolower', $recProdusent);
    
    if (($handle = fopen("https://sftp.dekkpro.no/web/client/pubshares/FaXrE8DdnmNzZWTwj2JWWU?compress=false", "r")) !== FALSE) {
        while (($dataRow = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if ($row == 1) { // Header row
                $headers = $dataRow;
                $row++;
                continue;
            }

            $kategori = $dataRow[array_search("Kategori", $headers)];
            $produsent = $dataRow[array_search("Produsent", $headers)];
            $produsentLowercase = strtolower($produsent);

            // Check if the row matches the criteria
            if (in_array($kategori, $allowedKategori) && in_array($produsentLowercase, $allowedProdusentLowercase)) {
               if (in_array($produsentLowercase, $recProdusentLowercase)) {
                  $recommendBrand = 1;
                }else $recommendBrand = 0;
                $data[] = [
                    "ID" => $rad++, //id
                    "Varenr" => $dataRow[array_search("Varenr.", $headers)], //varenr
                    "Produsent" => $produsent, //brand
                    "Kategori" => $kategori, //season
                    "Produkt" => $dataRow[array_search("Produkt", $headers)], //model
                    "Bredde" => $dataRow[array_search("Bredde", $headers)], //width
                    "Profil" => $dataRow[array_search("Profil", $headers)], //profile
                    "RulleMotsatand" => $dataRow[array_search("RulleMotsatand", $headers)], //fuel
                    "Diameter" => $dataRow[array_search("Diameter", $headers)], //inches
                    "Li" => $dataRow[array_search("Li", $headers)], //load
                    "Si" => $dataRow[array_search("Si", $headers)], //speed
                    "Grip" => $dataRow[27], //grip 
                    "DB" => $dataRow[array_search("DB", $headers)], //db
                    "EuClass" => $dataRow[array_search("EuClass", $headers)], //euClass
                    "EuDirective" => $dataRow[array_search("EuDirective", $headers)], //euDirective
                    "Listepris" => $dataRow[array_search("Listepris", $headers)], //price
                    "Bildelenk" => $dataRow[array_search("Bildelenk", $headers)], //image
                    "Ean" => $dataRow[array_search("EANKode", $headers)], //ean
                    "Stock" => isset($stockData[$dataRow[array_search("Varenr.", $headers)]]) ? $stockData[$dataRow[array_search("Varenr.", $headers)]] : 0,
                    "Recommended" => $recommendBrand //$recommended
                    
                ];
            }
            $row++;
        }
        fclose($handle);
    }

    // Prepare Insert
    $stmt = $con->prepare("INSERT INTO shop_tyres_api (id, category, varenr, brand, season, model, width, profile, fuel, inches, `load`, speed, grip, noise, euClass, euDirective, price, image,ean, delay, stock,recommended) VALUES (?, ?,?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)");

    // Retrieve the mapping of Produsent to category
    $sql = "SELECT `attribute1` AS 'Produsent', `attribute3` AS 'Category' FROM `shop_misc` WHERE `property` = 'shopTyresApiBrandDiscount'";
    $result = mysqli_query($con, $sql);
    
    // Create an associative array to store the mapping
    $produsentToCategoryMapping = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $produsentToCategoryMapping[$row['Produsent']] = $row['Category'];
        }
    }
    
    // Iterate over $data and determine category and season
    foreach ($data as $row) {
        switch ($row["Kategori"]) {
            case 'M+S Pigg':
                $season = 'winterStudded';
                break;
            case 'M+S':
                $season = 'winter';
                break;
            case 'Sommerdekk':
                $season = 'summer';
                break;
            default:
                $season = '';
                break;
        }
    
        $category = $produsentToCategoryMapping[$row["Produsent"]] ?? ''; // Use the mapping, default to empty string if not found
        $roundedBredde = round($row["Bredde"]);
        $roundedProfil = round($row["Profil"]);
        $roundedDiameter = round($row["Diameter"]);
        $roundedListepris = round($row["Listepris"]);
        
        if ($row["Stock"] > 3) {
            $stmt->bind_param("isssssssssssssssisssii", 
                $row["ID"],
                $category,
                $row["Varenr"],
                $row["Produsent"],
                $season,
                $row["Produkt"],
                $roundedBredde,
                $roundedProfil,
                $row["RulleMotsatand"],
                $roundedDiameter,
                $row["Li"],
                $row["Si"],
                $row["Grip"],
                $row["DB"],
                $row["EuClass"],
                $row["EuDirective"],
                $roundedListepris,
                $row["Bildelenk"],
                $row["Ean"],
                $delay,
                $row["Stock"],
                $row["Recommended"]
                
            );
        
    
            if (!$stmt->execute()) {
                $r = ['error' => $stmt->error];
                echo json_encode($r);
                return;
            }else {
                $r = ['success'];
        		
            }
        }
    }
    echo json_encode($r);
    return;
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}

function addBrandDiscount() {
    $con = dbCon();
    
    $brandName = p($_POST['brandName']);
    $attribute2 = p($_POST['attribute2']); //discount
    $attribute3 = p($_POST['attribute3']); //class
    $attribute5 = p($_POST['attribute5']); //Moss Dekk AS
    $property = 'shopTyresApiBrandDiscount';
    $locationID = 0;

    $q = mysqli_query($con, "INSERT INTO shop_misc (`id`, `locationID`, `property`, `attribute1`, `attribute2`, `attribute3`, `attribute4`, `attribute5`) VALUES (NULL, '$locationID', '$property', '$brandName', '$attribute2', '$attribute3', '', '$attribute5')");
    if($q) {
        $r = ['success'];
        echo json_encode($r);
        return;
    }

    $r = ['failed'];
    echo json_encode($r);
    return;
}

function listTyresFromSFTP() {
	$con = dbCon();
	$value = p($_POST['value']);
	
	if($value == '') {
		$r = ['empty'];
		echo json_encode($r);
		return;
	}
	// Get price adjustments from misc tabel, brand discount and fixed %
	$attributes = mysqli_query($con, "SELECT `property`,`attribute1`,`attribute2`,`attribute3` FROM shop_misc WHERE  attribute5 = 'Moss Dekk AS' and (property = 'shopTyresApiPriceRegulator' OR property = 'shopTyresApiBrandDiscount')");
    $attributeValues = mysqli_fetch_all_n($attributes, MYSQLI_ASSOC);

    $priceRegulatorPercent = 0;
    $priceRegulatorFixed = 0;
    $brandDiscounts = [];

    foreach ($attributeValues as $attr) {
        if ($attr['property'] == 'shopTyresApiPriceRegulator') {
            $priceRegulatorPercent = $attr['attribute2'];
            $priceRegulatorFixed = $attr['attribute3'];
        } else if ($attr['property'] == 'shopTyresApiBrandDiscount') {
            $brandDiscounts[$attr['attribute1']] = $attr['attribute2'];
        }
    }
	
	
   	$q = mysqli_query($con, "SELECT `id`, `varenr`, `brand`, `season`, `model`, `width`, `profile`, `fuel`, `inches`, `load`, `speed`, `noise`, `ean` , `euClass`, `euDirective`, `price`, `stock`, `category`  FROM `shop_tyres_api` where `varenr` LIKE '%$value%' or  `brand` LIKE '%$value%' or  `model` LIKE '%$value%'");
    if(mysqli_num_rows($q) > 0) {
        $fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
        $trList = "";
        $i = 1; 
    
        foreach($fetch as $f) {
                // If the tyre's brand doesn't have a discount, skip this iteration
            if (!isset($brandDiscounts[$f['brand']])) {
                continue;  // Skip to the next tyre in the list
            }
            $brandMultiplier = isset($brandDiscounts[$f['brand']]) ? 1 - ($brandDiscounts[$f['brand']] / 100) : 1; // Convert the discount to a multiplier
            $customerPrice = ($f['price'] * $brandMultiplier * (1 + $priceRegulatorPercent/100) + $priceRegulatorFixed) ;
            $customerPrice *= 1.25; // Add moms (25%)
            $trList  .= '<tr>';
            $trList  .= '<td>'.$i.'</td>';
            $trList  .= '<td>'.$f['varenr'].'</td>';
            $trList  .= '<td>'.$f['brand'].'</td>';
            $trList  .= '<td>'.$f['season'].'</td>';
            $trList  .= '<td>'.$f['model'].'</td>';
            $trList  .= '<td>'.$f['width'].'</td>';
            $trList  .= '<td>'.$f['price'].'</td>';
            $trList  .= '<td>'.round($customerPrice).'</td>';
            $trList  .= '<td>'.$f['profile'].'</td>';
            $trList  .= '<td>'.$f['fuel'].'</td>';
            $trList  .= '<td>'.$f['inches'].'</td>';
            $trList  .= '<td>'.$f['load'].'</td>';
            $trList  .= '<td>'.$f['speed'].'</td>';
            $trList  .= '<td>'.$f['noise'].'</td>';
            $trList  .= '<td>'.$f['ean'].'</td>';
            $trList  .= '<td>'.$f['euClass'].'</td>';
            $trList  .= '<td>'.$f['euDirective'].'</td>';
            $trList  .= '<td>'.$f['stock'].'</td>';
            $trList  .= '<td>'.$f['category'].'</td>';
            $trList  .= '</tr>';
            $i++;
        }
        $r = ['success', $trList];
		echo json_encode($r);
		return;
    }
	$r = ['no entries'];
	echo json_encode($r);
	return;
}
?>