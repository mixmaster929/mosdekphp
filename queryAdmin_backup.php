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
    case 'pdfDownload':
        pdfDownload();
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
	default: echo '<script> alert("You are now being Tracked"); </script>'; die;
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
function customerSearch() {
	$con = dbCon();
	$type = p($_POST['type']);
	$value = p($_POST['value']);
	
	if($type == '' || $value == '') {
		$r = ['empty'];
		echo json_encode($r);
		return;
	}
	
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE $type LIKE '%$value%'");
	if(mysqli_num_rows($q) > 0) {
		$tr = '';
		$i = 1;
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {
			$button = '<button class="btn btn-sm btn-danger text-white py-0 m-0 deleteButton'.$f['id'].' button'.$f['if'].'" onclick="deleteCustomer('.$f['id'].')">Delete</button>';
		
			$tr .= '<tr id="tr'.$f['id'].'">';
			$tr .= '<td>'.$i.'</td>';
			$tr .= '<td>'.$f['fullName'].'</td>';
			$tr .= '<td>'.$f['username'].'</td>';
			$tr .= '<td title="'.$f['password'].'"></td>';
			$tr .= '<td>'.$f['email'].'</td>';
			$tr .= '<td>'.$f['mobile'].'</td>';
			$tr .= '<td>'.$f['regNr'].'</td>';
			$tr .= '<td>'.$f['postCode'].'</td>';
			$tr .= '<td>'.$f['address'].' '.$f['city'].'</td>';
			//$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span><input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
			//$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span><input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
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
		$q = mysqli_query($con, "SELECT * FROM shop_admins WHERE username='$username' AND password='$passwordSHA' AND id != $adminID");
		if(mysqli_num_rows($q) > 0) {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		$q = mysqli_query($con, "UPDATE shop_admins SET `username`='$username', `password`='$passwordSHA' WHERE id=$adminID");
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
	//$email = p($_POST['email']);
	$site = p($_POST['site']);
	
	if($username == '' || $username == '' ) { $r = ['empty']; echo json_encode($r); return; }
	
	if($site == 'tyreShop') {
		$q = mysqli_query($con, "SELECT * FROM shop_admins WHERE username='$username' AND password='$password'");
		if(mysqli_num_rows($q) > 0) {
			$r = ['exists'];
			echo json_encode($r);
			return;
		}
		
		$q = mysqli_query($con, "INSERT INTO shop_admins (`id`, `username`, `password`, `email`) VALUES (NULL, '$username', '$password', '')");
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
	
	if($type == '' || $value == '') { $r = ['empty']; echo json_encode($r); return; }
	if($type == 'brandModel') {
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE brand LIKE '%$value%' OR model LIKE '%$value%'");
	}else {
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE size LIKE '%$value%'");
	}
	
	if(mysqli_num_rows($q) == 0) {
		$r = ['no entries'];
		echo json_encode($r);
		return;
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
		$tr .= '<td style="max-width:200px;">'.$productDesc.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
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
	
	$q = mysqli_query($con, "SELECT * FROM shop_stock WHERE ".$stockQuery."");
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
		$tr .= '<td style="max-width:200px;">'.$productDesc.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span>
					<input type="number" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
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
	$q = mysqli_query($con, "SELECT * FROM shop_orders");
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
	
	$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE ".$miscQuery."");
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
	
	$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE ".$type." LIKE '%".$value."%'");
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

		$q = mysqli_query($con, "UPDATE shop_tyres SET `category`='$category', `brand`='$brand', `model`='$model', `speed`='$speed', `load`='$load', `size`='$size', `price`='$price', `fuel`='$fuel', `grip`='$grip',`recommended`='$recommended', `noise`='$noise', `misc`='$misc', `season`='$season' WHERE id=$tyreID");
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
	
	$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE $type LIKE '%$value%'");
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
    if($seen == 0) {
        $paidButton = '<button class="btn btn-sm btn-outline-success py-0 m-0" >Paid</button>';
    }
    $nopaidButton = '<button class="btn btn-sm btn-outline-danger py-0 m-0" >No Paid</button>';
	
	$q = mysqli_query($con, "SELECT * FROM shop_invoice WHERE $type LIKE '%$value%'");
	if(mysqli_num_rows($q) > 0) {
		
		$tr = '';
		$i = 1;
		
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) {

            $imageLink = '<i class="fa fa-file-pdf-o"></i>';
            $paymentMode = $f['paymentMode'];

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
		}
		$r = ['success', $tr];
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

function pdfDownload(){
    $con = dbCon();
    $id = p($_POST['id']);

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

    // Include mpdf library file
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();

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

    $mpdf->WriteHTML($pdfcontent);

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

	$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE $type LIKE '%$value%'");
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
	$q = mysqli_query($con, "INSERT INTO shop_brand (`id`, `brand_name`, `model_name`, `category`, `speed`, `load`, `drivstoff_forbruk`, `stoy_niva`,`vat_grep`, `picture`, `season`, `tyre_info`) VALUES
											( null, '$brandName', '$model_name', '$Category', '$Speed', '$Load', '$Drivstoff_forbruk', '$Stoy_niva','$vat_grep', '$picName', '$Season', '$Tyre_Info')" );
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
	
		$q = mysqli_query($con, "INSERT INTO shop_tyres (`id`, `category`, `brand`, `model`, `speed`, `load`, `size`, `price`, `fuel`, `grip`, `noise`, `image`, `recommended`, `season`, `misc`) VALUES
											(NULL, '$category', '$brand', '$model', '$speed', '$load', '$size', '$price', '$fuel', '$grip', '$noise', '$image', $recommended, '$season', '$misc')" );
		
		if($q) {
			$newTyreID = (int)mysqli_insert_id($con);
			
			$q = mysqli_query($con, "INSERT INTO shop_stock (`id`, `tyreID`, `stock`, `ordered`, `misc`) VALUES (NULL, $newTyreID, 0, 0, '')");
			
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
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	
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
	$adminName = mysqli_fetch_array_n(mysqli_query($con, "SELECT username FROM shop_admins WHERE id=$admin"), MYSQLI_ASSOC)['username'];
	$id = (int)p($_POST['id']);
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
    			
    			$body .= 'Hey '.$name.', <br>';
    			$body .= 'Thank your messaging, <br>';
    			$body .= 'I have seen your messages.';
    			$body .= '<br><br>';
    			
    			$body .= 'This is my answer:<br>';
    			
    			$body .= '<b><p>'.$message.'</p></b><br>';
    			$body .= '</body></html>';
    			
    			$arr = array();
    			$arr['to'] = $email;
    			$arr['toName'] = $name;
    			$arr['subject'] = 'Please Answer to me';
    			$arr['body'] = $body;
    			$mail = mailSend($arr);
    		}
	    echo 'success'; return;
	    }
	
	echo 'failed';
	return;
	
}
// Adding query admin function for api
function tyreSearchApi() {			
	if(!adminLoggedIn()) { $r = ['no admin']; echo json_encode($r); return; }
	$con = dbCon();

	$type = p($_POST['type']);
	$value = p($_POST['value']);

	$q = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE $type LIKE '%$value%'");
	if(mysqli_num_rows($q) > 0) {

		$tr = '';
		$i = 1;

		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
		foreach($fetch as $f) { 
			$imageLink = '';
			if($f['image'] != '') {
				$imageLink = '<a href="'.$f['image'].'" target="_blank">View</a>';
			}

			$checked = "";
				
			$tr .= '<tr>
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
						<span class="txtField'.$f['id'].'">'.$f['width'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['width'].'" id="width'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['profile'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['profile'].'" id="profile'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['inches'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['inches'].'" id="inches'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['price'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['price'].'" id="price'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['fuel'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['fuel'].'" id="fuel'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$f['noise'].'</span>
						<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['noise'].'" id="noise'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
					</td>
					
					<td>
						<span class="txtField'.$f['id'].'">'.$f['season'].'</span>
					</td>
					<td>
						<span class="txtField'.$f['id'].'">'.$imageLink.'</span>
						 <input type="file" class="form-control-file editField'.$f['id'].'" id="image'.$f['id'].'" style="display:none;">
						<div id="imageUploadBar'.$f['id'].'" style="display:none;"><img src="../images/Rolling.gif" id="uploadLoading'.$f['id'].'" style="width:20px; margin-right:10px;" /> <span id="uploadPerc'.$f['id'].'" style=""></span>%</div>
					</td>
					
					<td>
						<button class="btn btn-sm btn-outline-warning py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="edit('.$f['id'].')">Edit</button>
						<button class="btn btn-sm btn-outline-success py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="saveNew('.$f['id'].')" style="display:none;">Save</button>
						<button class="btn btn-sm btn-outline-danger py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="deleteRow(\'tyres\', '.$f['id'].', 1)">Delete</button>
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
?>