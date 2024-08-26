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
	case 'registerCustomerWithoutTyre':
		registerCustomerWithoutTyre();
		break;
    case 'deleteCustomer':
        deleteCustomer();
        break;
    case 'inOutComingOrder':
        inOutComingOrder();
        break;
	case 'updateCustomer':
	    updateCustomer();
	    break;
     case 'changeOrderDate':
         changeOrderDate();
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
    case 'fetchTimeOrderSlots':
        fetchTimeOrderSlots();
        break;
	case 'getServices':
		getServices();
		break;
	case 'getMossDekkPrice':
		getMossDekkPrice();
		break;
	case 'errorHandling':
	    errorHandling();
	    break;
	case 'checkReservedTime':
		checkReservedTime();
		break;		
	case 'reservTime':
		reservTime();
		break;
	case 'fetchFrontTyres':
		fetchFrontTyres();
		break;
	case 'checkedRegNr':
		checkedRegNr();
		break;
	case 'checkPassword':
	    checkPassword();
		break;
	case 'forgotPassword':
		forgotPassword();
		break;
	case 'validateAdminPassword':
		validateAdminPassword();
		break;
	case 'vegvesenAPIGet':
		vegvesenAPIGet();
		break;		
		

	default: echo '<script> alert("You are now being Tracked"); </script>'; die;
}

function sendSms($mobile,$message) {
    
    //Send an SMS using Gatewayapi.com
    $url = "https://gatewayapi.com/rest/mtsms";
    $api_token = "ymZ5l7NdTl2uPEnO-uymgG_iU-PEQekMZ0si0C0Xs6-5ujNtsvkbJkgUP5Cfx-nM";
    

    
   $recipients = [$mobile];
    
    //Set SMS recipients and content
    $json = [
        'sender' => 'Mossdekk',
        'message' => $message,
        'recipients' => [],
    ];
    foreach ($recipients as $msisdn) {
        $json['recipients'][] = ['msisdn' => $msisdn];
    }
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_USERPWD, $api_token . ":");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    curl_close($ch);
    
    $response = [
        'status' => $http_status,
        'result' => json_decode($result),
    ];
    
}

function forgotPassword() 
{
    $con = dbCon();
	$mode = p($_POST['mode']);
	$receiver = p($_POST['receiver']);
	if($mode == 'sms')
	{
	   $query = http_build_query(array(
		'token' => 'qj2__nbIQ2GYY4ueCykliF8KOLmkhcR0y24yv-Wt37OHmqjYDzAx7XJS4akVRHm4',
		'sender' => 'ExampleSMS',
		'message' => $body,
		'recipients.0.msisdn' => $receiver,
	    ));
		$result = file_get_contents('https://gatewayapi.com/rest/mtsms?' . $query);
		$json = json_decode($result);
		$r = [$json];
		echo json_encode($json);
		return;  
    }
	else if($mode == 'email')
	{
	if(isset($_POST["receiver"]) && (!empty($_POST["receiver"]))){
		$email = $_POST["receiver"];
		$error = "";
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
			$error .= "Invalid email address";
        //    $error .="Invalid email address please type a valid email address!";
           }else{
           $sel_query = "SELECT * FROM `shop_customers` WHERE email='".$email."'";
           $results = mysqli_query($con,$sel_query);
           $row = mysqli_num_rows($results);
           if ($row==""){
		//    $error .= "<p>No user is registered with this email address!</p>";
			  $error .="No user";
           }
          }
           if($error!=""){
        //    echo "<div class='error'>".$error."</div>
		//    <br /><a href='javascript:history.go(-1)'>Go Back</a>";
				$r = [$error];
				echo json_encode($r);
				return;
		   }
		   else
		   {
				$expFormat = mktime(
				date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
				);
				$expDate = date("Y-m-d H:i:s",$expFormat);
				$key = md5(2418*2+$email);
				$addKey = substr(md5(uniqid(rand(),1)),3,10);
				$key = $key . $addKey;
				// Insert Temp Table
				mysqli_query($con,
				"INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
				VALUES ('".$email."', '".$key."', '".$expDate."');");
				
				$output='<p>Dear user,</p>';
				$output.='<p>Please click on the following link to reset your password.</p>';
				$output.='<p>-------------------------------------------------------------</p>';
				$output.='<p><a href="https://mossdekk.no/shop/resetPassword.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">
				https://mossdekk.no/shop/resetPassword.php
				?key='.$key.'&email='.$email.'&action=reset</a></p>';		
				$output.='<p>-------------------------------------------------------------</p>';
				$output.='<p>Please be sure to copy the entire link into your browser.
				The link will expire after 1 day for security reason.</p>';
				$output.='<p>If you did not request this forgotten password email, no action 
				is needed, your password will not be reset. However, you may want to log into 
				your account and change your security password as someone may have guessed it.</p>';   	
				$output.='<p>Thanks,</p>';
				$output.='<p>Mossdekk Team</p>';
				$body = $output; 
				$subject = "Password Recovery - mossdekk.no";
				
				$email_to = $email;
				$fromserver = "noreply@yourwebsite.com"; 
				
				
				$arr = array();
				$arr['to'] = $email_to;
				$arr['toName'] = 'username';
				$arr['subject'] = $subject;
				$arr['body'] = $body;
				$mail = mailSend($arr);
						
				if(!mailSend($arr)){
				echo "Mailer Error: " . $mail->ErrorInfo;
				}else{
					$r = ['success'];
					echo json_encode($r);
					return;
					}
					
           	}
        }
	}
}

function checkPassword()
{
    $regNr = p($_POST['regNr']);
    $password = md5(p($_POST['password']));
    $con = dbCon();
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE regNr like '%$regNr%' AND password = '$password'");
	if(mysqli_num_rows($q) > 0)
    {
		echo 'success';
		
		return;
    }
    else 
    {
		echo 'failed';
		return;
    }
}

function validateAdminPassword()
{
    $password = md5(p($_POST['password']));
    $con = dbCon();
	$q = mysqli_query($con, "SELECT * FROM shop_admins WHERE username = 'auto1' AND password = '$password'");
	if(mysqli_num_rows($q) > 0)
    {
		$r = ['success'];
		echo json_encode($r);
    }
    else 
    {
		$r = ['failed'];
		echo json_encode($r);
    }
}

function checkedRegNr() {
    $modal = p($_POST['modal']);
    $regNr = p($_POST['regNr']);

    // Check if regNr has a value
    if (empty($regNr)) {
        echo json_encode(['result'=> 'regNr is empty']);
        return;
    }

    $con = dbCon();
    $q = mysqli_query($con, "SELECT * FROM shop_customers WHERE regNr like '%$regNr%' and location = 'Moss Dekk AS'");
    
    if (mysqli_num_rows($q) > 0) {
        $f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
        $id = $f['id'];
        $name = $f['fullName'];
        $email = $f['email'];
        $mobile = $f['mobile'];
        $password = $f['password'];
        $location = $f['location'];
        $arr = ['result'=>'success', 'id' => $id, 'name'=>$name, 'mobile'=>$mobile, 'email'=>$email, 'password'=>$password, 'location'=>$location];
        echo json_encode($arr);
        return;
    } else {
        echo json_encode(['result'=> 'no entry']);
        return;
    }
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

	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE username = '$username' AND password='$password' and location = 'Moss Dekk AS'");
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
	$location = "Moss Dekk AS";
	$price = "1090";
	$email = p($_POST['email']);
	$today = date('Y/m/d H:i');

    //delivery information
	$delivery_date = p($_POST['delivery_date']);
    $delivery_time = p($_POST['delivery_time']);
    $tyreSize = p($_POST['tyreSize']);
    $season = p($_POST['season']);
    if($season == 1)
        $season = "summer";
    else
        $season = "winter";

	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE username='$username' and location='$location' ");
	if(mysqli_num_rows($q) > 0) {
		$r = ['exists'];
		echo json_encode($r);
		return;
	}

	//fix delivery date form
    $dateArray = explode('/', $delivery_date);
    $year = $dateArray[0];
    $month = $dateArray[1];
    $day = $dateArray[2];
    $orderedOn = $day.'.'.$month.'.'.$year.' '.$delivery_time;

    $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
    $postData = [
        'method' => 'registerOrderForTyreShop',
        'name' => $fullName,
        'regNr' => $regNr,
        'email' => $email,
        'postCode' => $postCode,
        'address' => $address,
        'city' => $city,
        'location' => $location,
        'price' => $price,
        'phone' => $mobile,
        'orderedOn' => $orderedOn,
        'tyreSize' => $tyreSize,
        'season' => $season
    ];

    $response = get_web_page($url, $postData);

    $resArr = array();
    $resArr = json_decode($response);
    if(!is_object($resArr)) {
        $r = ['api error'];
        echo json_encode($r);
        return;
    }

    if($resArr->result == 'success') {
        $output='<p>Hei!</p>';
        $output.='<p>Velkommen som kunde hos Moss Dekk</p>';
        $output.='<p></p>';
        $output.='<p>Reg nr:</p> '.$regNr;
        $output.='<p>Bruker navn:</p> '.$fullName;
        $output.='<p>Passord:</p> '.$password;
        $output.='<p></p>';
        $output.='<p>PDato og tid for innlevering av hjul:</p> '.$delivery_date;
        $output.='<p>For bestilling av time for våre tjenester , vennligst benytt www.mossdekk.no</p>';
        $output.='<p></p>';
        $output.='<p>MVH</p>';
        $output.='<p></p>';
        $output.='<p>Moss Dekk</p>';

        $body = $output;
        $subject = "Velkommen som kunde Hos Mossdekk";

        $email_to = $email;


        $arr = array();
        $arr['to'] = $email_to;
        $arr['toName'] = $fullName;
        $arr['subject'] = $subject;
        $arr['body'] = $body;
        $mail = mailSend($arr);

    }elseif($resArr->result == 'failed') {
        $r = ['api failed'];
        echo json_encode($r);
        return;
    }

	$q = mysqli_query($con, "INSERT INTO shop_customers (`id`, `createdOn`, `username`, `password`, `fullName`, `email`, `regNr`, `mobile`, `postCode`, `address`, `city`, `location`, `price`, `misc`) 
												VALUES (NULL, '$today', '$username', '$password', '$fullName', '$email', '$regNr', '$mobile', '$postCode', '$address', '$city', '$location', '$price', '')");
	if($q) {
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function registerCustomerWithoutTyre() {
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
	$location = "Moss Dekk AS";
	$price = "1090";
	$email = p($_POST['email']);
	$today = date('Y/m/d H:i');

	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE username='$username' and location='$location' ");
	if(mysqli_num_rows($q) > 0) {
		$r = ['exists'];
		echo json_encode($r);
		return;
	}

	$q = mysqli_query($con, "INSERT INTO shop_customers (`id`, `createdOn`, `username`, `password`, `fullName`, `email`, `regNr`, `mobile`, `postCode`, `address`, `city`, `location`, `price`, `misc`) 
												VALUES (NULL, '$today', '$username', '$password', '$fullName', '$email', '$regNr', '$mobile', '$postCode', '$address', '$city', '$location', '$price', '')");
	if($q) {
	    // email:
        $output='<p>Hei!</p>';
        $output.='<p>Velkommen som kunde hos Moss Dekk</p>';
        $output.='<p></p>';
        $output.='<p>Reg nr:</p> '.$regNr;
        $output.='<p>Bruker navn:</p> '.$fullName;
        $output.='<p>Passord:</p> '.$password;
        $output.='<p></p>';
        $output.='<p>For bestilling av time for våre tjenester , vennligst benytt www.mossdekk.no</p>';
        $output.='<p></p>';
        $output.='<p>MVH</p>';
        $output.='<p></p>';
        $output.='<p>Moss Dekk</p>';
    
        $body = $output;
        $subject = "Velkommen som kunde Hos Moss Dekk";
    
        $email_to = $email;
    
    
        $arr = array();
        $arr['to'] = $email_to;
        $arr['toName'] = $fullName;
        $arr['subject'] = $subject;
        $arr['body'] = $body;
        $mail = mailSend($arr);
	    
		$r = ['success'];
		echo json_encode($r);
		return;
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
}

function deleteCustomer() {
	foreach($_POST as $key=>$value) {
		if(empty($value)) {
			$r = ['empty'];
			echo json_encode($r);
			return;
		}
	}

	$con = dbCon();
	$regNrDeleteCustomer = p($_POST['regNrDeleteCustomer']);
    $email = p($_POST['emailDeleteCustomer']);
    $pickupDateDeleteCustomer = p($_POST['pickupDateDeleteCustomer']);
    $dateArray = explode('/', $pickupDateDeleteCustomer);
    $year = $dateArray[0];
    $month = $dateArray[1];
    $day = $dateArray[2];
    $pickupDateDeleteCustomer = $day.'.'.$month.'.'.$year;


	$qc = mysqli_query($con, "SELECT * FROM shop_customers WHERE regNr LIKE '%$regNrDeleteCustomer%'");
	if(mysqli_num_rows($qc) == 0) {
	    $message = "no exist";
	}
	else{
        $fc = mysqli_fetch_array_n($qc, MYSQLI_ASSOC);
//        $email = $fc['email'];
        $fullName = $fc['fullName'];
        $qc = mysqli_query($con, "DELETE FROM `shop_customers` WHERE regNr LIKE '%$regNrDeleteCustomer%'");
    }

    $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
    $postData = [
        'method' => 'deleteOrderForTyreShop',
        'regNr' => $regNrDeleteCustomer,
        'pickupDate' => $pickupDateDeleteCustomer
    ];

    $response = get_web_page($url, $postData);

    $resArr = array();
    $resArr = json_decode($response);

    if(!is_object($resArr)) {
        $r = ['api error'];
        echo json_encode($r);
        return;
    }
    if($resArr->result == 'success') {
        $output='<p>Hei!</p>';
        $output.='<p>Din kundeforhold er nå avsluttet hos oss i Moss Dekk  :</p>';
        $output.='<p></p>';
        $output.='<p>Reg nr:</p> '.$regNrDeleteCustomer;
        $output.='<p></p>';
        $output.='<p>Hjulene kan hentes: dato og tid :</p> '.$pickupDateDeleteCustomer;
        $output.='<p></p>';
        $output.='<p>MVH</p>';
        $output.='<p></p>';
        $output.='<p>Moss Dekk</p>';

        $body = $output;
        $subject = "Din kundeforhold er nå avsluttet";

        $email_to = $email;


        $arr = array();
        $arr['to'] = $email_to;
        $arr['toName'] = $fullName;
        $arr['subject'] = $subject;
        $arr['body'] = $body;
        $mail = mailSend($arr);

        $r = ['success'];
        echo json_encode($r);
        return;
    }
    else if($resArr->result == 'No tyres') {
        $r = ['no tyres'];
        echo json_encode($r);
        return;
    }
    else if($resArr->result == 'failed') {
        $r = ['api failed'];
        echo json_encode($r);
        return;
    }
}

function inOutComingOrder() {
    foreach($_POST as $key=>$value) {
        if(empty($value)) {
            $r = ['empty'];
            echo json_encode($r);
            return;
        }
    }

    $con = dbCon();
    $regNrOut = p($_POST['regNrOutComingOrder']);
    $email = p($_POST['emailOutComingOrder']);
    $pickupDate = p($_POST['pickupDateOutComingOrder']);
    $pickupTime = p($_POST['pickupTimeOutComingOrder']);

    $regNrIn = p($_POST['regNrInComingOrder']);
    $tyreSize = p($_POST['tyreSize']);
    $deliveryDate = p($_POST['deliveryDateInComingOrder']);
    $deliveryTime = p($_POST['deliveryTimeInComingOrder']);
    $season = p($_POST['seasonInComingOrder']);
    if($season == 1)
        $season = "summer";
    else
        $season = "winter";

    //fix pickup date form
    $dateArray = explode('/', $pickupDate);
    $year = $dateArray[0];
    $month = $dateArray[1];
    $day = $dateArray[2];
    $pickupDate = $day.'.'.$month.'.'.$year;

    //fix delivery date form
    $dateArray = explode('/', $deliveryDate);
    $year = $dateArray[0];
    $month = $dateArray[1];
    $day = $dateArray[2];
    $orderOn = $day.'.'.$month.'.'.$year.' '.$deliveryTime;

    $qc = mysqli_query($con, "SELECT * FROM shop_customers WHERE regNr LIKE '%$regNrOut%'");
    if(mysqli_num_rows($qc) == 0) {
        $message = "no exists";
    }
    else {
        $fc = mysqli_fetch_array_n($qc, MYSQLI_ASSOC);
        $id = $fc['id'];
//        $email = $fc['email'];
        $fullName = $fc['fullName'];
        $qc = mysqli_query($con, "UPDATE shop_customers SET regNr='$regNrIn' WHERE id=$id");

    }

    $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
    $postData = [
        'method' => 'replaceOrderForTyreShop',
        'regNrOut' => $regNrOut,
        'pickupDate' => $pickupDate,
        'pickupTime' => $pickupTime,
        'regNrIn' => $regNrIn,
        'tyreSize' => $tyreSize,
        'orderOn' => $orderOn,
        'season' => $season
    ];

    $response = get_web_page($url, $postData);

    $resArr = array();
    $resArr = json_decode($response);
    if(!is_object($resArr)) {
        $r = ['api error'];
        echo json_encode($r);
        return;
    }

    if($resArr->result == 'success') {
        $output='<p>Hei!</p>';
        $output.='<p>Du har bestilt bytte av dekkhotellplass.</p>';
        $output.='<p></p>';
        $output.='<p>Utlevering av hjul til ditt eksisterende dekkhotell avtale for</p>';
        $output.='<p>Reg nr:</p> '.$regNrOut;
        $output.='<p></p>';
        $output.='<p>Utleverings dato :</p> '.$pickupDate;
        $output.='<p></p>';
        $output.='<p>Innlevering av hjul til din Nye bil :</p>';
        $output.='<p></p>';
        $output.='<p>Reg nr:</p> '.$regNrIn;
        $output.='<p></p>';
        $output.='<p>Innleverings dato :</p> '.$deliveryDate;
        $output.='<p>MVH</p>';
        $output.='<p></p>';
        $output.='<p>Moss Dekk</p>';

        $body = $output;
        $subject = "Bytte av Dekkhotellplass – Moss Dekk";

        $email_to = $email;


        $arr = array();
        $arr['to'] = $email_to;
        $arr['toName'] = $fullName;
        $arr['subject'] = $subject;
        $arr['body'] = $body;
        $mail = mailSend($arr);

        $r = ['success'];
        echo json_encode($r);
        return;
    }
    else if($resArr->result == 'No tyres') {
        $r = ['no tyres'];
        echo json_encode($r);
        return;
    }
    else if($resArr->result == 'outComing failed') {
        $r = ['api failed'];
        echo json_encode($r);
        return;
    }

    $r = ['failed'];
    echo json_encode($r);
    return;
}

function changeOrderDate()
{
    foreach ($_POST as $key => $value) {
        if($key == 'totalTime') { continue; }
        if (empty($value)) {
            $r = ['empty'];
            echo json_encode($r);
            return;
        }
    }

    $con = dbCon();
    $regNrChangeOrder = p($_POST['regNrChangeOrder']);
    $email = p($_POST['emailChangeOrder']);
    $change_order_date = p($_POST['change_order_date']);
    $newTime = p($_POST['newTime']);
    $totalTime = p($_POST['totalTime']);

    $url = 'http://autobutler.no/management/api/functions_new.php';
    $postData = [
        'method' => 'orderDateChange',
        'regNrChangeOrder' => $regNrChangeOrder,
        'change_order_date' => $change_order_date,
        'newTime' => $newTime,
        'totalTime' => $totalTime,
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
        //sending email to customer when is changed order date
        $msg = '<html><head></head><body>
	
		Hei! <br>
		Du har nå endret dato på dekkskift/våre tjenester:<br>		
		Reg nr:<br>'.$regNrChangeOrder.'<br>'.
		'Ny tid og dato :<br>'.$change_order_date.'<br>'.
		'MVH'.'<br>'.
        'Moss Dekk'.'<br>
		
		</body></html>';
        $mail_arr = array();
        $mail_arr['to'] = $email; //'dekkhotell.autobutler@gmail.com';
        $mail_arr['toName'] = "Order Date Chnage";
        $mail_arr['subject'] = "Was Changed Order Date";
        $mail_arr['body'] = $msg;

        $mail = mailSend($mail_arr);


        $r = ['success'];
        echo json_encode($r);
        return;
    }else if($resArr->result == 'no employee') {
        $r = ['no employee'];
        echo json_encode($r);
        return;
    }



}

function updateCustomer() {
	foreach($_POST as $key=>$value) {
        if($key == 'serviceIDs' || $key == 'id' || $key == 'password' || $key = 'email' || $key == 'orgNr' || $key == 'orgNrDekk' || $key == 'referenceDekk' || $key == 'addressLocation' || $key == 'postcodeLocation' || $key == 'cityLocation') { continue; }
		if(empty($value)) {
			$r = ['empty'];
			echo json_encode($r);
			return;
		}
	}
	

    if($_POST['id'] == 'undefined'){
        $r = ['success'];
        echo json_encode($r);
        return;
    }

    $id = intval($_POST['id']);
	$fullName = $_POST['fullName'];
	$regNr = $_POST['regNr'];
	$mobile = $_POST['mobile'];
	$email = $_POST['email'];
	
	$con = dbCon();
	
	$q = mysqli_prepare($con, "UPDATE shop_customers SET fullName=?, regNr=?, mobile=?, email=? WHERE id=?");
	
	mysqli_stmt_bind_param($q, $fullName, $regNr, $mobile, $email, $id);
	
	mysqli_stmt_execute($q);
	
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
		if($key == 'serviceIDs' || $key == 'serviceCounts' || $key = 'email' || $key == 'orgNr' || $key == 'orgNrDekk'  || $key == 'referenceDekk' || $key == 'addressLocation' || $key == 'postcodeLocation' || $key == 'cityLocation') { continue; }
		if(p($value) == ''){
			$r = ['empty fields'];

			//Logg failed
    		$miscFailed = json_encode($r);
    		$customError = 'empty fields';
        	$errorFile = 'query.php';
        	$errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        			
        	$qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL,'$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    		// Logg end
    		echo $miscFailed; 
			return;
		}
	}
	$con = dbCon();
	
	$regNr = p($_POST['regNr']);
	$name = p($_POST['name']);
	$mobile = p($_POST['mobile']);
	$date = p($_POST['date']);
	$type = p($_POST['workType']);
    $tyres = (int)p($_POST['tyres']);
	$serviceIDs = p($_POST['serviceIDs']);
    $serviceCounts = p($_POST['serviceCounts']);
	$time = p($_POST['time']);
	$price = p($_POST['price']);
	$totalTime = (int)p($_POST['totalTime']);
	$workType = 'Tyre Change Dekkhotell';
	$privateCustomerID = (int)p($_POST['pCID']);
	$offerID = (int)p($_POST['offerID']);
	$tyreID = (int)p($_POST['tyreID']);
	$tyreIDs = $tyreID.',';
	$selType = p($_POST['selType']);
	$email = p($_POST['email']);
	$paymentDone = (int)p($_POST['paymentDone']);
	$paymentMode = p($_POST['paymentMode']);
	$orgNr = p($_POST['orgNr']);
    $reference = p($_POST['reference']);
	$password = p($_POST['password']);
    $locationID = p($_POST['locationID']);
    $addressLocation = p($_POST['addressLocation']);
    $postcodeLocation = p($_POST['postcodeLocation']);
    $cityLocation = p($_POST['cityLocation']);
    if ($locationID == 18){$orgNr = '921836686';
        $location = 'Moss Dekk AS';
    }
 
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
        'orgNr' => $orgNr,
        'password' => $password,
        'locationID' => $locationID,
        'addressLocation' => $addressLocation,
        'postcodeLocation' => $postcodeLocation,
        'cityLocation' => $cityLocation
    ];
    
    $response = get_web_page($url, $postData);

    $resArr = json_decode($response);

	//Logg all respons
	if(!is_object($resArr)) {
    $miscSuccess =  'no object response';
	}
	else $miscSuccess = $resArr->result;
    $customError = '0 logg all respons tyreChangeDekkhotellOrderWithoutLoginForTyreShop method';
    $errorFile = 'query.php';
    $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
    
    $qLogg = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`customerInfo`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscSuccess','$name $mobile $workType $email $serviceIDs $cityLocation $type $serviceIDs $paymentMode $paymentDone', NULL)");
    // Logg end

	//register invoice for tyre change Dekkhotell
    $invoiceID = 0;
    if($resArr->result == 'success' && $paymentDone == 1 && $email != '' && $paymentMode != 'payAtShop') {
        //invoice
        $customerID = 0;
        if(isset($_SESSION['customerID'])) {
            $customerID = (int)$_SESSION['customerID'];
        }

        $today = date('Y/m/d H:i');
        $today_date = date('Y/m/d');
        $isPay = 0;
        if ($paymentMode == 'payNow') {
            $mode = 'Kort betaling/delbetaling';
            $isPay = 1;
        } else if ($paymentMode == 'payAtShop') {
            $mode = 'Betaling i butikk';
            $isPay = 1;
        } else {
            $mode = 'Firmakunde';
        }

        //get brand, size and model from tyre offers
        $model = $brand = $size = $count = "";
        $model = $resArr->model;
        $size = $resArr->size;
        $brand = $resArr->brand;
        $count = $resArr->count;
        $offerPrice = $resArr->offerPrice;

        //        $query ="SELECT * FROM shop_invoice WHERE orderedOn like '%$today_date%' AND changeDate like '%$today_date%' AND name='$name' AND regNr='$regNr' AND mobile='$mobile' AND email='$email' AND brand='$brand' AND size='$size' AND tyres='$tyres' AND price='$price' AND tyreID='$tyreID' AND customerID='$customerID' AND paymentMode='$mode' AND workType='$workType' AND locationID='$locationID'";
        $qc = mysqli_query($con, "SELECT * FROM shop_invoice WHERE orderedOn like '%$today_date%' AND changeDate like '%$today_date%' AND name='$name' AND regNr='$regNr' AND mobile='$mobile' AND email='$email' AND tyres='$tyres' AND price='$price' AND tyreID='$tyreID' AND customerID='$customerID' AND paymentMode='$mode' AND workType='$workType' AND locationID='$locationID'");
        if(mysqli_num_rows($qc) == 0) {
            if ($paymentMode != 'payAtShop') {
            $qi = mysqli_query($con, "INSERT INTO `shop_invoice` (`id`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `model`, `offerPrice`, `count`, `tyres`, `price`, `changeDate`, `tyreID`, `customerID`, `paymentMode`, `workType`, `locationID`, `serviceIDs`, `serviceCounts`, `reference`, `isPay`) VALUES (NULL, '$today', '$name', '$regNr', '$mobile', '$email', '$brand', '$size', '$model', '$offerPrice', '$count', '$tyres', '$price', '$today', $tyreID, $customerID, '$mode', '$workType', '$locationID', '$serviceIDs', '$serviceCounts', '$reference', '$isPay')");
            $invoiceID = mysqli_insert_id($con);
            }
            //register shop customer
            $qc = mysqli_query($con, "SELECT * FROM shop_customers WHERE regNr LIKE '%$regNr%'");
            if(mysqli_num_rows($qc) == 0) {
	$q = mysqli_query($con, "INSERT INTO shop_customers (`id`, `createdOn`, `username`, `password`, `fullName`, `email`, `regNr`, `mobile`, `postCode`, `address`, `city`, `location`, `price`, `misc`) 
												VALUES (NULL, '$today', '$regNr', '$password', '$name', '$email', '$regNr', '$mobile', '$postcodeLocation', '$addressLocation', '$cityLocation', '$location', '$price', '')");   
            }
            else{
                $qc = mysqli_query($con, "UPDATE shop_customers SET username='$name', email='$email', mobile='$mobile', username='$name', fullName='$name' WHERE regNr LIKE '%$regNr%'");
            }
        }
        else{
            $fc = mysqli_fetch_array_n($qc, MYSQLI_ASSOC);
            $invoiceID = $fc['id'];
        }

    }

	if(!is_object($resArr)) {
		$r = ['api error'];

		$miscFailed = json_encode($r);
    	$customError = 'Api error, no object';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        			
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}

	if($resArr->result == 'customer not found') {
		$r = ['customer not found'];
		$miscFailed = json_encode($r);
    	$customError = 'customer not found';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        			
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}else if($resArr->result == 'failed' || $resArr->result == 'api error') {
		$r = ['failed'];
		$miscFailed = json_encode($r);
    	$customError = 'failed';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        			
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}else if($resArr->result == 'failed no object') {
		$r = ['failed no object'];
		$miscFailed = json_encode($r);
    	$customError = 'failed no object';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}else if($resArr->result == 'already ordered') {
		$r = ['already ordered'];
		$miscFailed = json_encode($r);
    	$customError = 'already orderedt';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}else if($resArr->result == 'no work') {
		$r = ['no work'];
		$miscFailed = json_encode($r);
    	$customError = 'no work';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}else if($resArr->result == 'no employee') {
		$r = ['no employee'];
		$miscFailed = json_encode($r);
    	$customError = 'no employee';
        $errorFile = 'query.php';
        $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    	// Logg end
    	echo $miscFailed; 
		return;
	}else if($resArr->result == 'success' ) {

                if(is_object($resArr->arr) && $paymentMode != 'payAtShop'){
                    $arr = $resArr->arr;
                    
                    $mail_arr = array();
                    $mail_arr['to'] = $arr->to; //'dekkhotell.autobutler@gmail.com';
                    $mail_arr['toName'] = $arr->toName;
                    $mail_arr['subject'] = $arr->subject;
                    $mail_arr['body'] = $arr->body;
                    $file = "pdf";
                    $pdf = array();
                    $res =json_decode(pdfDownload($invoiceID));
                    if($res->result == 'success') {
                        $pdf['source'] = $res->path;
                    }
                    $pdf['name'] = "invoice.pdf";
                    $mail = mailSend($mail_arr, $file, $pdf);
                    
                    $smsMessage = 'Bestillingen din er nå mottatt og registrert. '.$regNr.' er bestillt '.$date.' '.$time.' ';
                    sendSms($mobile,$smsMessage);
                }
                //Send email if admin selected
                if(is_object($resArr->arr) && $paymentMode == 'payAtShop'){
                    $arr = $resArr->arr;
                    
                    //$arr = $resArr->arr;
                    $mail_arr = array();
                    $mail_arr['to'] = $arr->to; //'dekkhotell.autobutler@gmail.com';
                    $mail_arr['toName'] = $arr->toName;
                    $mail_arr['subject'] = $arr->subject;
                    $mail_arr['body'] = $arr->body;
                    $mail = mailSend($mail_arr);
                    
                    $smsMessage = 'Bestillingen din er nå mottatt og registrert. '.$regNr.' er bestillt '.$date.' '.$time.' ';
                    sendSms($mobile,$smsMessage);
                }

	        if($paymentDone == 1) {
	          /* $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
                $postData = [
                        'method' => 'confirmOrder',
                        'deliveryDate' => $date,
                        'deliveryTime' => $time,
                        'tyreIDs' => $tyreIDs
                ];
                    
                $response = get_web_page($url, $postData);
               */     
				$r = ['success'];
				echo json_encode($r);
				return;
			}
			else {
				$orderID = random_string(10);
				$postData['orderID'] = $orderID;
				$_SESSION['pD'] = $postData;


				$acceptURL = 'https://www.mossdekk.no/?p=processOrder&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type=tyreChangeDekkhotell&serviceCounts='.$serviceCounts;
				$cancelURL = 'https://www.mossdekk.no/?p=processOrder&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type=tyreChangeDekkhotell&serviceCounts='.$serviceCounts; 
				$callbackURL = 'https://dev.mossdekk.no/?p=processOrderCallback&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type=tyreChangeDekkhotell&serviceCounts='.$serviceCounts;

				$arr = [
				    'regNr' => $regNr,
				    'dateTime' => $date . ' ' . $time,
					'orderID' => $orderID,
					'amount' => (int)$price*100,
					'acceptURL' => $acceptURL,
					'cancelURL' => $cancelURL,
					'callbackURL' => $callbackURL
				];
				$response = makePayment($arr);
				if(!isset($response->error)) {
					$token = $response->id;
					$url = $response->url;
    				$r = ['paySessionSuccess', $token, $url];
			
					//Logg success
    				$miscSuccess = json_encode($r);
    				$customError = '1 Paymentsession Success after tyreChangeDekkhotellOrderWithoutLoginForTyreShop method';
        			$errorFile = 'query.php';
        			$errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        			
        		    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`customerInfo`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscSuccess','$name $mobile $workType $email $serviceIDs $cityLocation $type $serviceIDs $paymentMode $paymentDone', NULL)");
    				// Logg end
    				echo $miscSuccess; //$r

					return;
				}else {
					// api error
					$customError = '1 Paymentsession API error after tyreChangeDekkhotellOrderWithoutLoginForTyreShop method';
    			    $errorFile = 'query.php';
    			    $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
    			    $miscError = json_encode($response->error);
    			    
    			    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError', '$miscError', NULL)");
					$r = ['api error'];
					echo $miscError;
					return;
				}
			}
	}
	$r = ['failed'];
    $miscFailed = json_encode($r);
    $customError = 'Whole function fails';
    $errorFile = 'query.php';
    $errorFunction = 'tyreChangeDekkhotellOrderWithoutLogin';
        			
    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscFailed', NULL)");
    // Logg end
    echo $miscFailed; 

	return;

}

function tyreChangeCheckForTyreOffers() {
	foreach($_POST as $key=>$value) {
		if($key == 'serviceIDs' || $key == 'serviceCounts' || $key == 'orgNr' || $key == 'orgNrDekk' || $key == 'referenceDekk') { continue; }
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
	
	$url = 'http://autobutler.no/dekkhotell/accessTyreHotell_new.php';
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
	$regNr = p($_POST['regNr']);
	$location = p($_POST['location']);
	$date = date('Y/m/d H:i');

	if($name == '' || $email == '' || $phone == '' || $sub == '' || $msg == '' || $regNr == '') {
		echo 'empty fields'; return;
	}

	$q = mysqli_query($con, "INSERT INTO shop_contact (`id`, `date`, `name`, `email`, `phone`, `subject`, `message`, `misc`, `reg_nr`, `location`) VALUES (NULL, '$date', '$name', '$email', '$phone', '$sub', '$msg', '', '$regNr', '$location')");
	if($q) {
		// send email notification
    	if($email != '') {
    			$workType = $resArr->workType;
    			$services = $resArr->services;

    		$today = date('Y/m/d H:i');
    			$body = "<html><head></head><body>";

    			$body .= 'Hey '.$name.', <br>';
    			$body .= 'I have some queries about this platform.';
    			$body .= '<br><br>';

    			$body .= 'Details of Query are:<br>';
    			$body .= 'Reg Nr: <b>'.$regNr.'</b> <br>';
    			$body .= 'Additional Services: <b>'.$services.'</b><br>';
    			$body .= 'Date & Time of tyre change: <b>'.$date.' '.$time.' </b> <br>';
    			$body .= 'Query date: <b>'.$today.'</b><br>';
    			$body .= 'Query: <b><p>'.$msg.'</p></b><br>';
    			$body .= '</body></html>';

    			$arr = array();
    			$arr['to'] = 'dekkhotell.autobutler@gmail.com';
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

function tyreOrderWithoutLogin() {

	$con = dbCon();

	$regNr = p($_POST['regNr']);
	$name = p($_POST['name']);
	$mobile = p($_POST['mobile']);
	$date = p($_POST['date']);
	$type = p($_POST['workType']);
	$serviceIDs = p($_POST['serviceIDs']);
    $serviceCounts = p($_POST['serviceCounts']);
	$time = p($_POST['time']);
	$price = p($_POST['price']);
	$totalTime = (int)p($_POST['totalTime']);
	$tyres = (int)p($_POST['tyres']);
	$apiTyre = (int)p($_POST['apiTyre']);
	$paymentDone = (int) p($_POST['paymentDone']);
	$email = p($_POST['email']);
	$tyreID = (int) p($_POST['tyreID']);
	$paymentMode = p($_POST['paymentMode']);
	$orgNr = p($_POST['orgNr']);
    $reference = p($_POST['reference']);
    $locationID = p($_POST['locationID']);
	$addressLocation = p($_POST['addressLocation']);
	$postcodeLocation = p($_POST['postcodeLocation']);
	$cityLocation = p($_POST['cityLocation']);
    $location = 'Moss Dekk AS';
	$customerID = 0;
	if(isset($_SESSION['customerID'])) {
		$customerID = (int)$_SESSION['customerID'];
	}

	if($paymentMode == 'orgNr' || $paymentMode == 'payAtShop') {
		$paymentDone = 1;
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

	// dekkhotell orders
	if(isset($_POST['dekk'])) {
		$dekk = (int)p($_POST['dekk']);
		if($dekk == 1) {
			/* OLD?
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
			*/
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
				'serviceCounts' => $serviceCounts,
				'time' => $time,
				'tyres' => $tyres,
				'paymentMode' => $paymentMode,
				'orgNr' => $orgNr,
				'locationID' => $locationID,
				'addressLocation' => $addressLocation,
				'postcodeLocation' => $postcodeLocation,
				'cityLocation' => $cityLocation,
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
			
            if($resArr->result == 'success' && $paymentDone == 1 && $paymentMode == 'payAtShop') {
                if($email != '') {//Tyre change order

                    $today = date('Y/m/d H:i');
                	$msg = "<html><head></head><body>";

					$msg .= 'Hey '.$name.', <br>';
					$msg .= 'You have successfully made a '.$workType.' order.';
					$msg .= '<br><br>';

					$msg .= 'Details of order are:<br>';
					$msg .= 'Customer Name: <b> '.$name.' </b><br>';
					$msg .= 'Mobile: <b> '.$mobile.'</b><br>';
					$msg .= 'Reg Nr: <b>'.$regNr.'</b> <br>';
					$msg .= 'Date & Time of tyre change: <b>'.$date.' '.$time.' </b> <br>';
					$msg .= 'Ordered On: <b>'.$today.'</b><br>';
					$msg .= 'Price: <b>Kr '.$price.'</b><br>';
					$msg .= '</body></html>';
					
    				$arr = array();
    				$arr['to'] = $email; //'dekkhotell.autobutler@gmail.com';
    				$arr['toName'] = $name;
    				$arr['subject'] = 'Successfully placed your order';
    				$arr['body'] = $msg;
                    $mail = mailSend($arr);
                    $smsMessage = 'Bestillingen din er nå mottatt og registrert. '.$regNr.' er bestillt '.$date.' '.$time.' ';
                    sendSms($mobile,$smsMessage);        				
                }	
				$r = ['success'];
				echo json_encode($r);
				return;
			}

                
                
			if($resArr->result == 'success' && $paymentDone == 1 && $paymentMode != 'payAtShop') {
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
						$misc['reference'] = $reference;
					}
					if(isset($resArr->workOrderID)) {
						$misc['managementWorkOrderID'] = $resArr->workOrderID;
					}
					$misc['orderType'] = 'dekk';
					$misc['workType'] = $type;
					$misc['serviceIDs'] = $serviceIDs;
					//$misc = json_encode($misc);

					//$orderID = $_SESSION['orderID'];

					$q = mysqli_query($con, "INSERT INTO `shop_orders` (`id`, `orderID`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `tyres`, `price`, `changeDate`, `status`, `misc`, `tyreID`, `customerID`, `location`) VALUES (NULL, '', '$orderedOn', '$name', '$regNr', '$mobile', '$email', '', '', '$tyres', '$price', '$changeDate', '$status', '$misc', $tyreID, $customerID,'$location')");
                    

				}

				if($email != '') {//Tyre change order
                    //invoice
                    $today = date('Y/m/d H:i');
                    $isPay = 0;
                    if($paymentMode == 'payNow') {
                        $mode = 'Kort betaling/delbetaling';
                        $isPay = 1;
                    }else if($paymentMode == 'payAtShop') {
                        $mode = 'Betaling i butikk';
                        $isPay = 1;
                    }else {
                        $mode = 'Firmakunde';
                    }
                    //get brand and model and size from shop_tyres
                    $brand = $model = $size = '';
                    //Diffrent tabel if api tyres
                    if($apiTyre == 1){
                        $qshop = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE id=$tyreID");
                    }
                    else {
                        $qshop = mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID");
                    }
                    
                    if(mysqli_num_rows($qshop) != 0) {
                        $fshop = mysqli_fetch_array_n($qshop, MYSQLI_ASSOC);
                        $brand = $fshop['brand'];
                        $model = $fshop['model'];
                        if($apiTyre == 1){
                        $size = $fshop['width'] . '/' . $fshop['profile'] . '-' . $fshop['inches'];
                        $misc['varenr'] = $fshop['varenr'];
                        }else $size = $fshop['size'];
                    }
                    
                    $misc = json_encode($misc);
                    if ($paymentMode != 'payAtShop') {
                    $qi = mysqli_query($con, "INSERT INTO `shop_invoice` (`id`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `model`, `tyres`, `price`, `changeDate`, `tyreID`, `customerID`, `paymentMode`, `workType`, `locationID`, `serviceIDs`, `serviceCounts`, `reference`, `isPay`) VALUES (NULL, '$today', '$name', '$regNr', '$mobile', '$email', '$brand', '$size', '$model', '$tyres', '$price', '$today', $tyreID, $customerID, '$mode', '$workType', '$locationID', '$serviceIDs', '$serviceCounts', '$reference', '$isPay')");
                    $invoice_id = mysqli_insert_id($con);
                    }
					$workType = $resArr->workType;
					$services = $resArr->services;

					$msg = "<html><head></head><body>";

					$msg .= 'Hey '.$name.', <br>';
					$msg .= 'You have successfully made a '.$workType.' order.';
					$msg .= '<br><br>';

					$msg .= 'Details of order are:<br>';
					$msg .= 'Customer Name: <b> '.$name.' </b><br>';
					$msg .= 'Mobile: <b> '.$mobile.'</b><br>';
					$msg .= 'Reg Nr: <b>'.$regNr.'</b> <br>';
					$msg .= 'Additional Services: <b>'.$services.'</b><br>';
					$msg .= 'Items: <b>'.$brand.'</b> '.$model.' - '.$size.' <br>';
					$msg .= 'Date & Time of tyre change: <b>'.$date.' '.$time.' </b> <br>';
					$msg .= 'Ordered On: <b>'.$today.'</b><br>';
					$msg .= 'Price: <b>Kr '.$price.'</b><br>';
					$msg .= '</body></html>';
                    
					$arr = array();
                    //$email = "natalianickngp@outlook.com";
					$arr['to'] = $email; //'dekkhotell.autobutler@gmail.com';
					$arr['toName'] = $name;
					$arr['subject'] = 'Successfully placed your order';
					$arr['body'] = $msg;
                    $file = "pdf";
                    $pdf = array();
                    $smsMessage = 'Bestillingen din er nå mottatt og registrert. '.$regNr.' er bestillt '.$date.' '.$time.' ';
                    sendSms($mobile,$smsMessage);

                    $resArr =json_decode(pdfDownload($invoice_id));
                    if($resArr->result == 'success') {
                        $pdf['source'] = $resArr->path;
                    }
                    
                    $pdf['name'] = "invoice.pdf";
					if($paymentMode != 'payAtShop'){
                        $mail = mailSend($arr, $file, $pdf);
					}
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
				$acceptURL = 'https://www.mossdekk.no/?p=processOrder&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&apiTyre='.$apiTyre.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type='.$type.'&serviceCounts='.$serviceCounts;
				$cancelURL = 'https://www.mossdekk.no/?p=processOrder&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&apiTyre='.$apiTyre.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type='.$type.'&serviceCounts='.$serviceCounts; //ps=0 is not payed
				$callbackURL = 'https://dev.mossdekk.no/?p=processOrderCallback&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&apiTyre='.$apiTyre.'&regNr='.$regNr.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type='.$type.'&serviceCounts='.$serviceCounts;

				$arr = [
				    'regNr' => $regNr,
				    'dateTime' => $date . ' ' . $time,
					'orderID' => $orderID,
					'amount' => (int)$price*100,
					'acceptURL' => $acceptURL,
					'cancelURL' => $cancelURL,
					'callbackURL' => $callbackURL
				];
				$response = makePayment($arr);
				if(!isset($response->error)) {
					$token = $response->id;
					$url = $response->url;
					$r = ['paySessionSuccess', $token, $url];

					//Logg success
    				$miscSuccess = json_encode($r);
					$customError = '2 Payment Success after after tyreOrderWithoutLogin method';
    			    $errorFile = 'query.php';
    			    $errorFunction = 'tyreOrderWithoutLogin';
        			
        		    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscSuccess', NULL)");
    				// Logg end
    				echo $miscSuccess; //$r

					return;
				}else {
    				$customError = '2 Payment API error after tyreOrderWithoutLogin method';
    			    $errorFile = 'query.php';
    			    $errorFunction = 'tyreOrderWithoutLogin';
    			    $miscError = json_encode($response->error);
    			    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError,'$miscError', NULL)");
    				$r = ['api error'];
					echo $miscError;
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
	//Diffrent tabel if api tyres
    if($apiTyre == 1){
        $q = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE id=$tyreID");
    }
    else {
        $q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID");
    }
	if(mysqli_num_rows($q) == 0) {
		$r = ['no tyre'];
		echo json_encode($r);
		return;
	}else {
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		$brand = $f['brand'];
		$model = $f['model'];
		if($apiTyre == 1){
		$size = $f['width'] . '/' . $f['profile'] . '-' . $f['inches'];
		} else $size = $f['size'];
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
	
	/* OLD?
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
	*/

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
		//'pay' => json_encode($pay),
		'paymentMode' => $paymentMode,
		'orgNr' => $orgNr,
        'locationID' => $locationID,
        'addressLocation' => $addressLocation,
        'postcodeLocation' => $postcodeLocation,
        'cityLocation' => $cityLocation
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
			$isPay = 0;
			if($paymentMode == 'payNow') {
				$mode = 'Kort betaling/delbetaling';
                $isPay = 1;
			}else if($paymentMode == 'payAtShop') {
				$mode = 'Betaling i butikk';
                $isPay = 1;
			}else {
				$mode = 'Firmakunde';
			}
			if(isset($resArr->workOrderID)) {
				$misc['managementWorkOrderID'] = $resArr->workOrderID;
			}
			$misc['paymentMode'] = $mode;
			if($paymentMode == 'orgNr') {
				$misc['orgNr'] = $orgNr;
				$misc['reference'] = $reference;
			}
			$misc['model'] = $model;
			$misc['season'] = $season;
			$misc['runFlat'] = $runFlat;
			$misc['load'] = $load;
			$misc['category'] = $category;
			

			$orderID = $_SESSION['orderID'];

            //get brand and model and size from shop_tyres
            $brand = $model = $size = '';
            
            //Diffrent tabel if api tyres
            if($apiTyre == 1){
                $qshop = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE id=$tyreID");
            }
            else {
                $qshop = mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID");
                $f = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
    			$oldStock = $f['stock'];
    			$newStock = $oldStock - $tyres;
			
			    $q = mysqli_query($con, "UPDATE shop_stock SET stock = $newStock WHERE tyreID=$tyreID");
            }

            if(mysqli_num_rows($qshop) != 0) {
                $fshop = mysqli_fetch_array_n($qshop, MYSQLI_ASSOC);
                $brand = $fshop['brand'];
                $model = $fshop['model'];
                if($apiTyre == 1){
                $size = $fshop['width'] . '/' . $fshop['profile'] . '-' . $fshop['inches'];
                $misc['varenr'] = $fshop['varenr'];
                }else $size = $fshop['size'];
            }
            $misc = json_encode($misc);
			$q = mysqli_query($con, "INSERT INTO `shop_orders` (`id`, `orderID`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `tyres`, `price`, `changeDate`, `status`, `misc`, `tyreID`, `customerID`, `location`) VALUES (NULL, '$orderID', '$orderedOn', '$name', '$regNr', '$mobile', '$email', '$brand', '$size', '$tyres', '$price', '$changeDate', '$status', '$misc', $tyreID, $customerID,'$location')");
			//invoice
            $today = date('Y/m/d H:i');
            if ($paymentMode != 'payAtShop') {
			$qi = mysqli_query($con, "INSERT INTO `shop_invoice` (`id`, `orderedOn`, `name`, `regNr`, `mobile`, `email`, `brand`, `size`, `model`, `tyres`, `price`, `changeDate`, `tyreID`, `customerID`, `paymentMode`, `workType`, `locationID`, `serviceIDs`, `serviceCounts`, `reference`, `isPay`) VALUES (NULL, '$today', '$name', '$regNr', '$mobile', '$email', '$brand', '$size', '$model', '$tyres', '$price', '$today', $tyreID, $customerID, '$mode', '$workType', '$locationID', '$serviceIDs', '$serviceCounts', '$reference', '$isPay')");
			$invoice_id = mysqli_insert_id($con);
            }

			if($email != '') {
					$msg = "<html><head></head><body>";
                    $today = date('Y/m/d H:i');
                    $services = $resArr->services;
                    $msg .= 'Hey '.$name.', <br>';
					$msg .= 'You have successfully made a '.$workType.' order.';
					$msg .= '<br><br>';
					
					$msg .= 'Details of order are:<br>';
					$msg .= 'Customer Name: <b> '.$name.' </b><br>';
					$msg .= 'Mobile: <b> '.$mobile.'</b><br>';
					$msg .= 'Reg Nr: <b>'.$regNr.'</b> <br>';
					$msg .= 'Items: <b>'.$brand.'</b> '.$model.' - '.$size.' <br>';
					$msg .= 'Date & Time of tyre change: <b>'.$date.' '.$time.' </b> <br>';
					$msg .= 'Ordered On: <b>'.$today.'</b><br>';
					$msg .= 'Price: <b>Kr '.$price.'</b><br>';
					$msg .= '</body></html>';
					$arr = array();
					$arr['to'] = $email; 
					$arr['toName'] = $name;
					$arr['subject'] = 'Successfully placed your order';
					$arr['body'] = $msg;
                    $smsMessage = 'Bestillingen din er nå mottatt og registrert. '.$regNr.' er bestillt '.$date.' '.$time.' ';
                    sendSms($mobile,$smsMessage);					
					
					if($paymentMode != 'payAtShop'){
    					$file = "pdf";
                        $pdf = array();
                        $resArr =json_decode(pdfDownload($invoice_id));

                        if($resArr->result == 'success') {
                            $pdf['source'] = $resArr->path;
                        }
                        $pdf['name'] = "invoice.pdf";
					    $mail = mailSend($arr, $file, $pdf);
					}
                    else {
                    $mail = mailSend($arr);
                    }
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
			
				$acceptURL = 'https://www.mossdekk.no/?p=processOrder&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&apiTyre='.$apiTyre.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type='.$type.'&serviceCounts='.$serviceCounts;
				$cancelURL = 'https://www.mossdekk.no/?p=processOrder&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type='.$type.'&serviceCounts='.$serviceCounts; 
				$callbackURL = 'https://dev.mossdekk.no/?p=processOrderCallback&email='.$email.'&totalTime=0&workType='.$workType.'&price='.$price.'&regNr='.$regNr.'&apiTyre='.$apiTyre.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time2='.$time.'&tyres='.$tyres.'&locationID='.$locationID.'&postcodeLocation='.$postcodeLocation.'&addressLocation='.$addressLocation.'&cityLocation='.$cityLocation.'&orgNr='.$orgNr.'&reference='.$reference.'&paymentMode='.$paymentMode.'&selType='.$selType.'&tyreID='.$tyreID.'&tyreIDs='.$tyreIDs.'&privateCustomerID='.$privateCustomerID.'&offerID='.$offerID.'&type='.$type.'&serviceCounts='.$serviceCounts;
			

			$arr = [
				'orderID' => $orderID,
				'regNr' => $regNr,
				'dateTime' => $date . ' ' . $time,
				'amount' => (int)$price*100,
				'acceptURL' => $acceptURL,
				'cancelURL' => $cancelURL,
				'callbackURL' => $callbackURL
			];
			$response = makePayment($arr);
			if(!isset($response->error)) {
				$token = $response->id;
				$url = $response->url;
				$r = ['paySessionSuccess', $token, $url];
				//Logg success
				$miscSuccess = json_encode($r);
				$customError = '3 Payment Success after tyreShopTyreOrder method';
    			$errorFile = 'query.php';
    			$errorFunction = 'tyreOrderWithoutLogin';
    			
        		$qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError','$miscSuccess', NULL)");
    			// Logg end
    			echo $miscSuccess; //$r
				return;
			}else {
			    $customError = '3 Payment API error after tyreShopTyreOrder method';
			    $errorFile = 'query.php';
			    $errorFunction = 'tyreOrderWithoutLogin';
			    $miscError = json_encode($response->error);
			    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`workDate`,`paymentMode`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL, '$regNr','$date $time','$paymentMode', '$errorFunction','$errorFile','$customError', '$miscError', NULL)");
				$r = ['api error'];
				echo $miscError; 
				return;
			}
			// PAYMENT END #########################################################################
		}
	}

	$r = ['failed'];
	echo json_encode($r);
	return;
	
}

function pdfDownload($id){
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
        $isPay = $fo['isPay'];
        $qc = mysqli_query($con, "SELECT * FROM shop_customers WHERE id='$customerID' LIMIT 1");
        if(mysqli_num_rows($qc) > 0) {
            $fc = mysqli_fetch_array_n($qc, MYSQLI_ASSOC);
            $address = $fc['address'];
            $postCode = $fc['postCode'];
            $city = $fc['city'];
        }
        $qb = mysqli_query($con, "SELECT * FROM shop_bank LIMIT 1");
        if(mysqli_num_rows($qb) > 0 && $isPay == 0) {
            $fb = mysqli_fetch_array_n($qb, MYSQLI_ASSOC);
            $due_period = $fb['due_period'] . ' Days';
            $account_in = $fb['account_in'];
        } else {
            $due_period = 'BETALT';
            $account_in = '';
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
    $pdfcontent .= 'Var kontact:<br>';
    $pdfcontent .= 'Leveransedato:<br>';
    $pdfcontent .= 'Leveransested:<br>';
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td>';
    $pdfcontent .= 'Bogdan Mincu<br>';
    $pdfcontent .= $invoice_date.'<br>';
    $pdfcontent .= $locationName;
    $pdfcontent .= '</td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td></td>';
    $pdfcontent .= '<td>Due date:<br>';
    $pdfcontent .= $due_period.'</td>';
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
    $mpdf->Output('./uploads/pdf/invoice.pdf');
    $r = ['result'=>'success', 'path'=>'./uploads/pdf/invoice.pdf'];
    return json_encode($r);
}

function getTimeSlots() {
	$con = dbCon();
	//$orderType = p($_POST['orderType']);
	$orderType = 'Tyre Change';
	$date = p($_POST['date']);
	$day = p($_POST['day']);
	$serviceIDs = p($_POST['serviceIDs']);
	$locationID = p($_POST['locationID']);
	$timeSlots = array();
	$currentTime = date('Hi');
	$currentDate = date('Y/m/d');
	
	$dekkDone = 0;
	$totalTime = 0;
	//No timeslots same date
	if($currentDate == $date){
	   	$r = ['no employee'];
		echo json_encode($r);
	    return; 
	}
	
	if(isset($_POST['type'])) {
		$type = p($_POST['type']);
		if($type == 'dekk') {
			
			$workType = p($_POST['workType']);
			if($workType == 'tyreChange') { $work = 'Tyre Change'; }
			elseif($workType == 'tyreBalancing') { $work = 'Tyre Balancing'; }
			else if($workType == 'tyreRepair') { $work = 'Tyre Repair'; }
			else if($workType == 'tyreChangeDekkhotell') { $work = 'Tyre Change Dekkhotell'; }
	
			$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
			$postData = ['method'=>'getTimeSlotsForTyreShop', 'workType'=>$work, 'locationID'=>$locationID, 'serviceIDs'=>$serviceIDs, 'day'=>$day, 'date'=>$date];

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
		
		$q = mysqli_query($con, "SELECT * FROM shop_misc WHERE property='timesForNormalTyreChangeOrder' AND attribute1='$day' AND locationID='$locationID'");
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
		'locationID' => 	$locationID,
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
				$r = ['success', $resArr->data, $resArr->totalTime];
				echo json_encode($r);
				return;
			}
		}
	}
	$r = ['failed'];
	echo json_encode($r);
	return;
		
}

function fetchTimeOrderSlots(){
    $con = dbCon();
    $orderType = 'Tyre Change';
    $sendDate = p($_POST['sendDate']);
    $day = p($_POST['day']);
    $regnr = p($_POST['regnr']);
    $email = p($_POST['email']);

    $url = 'http://autobutler.no/management/api/functions.php';
    $postData = [
        'method'=>'fetchTimeOrderSlots',
        'workType'=>$orderType,
        'date' => $sendDate,
        'day' => $day,
        'regnr' => $regnr,
        'email' => $email
    ];
    $response = get_web_page($url, $postData);
    $resArr = array();
    $resArr = json_decode($response);

    if(is_object($resArr)) {
        if($resArr->result == 'success') {
                $workType = $resArr->workType;
                $orderID = $resArr->orderID;
                $serviceIDs = $resArr->serviceIDs;
                $totalTime = $resArr->totalTime;
        }
    }

    $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
    $postData = [
        'method' => 'getTimeSlotsForDateChange',
        'workType' => $workType,
        'managementOrderID' => $orderID,
        'newDate' => $sendDate,
        'serviceIDs' => $serviceIDs,
        'day' => $day
    ];
    $response = get_web_page($url, $postData);
    $resArr = json_decode($response);
    if(!is_object($resArr)) {
        $r = ['api error'];
        echo json_encode($r);
        return;
    }

    if($resArr->result == 'closed') {
        $r = ['closed'];
        echo json_encode($r);
        return;
    }
    if($resArr->result == 'success') {
        if($totalTime != 0) {
            $totalTime = (int)$resArr->totalTime;
        }

        $date = $sendDate;
        $timeSlots = $resArr->timeSlots;
        if($workType == 'New Tyre') {

        }else if($workType == 'Tyre Change Dekkhotell') {
            $workType = $workType;
        }else if($workType == 'Punktering') {
            $workType = 'Dekkhotell Tyre Repair';
        }else {
            $workType = 'Dekkhotell '.$workType;
        }



        $url = 'http://autobutler.no/management/api/functions.php';
        $postData = [
            'method'=>'fetchTimeOrderSlots',
            'workType'=>$workType,
            'date' => $sendDate,
            'timeSlots' => json_encode($timeSlots),
            'serviceIDs' => $serviceIDs,
            'totalTime' => $totalTime
        ];
        $response = get_web_page($url, $postData);
        $resArr = array();
        $resArr = json_decode($response);
        if($resArr->result == 'success') {
            $r = ['success', $resArr->data, $resArr->totalTime];
            echo json_encode($r);
            return;
        }
        $r = ['failed'];
        echo json_encode($r);
        return;
    }
}

function getServices() {
	$con = dbCon();

	if(isset($_POST['type'])) {
		$type = p($_POST['type']);
        $locationID = isset($_POST['locationID']) ? p($_POST['locationID']) : 0;
		if(($type == 'dekk') || ($type == 'location')) {

			$workType = p($_POST['workType']);
			$url = 'http://autobutler.no/dekkhotell/accessTyreHotell_new.php';
			$postData = ['method'=>'getServicesForTyreShop', 'type'=>$type, 'workType'=>$workType, 'locationID'=>$locationID];

			$response = get_web_page($url, $postData);
			$resArr = array();
			$resArr = json_decode($response);
			if(!is_object($resArr)) {
				$r = ['failed'];
				echo json_encode($r);
				return;
			}

			//use api for location
            $url = 'http://autobutler.no/management/api/functions.php';
            $postData = ['method'=>'fetchServicesLocationShop', 'type'=>$type, 'workType'=>$workType,'locationName'=>'Moss Dekk AS'];

            $response = get_web_page($url, $postData);
            $resHtml = json_decode($response);
            if(!is_object($resHtml)) {
                $r = ['api error'];
                echo json_encode($r);
                return;
            }

			$r = ['success', $resArr->services, $resArr->price, $resHtml->html, $resArr->activated, $resArr->tyreInfo];
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

function getMossDekkPrice() {
	$con = dbCon();

	if(isset($_POST['regNrDekk'])) {
		$q = $_POST['regNrDekk'];
        if(preg_match('/^[a-zA-Z]{2}[0-9]{5}$/', $q)){
			$query= "SELECT price FROM shop_customers WHERE regNr like '".$q."'";
    		$result=mysqli_query($con,$query);
    		if($result){
			    foreach($result as $row){
			       $mossDekkPrice = $row['price'];
			    } 
			    $r = ['success',$mossDekkPrice];
			    echo json_encode($r);
						return;
			    }
		}else {
		    	$r = ['failed'];
				echo json_encode($r);
				return;
		}
	}
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
function errorHandling() {

    // Create dababase connection
    $con = dbCon();
    $regnr = $_POST['regNr'];
    $event = $_POST['event'];
    //Logg failed
    $customError = 'paymentfunction triggered';
    $errorFile = 'warehouse.php';
    $errorFunction = 'errorHandling';
       
    $qError = mysqli_query($con, "INSERT INTO `shop_errors` ( `id`,`regnr`,`function`,`file`,`customError`,`misc`,`created`) VALUES (NULL,'$regnr','$errorFunction','$errorFile','$customError','$event', NULL)");
    // Logg end

    return;
}
function checkReservedTime() {

	// Create dababase connection
	$con = dbCon();
	
	$date_reserved = $_POST['date'];
	$regnr = $_POST['regNr'];

	// remove all row where "updated" are older then 5min and same regnr
	$delete_query = "DELETE FROM shop_timeslot_reserved WHERE updated < DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
	mysqli_query($con, $delete_query);
	
	// Check if there's another record 
	$check_query = "SELECT timeReserved FROM shop_timeslot_reserved WHERE dateReserved = '$date_reserved' AND regnr <> '$regnr'";
	$result=mysqli_query($con,$check_query);
    if (mysqli_num_rows($result) > 0){
        foreach($result as $row){
            $timeReserved = $row['timeReserved'];
            $formatTimeReserved = str_replace(':', '', $timeReserved);
            $formatTimeReserved = substr($formatTimeReserved, 0, 4);
            //$class = ".serviceBar.inactiveService.dateTimeDekk.dateTimeDekk" . $formatTimeReserved;
            $allTimeReserved.= $formatTimeReserved.",";
        }
    if(substr($allTimeReserved, -1) === ',') { // ckeck if the last char is , and remove it
        $allTimeReserved = substr($allTimeReserved, 0, -1);
    }   
	$r = ['reserved', $allTimeReserved];
	echo json_encode($r);
    return;    
    }
    else {
    $r = ['failed', 'no reserved'];
	echo json_encode($r);    
    return;
    }
}

function reservTime() {

	// Create dababase connection
	$con = dbCon();
	
	$time_id = $_POST['time']; 
	$date_reserved = $_POST['date'];
	$regnr = $_POST['regNr'];

	// remove all row where "updated" are older then 5min and same regnr
	$delete_query = "DELETE FROM shop_timeslot_reserved WHERE updated < DATE_SUB(NOW(), INTERVAL 5 MINUTE) OR regnr = '$regnr'";
	mysqli_query($con, $delete_query);
	
	// Check if there's another record 
	$check_query = "SELECT * FROM shop_timeslot_reserved WHERE timeReserved = '$time_id' AND dateReserved = '$date_reserved' AND regnr <> '$regnr'";
	$result = mysqli_query($con, $check_query);

	if (mysqli_num_rows($result) == 0) {
	    // No records, insert ny row and reserv it
	    $insert_query = "INSERT INTO shop_timeslot_reserved (regnr, timeReserved, dateReserved, updated) VALUES ('$regnr', '$time_id', '$date_reserved', NOW())";
	    mysqli_query($con, $insert_query);
	    echo json_encode("success");
	    return;
	} else {
	    // Time reserved
	    echo json_encode("reserved");
	    return;
	}
}



function fetchFrontTyres() {

	$season = p($_POST['season']);
	$width = $sizeOne = p($_POST['sizeOne']);
	$profile = $sizeTwo = p($_POST['sizeTwo']);
	$inches = $sizeThree = p($_POST['sizeThree']);
	
	$size = $sizeOne.'/'.$sizeTwo.'-'.$sizeThree;

	$con = dbCon();

	$q = mysqli_query($con, "SELECT `id` FROM shop_tyres");
	if(mysqli_num_rows($q) > 0) {

		$budgetTyre = '<h2 style="text-align:center; color:#529503;">Budsjett</h2>'; // heading
		$mellomTyre = '<h2 style="text-align:center; color:#529503;">Kvalitet</h2>';
		$premiumTyre = '<h2 style="text-align:center; color:#529503;">Premium</h2>';
		$budgetNum = $mellomNum = $premiumNum = 0;

		//budget tyre
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE `season`='$season' AND `size` LIKE '%$size%' AND `category`='budget' AND location = 'Moss Dekk AS'");
		if(mysqli_num_rows($q) > 0) {

			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {

				$tyreID = $f['id'];
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
				$stock = $fs['stock'];
				$delay = $fs['delay'];
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="" >Sold Out</div>';
				}

				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = '';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}


				$budgetTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:0px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url(\'uploads/tyreImg/'.$f['image'].'\') no-repeat center; background-size:contain; background-color:#fff;">
											
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$f['brand'].'  <br> '.$f['model'].' </div>
											<div class="tyreSize" style="font-size:15px; margin-top: auto; background:none;  padding:0 10px;"> '.$f['size'].' '.$f['load'].' '.$f['speed'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
													    <input type="hidden" id="delay'.$f['id'].'" value="'.$delay.'">
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
		}else 	$budgetTyre = ''; // no heading


		//mellom tyres
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE season='$season' AND `size` LIKE '%$size%' AND category='mellom' AND location = 'Moss Dekk AS' ");
		if(mysqli_num_rows($q) > 0) {
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {

				$tyreID = $f['id'];
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
				$stock = $fs['stock'];
                $delay = $fs['delay'];
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="" >Sold Out</div>';
				}

                $border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = '';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}

				$mellomTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:0px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url(\'uploads/tyreImg/'.$f['image'].'\') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$f['brand'].'  <br> '.$f['model'].' </div>
											<div class="tyreSize" style="font-size:15px; margin-top: auto; background:none;  padding:0 10px;"> '.$f['size'].' '.$f['load'].' '.$f['speed'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<input type="hidden" id="delay'.$f['id'].'" value="'.$delay.'">
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
		} else 	$mellomTyre = '';


		//premium tyres
		$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE season='$season' AND `size` LIKE '%$size%' AND category='premium' AND location = 'Moss Dekk AS'");
		if(mysqli_num_rows($q) > 0) {
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {

				$tyreID = $f['id'];
				$fs = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_stock WHERE tyreID=$tyreID"), MYSQLI_ASSOC);
				$stock = $fs['stock'];
                $delay = $fs['delay'];
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="">Sold Out</div>';
				}


				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = '';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}

				$premiumTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:0px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url(\'uploads/tyreImg/'.$f['image'].'\') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$f['brand'].' <br> '.$f['model'].'  </div>
											<div class="tyreSize" style="font-size:15px;margin-top: auto; background:none;  padding:0 10px;"> '.$f['size'].' '.$f['load'].' '.$f['speed'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<input type="hidden" id="delay'.$f['id'].'" value="'.$delay.'">
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
		}	else $premiumTyre = '';
		
		
	    // Get price adjustments from misc tabel, brand discount and fixed %
        $attributes = mysqli_query($con, "SELECT `property`,`attribute1`,`attribute2`,`attribute3` FROM shop_misc WHERE attribute5 = 'Moss Dekk AS' and (property = 'shopTyresApiPriceRegulator' OR property = 'shopTyresApiBrandDiscount')");
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
        //budget NorgesDekk
		$q = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE season='$season' AND `profile` = '$profile' AND width = '$width' AND `inches` = '$inches'  AND category='budget'");
		if(mysqli_num_rows($q) > 0) {
		    if($budgetTyre == '') {$budgetTyre .= '<h2 style="text-align:center; color:#529503;">Budsjett</h2>';}
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
                // If the tyre's brand doesn't have a discount, skip this iteration
                if (!isset($brandDiscounts[$f['brand']])) {
                    continue;  // Skip to the next tyre in the list
                }
				$tyreID = $f['id'];
				$stock = $f['stock'];
                $delay = $f['delay'];
                $tyreBrand = $f['brand'];
                $tyrePrice = $f['price'];
                $width = $f['width'];
                $profile = $f['profile'];
                $inches = $f['inches'];
                $brandMultiplier = isset($brandDiscounts[$tyreBrand]) ? 1 - ($brandDiscounts[$tyreBrand] / 100) : 1; // Convert the discount to a multiplier
                $customerPrice = ($tyrePrice  * $brandMultiplier * (1 + $priceRegulatorPercent/100) + $priceRegulatorFixed) ;
                $customerPrice *= 1.25; // Add moms (25%)
                
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.round($customerPrice).'" data-tyreidApi="'.$f['id'].'" data-tyreid="0">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="">Sold Out</div>';
				}

				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = '';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}
				$budgetTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:0px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url('.$f['image'].') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$tyreBrand.' <br> '.$f['model'].'  </div>
											<div class="tyreSize" style="font-size:15px;margin-top: auto; background:none;  padding:0 10px;"> '.$width.'/'.$profile.'-'.$inches.' '.$f['load'].' '.$f['speed'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<input type="hidden" id="delay'.$f['id'].'" value="'.$delay.'">
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
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK '.round($customerPrice).'</b></div>
											<div class="tyreButtons" style="">
												'.$buyButton.'
												<a href="?p=productDetailsApi&pID='.$f['id'].'"><div class="detailsButton" style="">Details</div></a>
											</div>
										</div>
								</div>';
				$budgetNum++;
			}
		}
        //mellom NorgesDekk
		$q = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE season='$season' AND `profile` = '$profile' AND width = '$width' AND `inches` = '$inches'  AND category='mellom'");
		if(mysqli_num_rows($q) > 0) {
		    if($mellomTyre == '') {$mellomTyre .= '<h2 style="text-align:center; color:#529503;">Kvalitet</h2>';}
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
                // If the tyre's brand doesn't have a discount, skip this iteration
                if (!isset($brandDiscounts[$f['brand']])) {
                    continue;  // Skip to the next tyre in the list
                }
				$tyreID = $f['id'];
				$stock = $f['stock'];
                $delay = $f['delay'];
                $tyreBrand = $f['brand'];
                $tyrePrice = $f['price'];
                $width = $f['width'];
                $profile = $f['profile'];
                $inches = $f['inches'];
                $brandMultiplier = isset($brandDiscounts[$tyreBrand]) ? 1 - ($brandDiscounts[$tyreBrand] / 100) : 1; // Convert the discount to a multiplier
                $customerPrice = ($tyrePrice  * $brandMultiplier * (1 + $priceRegulatorPercent/100) + $priceRegulatorFixed) ;
                $customerPrice *= 1.25; // Add moms (25%)
                
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.round($customerPrice).'" data-tyreidApi="'.$f['id'].'" data-tyreid="0">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="">Sold Out</div>';
				}

				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = '';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}
				$mellomTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:0px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url('.$f['image'].') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$tyreBrand.' <br> '.$f['model'].'  </div>
											<div class="tyreSize" style="font-size:15px;margin-top: auto; background:none;  padding:0 10px;"> '.$width.'/'.$profile.'-'.$inches.' '.$f['load'].' '.$f['speed'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<input type="hidden" id="delay'.$f['id'].'" value="'.$delay.'">
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
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK '.round($customerPrice).'</b></div>
											<div class="tyreButtons" style="">
												'.$buyButton.'
												<a href="?p=productDetailsApi&pID='.$f['id'].'"><div class="detailsButton" style="">Details</div></a>
											</div>
										</div>
								</div>';
				$mellomNum++;
			}
		}

		//premium tyres NorgesDekk
		$q = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE season='$season' AND `profile` = '$profile' AND width = '$width' AND `inches` = '$inches'  AND category='premium'");
		if(mysqli_num_rows($q) > 0) {
		    if($premiumTyre == '') {$premiumTyre .= '<h2 style="text-align:center; color:#529503;">Premium</h2>';}
			$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
			foreach($fetch as $f) {
                // If the tyre's brand doesn't have a discount, skip this iteration
                if (!isset($brandDiscounts[$f['brand']])) {
                    continue;  // Skip to the next tyre in the list
                }
				$tyreID = $f['id'];
				$stock = $f['stock'];
                $delay = $f['delay'];
                $tyreBrand = $f['brand'];
                $tyrePrice = $f['price'];
                $width = $f['width'];
                $profile = $f['profile'];
                $inches = $f['inches'];
                $brandMultiplier = isset($brandDiscounts[$tyreBrand]) ? 1 - ($brandDiscounts[$tyreBrand] / 100) : 1; // Convert the discount to a multiplier
                $customerPrice = ($tyrePrice  * $brandMultiplier * (1 + $priceRegulatorPercent/100) + $priceRegulatorFixed) ;
                $customerPrice *= 1.25; // Add moms (25%)
                
				if($stock > 0) {
					$buyButton = '<div class="buyButton" style="" data-toggle="modal" data-target="#buyTyreModal" data-price="'.round($customerPrice).'" data-tyreidApi="'.$f['id'].'" data-tyreid="0">Buy</div>';
				}else {
					$buyButton = '<div class="buyButton disabled" style="">Sold Out</div>';
				}

				$border = 'border: 1px solid #ccc;';
				$badge = $borderStyle = '';
				$recommended = $f['recommended'];
				if($recommended == 1) {
					$border = '';
					$borderStyle = '';
					$badge = '<div class="badges" style="margin:10px 0px -5px 0px;"><span class="badge badge-warning">Recommended</span></div>';
				}
				$premiumTyre .= '<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:0px; background:none;">
										<div class="tyreCard '.$border.'" style="'.$borderStyle.'">
											<div class="tyreImg" style="background:url('.$f['image'].') no-repeat center; background-size:contain; background-color:#fff;">
												
											</div>
											'.$badge.'
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> '.$tyreBrand.' <br> '.$f['model'].'  </div>
											<div class="tyreSize" style="font-size:15px; margin-top: auto; background:none;  padding:0 10px;"> '.$width.'/'.$profile.'-'.$inches.' '.$f['load'].' '.$f['speed'].'</div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<input type="hidden" id="delay'.$f['id'].'" value="'.$delay.'">
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
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK '.round($customerPrice).'</b></div>
											<div class="tyreButtons" style="">
												'.$buyButton.'
												<a href="?p=productDetailsApi&pID='.$f['id'].'"><div class="detailsButton" style="">Details</div></a>
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

//vegvesenAPI https://autosys-kjoretoy-api.atlas.vegvesen.no/swagger-ui/index-enkeltoppslag.html

function vegvesenAPIauth($regNr) {
    $url = 'https://www.vegvesen.no/ws/no/vegvesen/kjoretoy/felles/datautlevering/enkeltoppslag/kjoretoydata?kjennemerke=' . $regNr;
    $headers = [
        "SVV-Authorization: Apikey dfe02125-485f-489b-8afc-c0de610b2cc0"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
//Same funktcion in autobutlerQuery.php. Need to change in both
function vegvesenAPIGet() {
    $regNr = p($_POST['regNr']);
    $load1 = $load2 = '';
    $speed1 = $speed2 = '';
    
    $i = 1;
    $size1 = 0;
    $size2 = 0;
    ${"size" . $i} = 0;
    ${"speed" . $i} = 0;
    ${"load" . $i} = 0;
    if ($regNr) {
        $kjoretoy = vegvesenAPIauth($regNr);
        $akselDekkOgFelgListe = $kjoretoy['kjoretoydataListe'][0]['godkjenning']['tekniskGodkjenning']['tekniskeData']['dekkOgFelg']['akselDekkOgFelgKombinasjon'][0]['akselDekkOgFelg'];
    
        foreach ($akselDekkOgFelgListe as $akselData) {
            ${"size" . $i} = htmlspecialchars($akselData['dekkdimensjon']);
            ${"speed" . $i} = htmlspecialchars($akselData['hastighetskodeDekk']);
            ${"load" . $i} = htmlspecialchars($akselData['belastningskodeDekk']);
            $i++;
        }
       $model= htmlspecialchars($kjoretoy['kjoretoydataListe'][0]['godkjenning']['tekniskGodkjenning']['tekniskeData']['generelt']['handelsbetegnelse'][0]);
       $brand = htmlspecialchars($kjoretoy['kjoretoydataListe'][0]['godkjenning']['tekniskGodkjenning']['tekniskeData']['generelt']['merke'][0]['merke']);
    $con = dbCon();   
    // save info to database for later use
    $stmt = $con->prepare("INSERT INTO shop_tyres_api_dim (regNr, brand, model, size1, size2, speed1, speed2, tyreLoad1, tyreLoad2) VALUES (?,?,?, ?, ?, ?, ?,?,?)  ON DUPLICATE KEY UPDATE 
            brand = VALUES(brand),
            model = VALUES(model),
            size1 = VALUES(size1),
            size2 = VALUES(size2),
            speed1 = VALUES(speed1),
            speed2 = VALUES(speed2),
            tyreLoad1 = VALUES(tyreLoad1),
            tyreLoad2 = VALUES(tyreLoad2)
    ");
    
    $stmt->bind_param("sssssssss", 
        $regNr,
        $brand,
        $model,
        $size1,
        $size2,
        $speed1,
        $speed2,
        $load1,
        $load2
    );
    
    if (!$stmt->execute()) {
        $r = ['error' => $stmt->error];
        echo json_encode($r);
        return;
    } else {
        $r = [
            'status' => 'success',
            'regNr' => $regNr,
            'brand' => $brand,
            'model' => $model,
            'size1' => $size1,
            'size2' => $size2,
            'speed1' => $speed1,
            'speed2' => $speed2,
            'load1' => $load1,
            'load2' => $load2
        ];
        echo json_encode($r);
        return;
    }
     $r = [
            'status' => 'success'
            ];
    echo json_encode($r);
    mysqli_close($con);
    return;    
        
    }
}
?>