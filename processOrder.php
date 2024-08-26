<span style="color: white; display: flex; justify-content: center; align-items: center;">Bestillingen er under behandling...error</span><br>
 <img src="images/Rolling.gif" class="servicesLoadingDekk" style="width:20px; height:auto; margin:auto; display:block;" />'
<?php

function paymentAuthorizeDintero() {
    $email = urldecode($_GET['email']);
    $totalTime = $_GET['totalTime'];
    $workType = $_GET['workType'];
    $price = $_GET['price'];
    $regNr = $_GET['regNr'];
    $name = urldecode($_GET['name']);
    $mobile = $_GET['mobile'];
    $date = urldecode($_GET['date']);
    $serviceIDs = urldecode($_GET['serviceIDs']);
    $time = urldecode($_GET['time2']);
    $tyres = urldecode($_GET['tyres']);
    $error = $_GET['error'];
    $apiTyre = $_GET['apiTyre'];
    //$ps = $_GET['ps'];
    //$_GET['error']; 
    //error=failed or cancelled
    $transactionID = $_GET['transaction_id'];
    //$dekk = 1;
    $serviceCounts = $_GET['serviceCounts'];
    $locationID = $_GET['locationID'];
    $addressLocation = urldecode($_GET['addressLocation']);
    $postcodeLocation = urldecode($_GET['postcodeLocation']);
    $cityLocation = $_GET['cityLocation'];
    $orgNr = $_GET['orgNr'];
    $reference = $_GET['reference'];
    $paymentMode = $_GET['paymentMode'];
    $privateCustomerID = (int)p($_get['pCID']);
    $tyreID = (int)p($_GET['tyreID']);
    $tyreIDs = $tyreID; //.',';
    $offerID = (int)p($_GET['offerID']);
    $selType = p($_GET['selType']);
    $type = $_GET['type'];
    $alertMessage = 'Bestillingen din er kansellert';    
    $ordresvar = 'cancel';
    $method= 'tyreOrderWithoutLogin';
   if($transactionID != '' && $error == '') {
            if($type == 'tyreChangeDekkhotell') {
                $dekk= '1';
                $type = 'tyreChange';
                $method ='tyreChangeDekkhotellOrderWithoutLogin';
            }    
            $ordresvar = 'success';
            $postData = [
            'locationID' => $locationID,
            'addressLocation' => $addressLocation,
            'postcodeLocation' => $postcodeLocation,
            'cityLocation' => $cityLocation,
            'method' => $method,
            'orgNr' => $orgNr,
            'reference' => $reference,
            'paymentMode' => $paymentMode,
            'paymentDone' => 1,
            'email' => $email,
            'apiTyre' => $apiTyre,
            'totalTime' => $totalTime,
            'workType' => $type,
            'price' => $price,
            'regNr' => $regNr,
            'name' => $name,
            'mobile' => $mobile,
            'dekk' => $dekk,
            'date' => $date,
            'serviceIDs' => $serviceIDs,
            'time' => $time,
            'transactionID' => $transactionID,
            'pCID' => $privateCustomerID,
            'offerID' => $offerID,
            'tyreID' => $tyreID,
            'selType' => $selType,
            'tyres' => $tyres
        ];
    $con = dbCon();
    $qo = mysqli_query($con, "SELECT * FROM shop_invoice WHERE DATE(`orderedOn`) = CURDATE() AND regNr = '$regNr' and price = '$price'");    
    if(mysqli_num_rows($qo) == 0) {
        
        $servicesURL = '';
        $IDs = explode(',', $serviceIDs);
        foreach($IDs as $id) {
            if($id == '' || $id == ' ' || $id == 'undefined') { continue; }
            $servicesURL .= '&service'.$id.'='.$variablesDekk['service'.$id];
        }
            $locationParams = 'locationID='.$locationID.'&addressLocation='.$addressLocation.'&postcodeLocation='.$postcodeLocation.'&cityLocation='.$cityLocation.'&apiTyre='.$apiTyre.'&tyreID='.$tyreID.'&email='.$email.'&totalTime='.$totalTime.'&workType='.$type.'&price='.$price.'&regNr='.$regNr.'&name='.$name.'&mobile='.$mobile.'&date='.$date.'&ordresvar='.$ordresvar.'&alertMessage='.$alertMessage.'&serviceIDs='.$serviceIDs.'&serviceCounts='.$serviceCounts.'&time='.$time.'&tyres='.$tyres.$servicesURL;
            
            $url = 'https://dev.mossdekk.no/query.php';
            $response = get_web_page($url, $postData);
            $resArr = json_decode($response);
            $ordresvar = $resArr[0];
            handleOrdresvar($ordresvar,$postData);
        }else handleOrdresvar($ordresvar,$postData);
    }else handleOrdresvar($ordresvar,$postData);
}


function handleOrdresvar($ordresvar,$postData) {
        $alertMessage = '';
        if ($ordresvar == 'failed') {
            $alertMessage = 'Technical error, contact admin';
        } else if ($ordresvar == 'success') { 
            $alertMessage ='Bestillingen din er mottatt og registrert. Du vil snart få e-mail med bekreftelse.';
        } else if ($ordresvar == 'Bestillingen din er kansellert') { 
            $alertMessage ='Bestillingen din er mottatt og registrert. Du vil snart få e-mail med bekreftelse.';
        } else if ($ordresvar == 'already ordered') {
            $alertMessage = 'This Reg Nr is already under process';
        } else if ($ordresvar == 'no work') {
            $alertMessage = 'This work has not been assigned';
        } else if ($ordresvar == 'no employee') {
            $alertMessage = 'No employee available at this time';
        } else if ($ordresvar == 'no tyre') {
            $alertMessage ='No tyre exist with this regNr';
        }
        $urlParams = "?p=successfulOrder&locationID=" . $postData['locationID'] . "&addressLocation=" . $postData['addressLocation'] . "&postcodeLocation=" . $postData['postcodeLocation'] . "&cityLocation=" . $postData['cityLocation'] . "&tyreID=" . $postData['tyreID'] . "&email=" . $postData['email'] . "&totalTime=" . $postData['totalTime'] . "&workType=" . $postData['workType'] . "&price=" . $postData['price'] . "&regNr=" . $postData['regNr'] . "&name=" . $postData['name'] . "&mobile=" . $postData['mobile'] . "&date=" . $postData['date'] . "&ordresvar=" . $ordresvar . "&alertMessage=" . $alertMessage . "&serviceIDs=" . $postData['serviceIDs'] . "&serviceCounts=" . $postData['serviceCounts'] . "&time=" . $postData['time'] . "&tyres=" . $postData['tyres'];
    echo "<script>window.onload = function() { window.location.href='".$urlParams."'; };</script>";
    }
paymentAuthorizeDintero();
?>