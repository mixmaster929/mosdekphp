 <span style="color: white;">Bestillingen er under behandling...</span><br>
 <img src="images/Rolling.gif" class="servicesLoadingDekk" style="width:20px; height:auto; margin:auto; display:block;" />
<?php

$email = $_GET['email'];
$totalTime = $_GET['totalTime'];
$workType = $_GET['workType'];
$price = $_GET['price'];
$regNr = $_GET['regNr'];
$name = $_GET['name'];
$mobile = $_GET['mobile'];
$date = $_GET['date'];
$serviceIDs = $_GET['serviceIDs'];
$time = $_GET['time'];
$tyres = $_GET['tyres'];
$error = $_GET['error'];
//$ps = $_GET['ps'];
//$_GET['error']; 
//error=failed or cancelled
$transactionID = $_GET['transaction_id'];

$serviceCounts = $_GET['serviceCounts'];
$locationID = $_GET['locationID'];
$addressLocation = $_GET['addressLocation'];
$postcodeLocation = $_GET['postcodeLocation'];
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

// ?p=successfulOrder2&email=0&totalTime=0&workType=0&price=0&regNr=0&name=0&mobile=0&date=0&serviceIDs=0&serviceCounts=0&time=0&tyres=0&ps=1&locationID=0&postcodeLocation=32&addressLocation=0&cityLocation=0&orgNr=0&reference=0&paymentMode=0&selType=0&tyreID=0&tyreIDs=0&privateCustomerID=0&offerID=0&type='tyreChangeDekkhotell'&serviceCounts=1;
?>

<script>
function paymentAuthorizeDintero() {
    console.log("Hello");

    var transactionID =  '<?= $transactionID ?>';
    var error = '<?= $error ?>';
    if(transactionID !=  '' && error == ''){
        
        var email = '<?= $email ?>';
        var totalTime = '<?= $totalTime ?>';
        var workType = '<?= $workType ?>';
        var price = '<?= $price ?>';
        var regNr = '<?= $regNr ?>';
        var name = '<?= $name ?>';
        var mobile = '<?= $mobile ?>';
        var date = '<?= $date ?>';
        var serviceIDs = '<?= $serviceIDs ?>';
        var serviceCounts = '<?= $serviceCounts ?>';
        var time = '<?= $time ?>';
        var tyres = '<?= $tyres ?>';
        var locationID = '<?= $locationID ?>'; 
        var addressLocation = '<?= $addressLocation ?>'; 
        var postcodeLocation = '<?= $postcodeLocation ?>'; 
        var cityLocation = '<?= $cityLocation ?>'; 
        var orgNr = '<?= $orgNr ?>'; 
        var reference = '<?= $reference ?>'; 
        var paymentMode = '<?= $paymentMode ?>'; 
        var selType = '<?= $selType ?>'; 
        var tyreID = '<?= $tyreID ?>'; 
        var tyreIDs = '<?= $tyreIDs ?>'; 
        var privateCustomerID = '<?= $privateCustomerID ?>'; 
        var offerID = '<?= $offerID ?>'; 
        var type = '<?= $type ?>'; 
        //logg if paymenttriggered
        var eventstatus = 'Paymentsuccess'; 
        var regNr = '<?= $regNr ?>'; 
        var ordresvar = '';
        var alertMessage = '';
        var servicesURL = '';
        var IDs = serviceIDs.split(',');
        IDs.forEach(function (id) {
            if(id == '' || id == ' ' || id == 'undefined') { return true; }
            servicesURL += '&service'+id+'='+variablesDekk['service'+id];
        });
        var url = 'method=errorHandling&regNr='+regNr+'&event='+eventstatus;
        fetch(url, function(result) {
        });   
        //logg end 
        
        saveCustomerInfo(function(result) { });
    
        
        //checkoutVariable = checkout;
        //var payURL = '&txnID=' + event.transaction_id;
        
        if(type == 'tyreChangeDekkhotell') {
            var url = 'locationID='+locationID+'&addressLocation='+addressLocation+'&postcodeLocation='+postcodeLocation+'&cityLocation='+cityLocation+'&method=tyreChangeDekkhotellOrderWithoutLogin&orgNr='+orgNr+'&reference='+reference+'&paymentMode='+paymentMode+'&paymentDone=1&email='+email+'&totalTime='+totalTime+'&workType=tyreChange&price='+price+'&regNr='+regNr+'&name='+name+'&mobile='+mobile+'&date='+date+'&serviceIDs='+serviceIDs+'&time='+time+'&transactionID='+transactionID+'&pCID='+privateCustomerID+'&offerID='+offerID+'&tyreID='+tyreID+'&selType='+selType;
        }else {
            var url = 'locationID='+locationID+'&addressLocation='+addressLocation+'&postcodeLocation='+postcodeLocation+'&cityLocation='+cityLocation+'&method=tyreChangeDekkhotellOrderWithoutLogin&dekk=1&orgNr='+orgNr+'&reference='+reference+'&paymentMode='+paymentMode+'&paymentDone=1&email='+email+'&totalTime='+totalTime+'&workType='+workType+'='+price+'&regNr='+regNr+'&name='+name+'&mobile='+mobile+'&date='+date+'&serviceIDs='+serviceIDs+'&time='+time+'&transactionID='+transactionID+'&pCID='+privateCustomerID+'&offerID='+offerID+'&tyres='+tyres;
        }
        console.log('url:'+url);
        fetch(url, function(result) {
    var e = JSON.parse(result);
    var ordresvar = e[0];
    console.log(ordresvar);
    handleOrdresvar(ordresvar); // Anropa en callback-funktion med ordresvar som argument
});

function handleOrdresvar(ordresvar) {
    var alerMessage = '';
    if(ordresvar == 'failed') {
        alerMessage = 'Technical error, contact admin';
    } else if(ordresvar == 'success') { 
        alerMessage ='Bestillingen din er mottatt og registrert. Du vil snart få e-mail med bekreftelse.';
    } else if(ordresvar == 'already ordered') {
        alerMessage = 'This Reg Nr is already under process';
    } else if(ordresvar == 'no work') {
        alerMessage = 'This work has not been assigned';
    } else if(ordresvar == 'no employee') {
        alerMessage = 'No employee available at this time';
    } else if(ordresvar == 'no tyre') {
        alerMessage ='No tyre exist with this regNr';
    }
    window.location.href = "?p=successfulOrder&"+'locationID='+locationID+'&addressLocation='+addressLocation+'&postcodeLocation='+postcodeLocation+'&cityLocation='+cityLocation+'&tyreID='+tyreID+'&email='+email+'&totalTime='+totalTime+'&workType='+type+'&price='+price+'&regNr='+regNr+'&name='+name+'&mobile='+mobile+'&date='+date+'&ordresvar='+ordresvar+'&alertMessage='+alertMessage+'&serviceIDs='+serviceIDs+'&serviceCounts='+serviceCounts+'&time='+time+'&tyres='+tyres+servicesURL;
}

    }
    else{
        alerMessage = 'Bestillingen din er kansellert';    
        ordresvar = 'cancel';
        window.location.href = "?p=successfulOrder&"+'locationID='+locationID+'&addressLocation='+addressLocation+'&postcodeLocation='+postcodeLocation+'&cityLocation='+cityLocation+'&tyreID='+tyreID+'&email='+email+'&totalTime='+totalTime+'&workType='+type+'&price='+price+'&regNr='+regNr+'&name='+name+'&mobile='+mobile+'&date='+date+'&ordresvar='+ordresvar+'&alertMessage='+alertMessage+'&serviceIDs='+serviceIDs+'&serviceCounts='+serviceCounts+'&time='+time+'&tyres='+tyres+servicesURL;
    }    
}
paymentAuthorizeDintero();
</script>

