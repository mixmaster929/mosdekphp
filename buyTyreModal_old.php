<div class="modal fade" id="buyTyreModal" tabindex="-1" role="dialog" aria-labelledby="buyTyreModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="">
      <div class="modal-header">
        <h5 class="modal-title" id="buyTyreModal">Buy Tyre</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="alert alert-warning" role="alert" style="border-radius: 0; margin: -16px -16px 20px; padding: 5px 20px; font-size: 15px; ">
		 Fill up all the details, and select services (optional)
	  </div>
	  
		<div class="priceContainer " style="">
			<span style="font-size:18px; color:#555;"> Price: Kr </span>
			<span id="orderPrice" style="font-size:25px; color:#000; font-weight:400;"> 300 </span>
		</div>
		
		<form>
          <div class="form-group" style="margin-bottom:10px;">
            <label for="regNr" class="col-form-label labelMod">Reg Nr:</label>
            <input type="text" class="form-control inputMod" id="regNr" placeholder="Vehicle Reg Nr">
          </div>
		   <div class="form-group" style="margin-bottom:10px;">
            <label for="name" class="col-form-label labelMod">Name:</label>
            <input type="text" class="form-control inputMod" id="name" placeholder="Full name">
          </div>
		   <div class="form-group" style="margin-bottom:10px;">
            <label for="mobile" class="col-form-label labelMod">Mobile:</label>
            <input type="text" class="form-control inputMod" id="mobile" placeholder="Mobile number">
          </div>
		  <div class="form-group" style="margin-bottom:10px;">
            <label for="email" class="col-form-label labelMod">Email:</label>
            <input type="text" class="form-control inputMod" id="email" placeholder="Email address">
          </div>
		  <div class="form-group" style="margin-bottom:10px;">
            <label for="tyres" class="col-form-label labelMod">Number of Tyres:</label>
            <select class="form-control inputMod" id="tyres">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
			</select>
          </div>
		  <hr>
			
			<img src="images/Rolling.gif" class="servicesLoading" style="width:20px; height:auto; margin:auto; display:block;" />
			<div id="servicesContainer">
				<div class="serviceBar inactiveService " style="">
					Service 1 desc here..
					<div style="display:inline-block; margin-left:10px; padding-left:10px; border-left:1px solid #ccc;">
						Kr 50
					</div>
				</div>
				
				<div class="serviceBar activeService " style="font-size:14px; display:inline-block; border:1px solid #007bff; color:#fff; background-color:#007bff; border-radius:0.25rem; padding:2px 10px; margin:3px;">
					Service 2 description goes here...
					<div style="display:inline-block; margin-left:10px; padding-left:10px; border-left:1px solid #eee;">
						Kr 50
					</div>
				</div>
			</div>
		  
		  <hr>
           <div class="form-group" style="margin-bottom:10px;">
            <label for="tyreChangeDateTime" class="col-form-label labelMod">Date & Time:</label>
            <input type="text" class="form-control inputMod" id="tyreChangeDateTime" placeholder="Tyre change date & time">
          </div>
		  <hr>
		  
		  
			<img src="images/Rolling.gif" class="timeSlotsLoading" style="width:20px; height:auto; margin:auto; display:block;" />
		  
		  
		  <div id="timeSlotsContainer" style="display:none;">
				<div class="serviceBar inactiveService " style="">
					11:30
				</div>
				<div class="serviceBar inactiveService " style="">
					12:30
				</div>
				<div class="serviceBar activeService " style="">
					13:30
				</div>
			</div>
		  
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		 <!-- <button type="button" class="btn btn-primary" id="checkButton" disabled>Check</button>-->
        <button type="button" class="btn btn-success" id="continueButton">Continue</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="paymentOptionModal" tabindex="-1" role="dialog" aria-labelledby="paymentOptionModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentOptionModalTitle">Payment Option</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" style="margin-bottom:10px;">
            <label for="paymentOption" class="col-form-label labelMod" style="width:auto; margin-right:20px;">Select a payment mode:</label>
            <select class="form-control inputMod" id="paymentOption">
				<option value="payNow">Kort betaling/delbetaling</option>
				<option value="orgNr">Firmakunde</option>
				<option value="payAtShop">Betaling i butikk</option>
			</select>
        </div>
		<div class="form-group orgNr" style="margin-bottom:10px; display:none;">
            <label for="orgNr" class="col-form-label labelMod">Organisation Nr:</label>
            <input type="text" class="form-control inputMod" id="orgNr" placeholder="Org Nr">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
        <button type="button" class="btn btn-primary" id="paymentOptionContinue">Continue</button>
      </div>
    </div>
  </div>
</div>

<script>
var payVariables = new Array();
var checkout = new Bambora.ModalCheckout(null);
checkout.on(Bambora.Event.Authorize, paymentAuthorize);
checkout.on(Bambora.Event.Close, paymentClose);
checkout.on(Bambora.Event.Cancel, paymentCancel);

function bamboraNewInstance(emptyVar) {
	checkout.destroy();
	checkout = new Bambora.ModalCheckout(null);
	checkout.on(Bambora.Event.Authorize, paymentAuthorize);
	checkout.on(Bambora.Event.Close, paymentClose);
	checkout.on(Bambora.Event.Cancel, paymentCancel);
	if(emptyVar == 1) {
		payVariables = [];
	}
}

function showPaymentModal(token, url) {
	checkout
		.initialize(token)
		.then(function() {
			// The session has been fetched.
			checkout.show();
			hideLoadingBar();
			// Since Bambora Checkout has been preloaded, it is shown immediately.
	});
	
}

function paymentClose(payload) {
	console.log(payload);
	//var interval = setInterval(function () {
		showLoadingBar();
		
		var servicesURL = '';
		var IDs = variables['serviceIDs'].split(',');
		IDs.forEach(function (id) {
			if(id == '' || id == ' ' || id == 'undefined') { return true; }
			servicesURL += '&service'+id+'='+variables['service'+id];
		});
		
		var payURL = '&amount='+payVariables['amount']+'&cardNo='+payVariables['cardNo']+'&currency='+payVariables['currency']+'&payDate='+payVariables['date']+'&eci='+payVariables['eci']+'&feeID='+payVariables['feeID']+'&hash='+payVariables['hash']+'&issuerCountry='+payVariables['issuerCountry']+'&orderID='+payVariables['orderID']+'&paymentType='+payVariables['paymentType']+'&reference='+payVariables['reference']+'&payTime='+payVariables['time']+'&txnID='+payVariables['txnID'];
		
		var url = 'method=tyreOrderWithoutLogin&paymentDone=1&orgNr='+variables['orgNr']+'&paymentMode='+variables['paymentMode']+'&tyreID='+variables['tyreID']+'&totalTime='+variables['totalTime']+'&workType=newTyre&price='+variables['price']+'&regNr='+variables['regNr']+'&name='+variables['name']+'&mobile='+variables['mobile']+'&date='+variables['date']+'&serviceIDs='+variables['serviceIDs']+'&time='+variables['time']+'&tyres='+variables['tyres']+'&email='+variables['email']+payURL+servicesURL;
		fetch(url, function(result) {
			bamboraNewInstance(1);
			hideLoadingBar();
			var e = JSON.parse(result);
			if(e[0] == 'failed') {
				showAlert('danger', 'Technical error, contact admin');
			}else if(e[0] == 'success') {
				showAlert('success', 'Bestillingen din er nå mottatt og registrert. Du vil snart få e-mail med bekreftelse.');
				//showAlert('success', 'Successfully placed your order');
				variables = [];
				$('#buyTyreModal').modal('hide');
			}else if(e[0] == 'already ordered') {
				showAlert('danger', 'This Reg Nr is already under process');
			}else if(e[0] == 'no work') {
				showAlert('danger', 'This work has not been assigned');
			}else if(e[0] == 'no employee') {
				showAlert('danger', 'No employee available at this time');
			}
		});
		
		
		//clearInterval(interval);
	//}, 2000);
}

function paymentAuthorize(payload) {
	console.log(payload);
	var e = payload['data'];
	payVariables['amount'] = e['amount'];
	payVariables['cardNo'] = e['cardno'];
	payVariables['currency'] = e['currency'];
	payVariables['date'] = e['date'];
	payVariables['eci'] = e['eci'];
	payVariables['feeID'] = e['feeid'];
	payVariables['hash'] = e['hash'];
	payVariables['issuerCountry'] = e['issuercountry'];
	payVariables['orderID'] = e['orderid'];
	payVariables['paymentType'] = e['paymenttype'];
	payVariables['reference'] = e['reference'];
	payVariables['time'] = e['time'];
	payVariables['txnID'] = e['txnid'];
	
	paymentClose(payload);
	
}

function paymentCancel(payload) {
	console.log(payload);
	bamboraNewInstance(1);
}

variables = new Array();
variables['paymentDone'] = 0;

/*
$('.frontSelect').on('change', function(e) {
	var season = $('.seasonSelect').find(':selected').val();
	var sizeOne = $('.sizeOneSelect').find(':selected').val();
	var sizeTwo = $('.sizeTwoSelect').find(':selected').val();
	var sizeThree = $('.sizeThreeSelect').find(':selected').val();
	
	var url = 'method=fetchFrontTyres&season='+season+'&sizeOne='+sizeOne+'&sizeTwo='+sizeTwo+'&sizeThree='+sizeThree;
	showFrontLoading();

	$('.budgetContainer').html('');
	$('.mellomContainer').html('');
	$('.premiumContainer').html('');
			
	fetch(url, function(result) {
		hideFrontLoading();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			$('.frontTyresContainer').show();
			$('.budgetContainer').html(e[1]);
			$('.mellomContainer').html(e[2]);
			$('.premiumContainer').html(e[3]);
		}else if(e[0] == 'no entry') {
			showAlert('danger', 'No entries found for these parameters');
		}else {
			//showModal('Error!', 'Some error occurred while fetching for tyres');
			showAlert('danger', 'Some error occurred while fetching for tyres');
		}
	});
});
*/

$('.searchTyreButton').on('click', function(e) {
	var season = $('.seasonSelect').find(':selected').val();
	var sizeOne = $('.sizeOneSelect').find(':selected').val();
	var sizeTwo = $('.sizeTwoSelect').find(':selected').val();
	var sizeThree = $('.sizeThreeSelect').find(':selected').val();
	
	var url = 'method=fetchFrontTyres&season='+season+'&sizeOne='+sizeOne+'&sizeTwo='+sizeTwo+'&sizeThree='+sizeThree;
	showFrontLoading();

	$('.budgetContainer').html('');
	$('.mellomContainer').html('');
	$('.premiumContainer').html('');
	$('.tyreSearchResult').slideUp(200);
	fetch(url, function(result) {
		hideFrontLoading();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			$('.tyreSearchResult').slideDown(200);
			$('.frontTyresContainer').show();
			$('.budgetContainer').html(e[1]);
			$('.mellomContainer').html(e[2]);
			$('.premiumContainer').html(e[3]);
			$('.budgetNum').html(e[4]);
			$('.mellomNum').html(e[5]);
			$('.premiumNum').html(e[6]);
			$('html, body').animate({
				scrollTop: $(".tyreSearchResult").offset().top
			}, 1000);
		}else if(e[0] == 'no entry') {
			showAlert('danger', 'No entries found for these parameters');
		}else {
			//showModal('Error!', 'Some error occurred while fetching for tyres');
			showAlert('danger', 'Some error occurred while fetching for tyres');
		}
		
	});
});

function showFrontLoading() {
	$('.frontTyresContainer').hide(200);
	$('.frontLoading').show(100);
}

function hideFrontLoading() {
	$('.frontLoading').hide(300);
}

$('#tyreChangeDateTime').datetimepicker({
	minDate: moment(new Date()).tz('Europe/Oslo').format('YYYY/MM/DD'),
	format: 'YYYY/MM/DD',
	sideBySide: false,
});

$('#tyreChangeDateTime').on('dp.change', function(e) {
	$('#continueButton').attr('disabled', true);
	showTimeSlots(e.date);
});

function saveTime(time, timeID) {
	if(time == '') { return; }
	
	variables['time'] = time;
	$('.dateTime').removeClass('activeService').addClass('inactiveService');
	$('.dateTime'+timeID).removeClass('inactiveService').addClass('activeService');
	
	if(variables['time'] != '') {
		$('#continueButton').attr('disabled', false);
	}else {
		$('#continueButton').attr('disabled', true);
	}
}

function showTimeSlots(date) {
	
	var day = moment(date).format('dddd');
	var sendDate = moment(date).format('YYYY/MM/DD');
	
	variables['time'] = '';
	$('#timeSlotsContainer').hide(200).html('');
	$('.timeSlotsLoading').show(100);
			
	var url = 'method=getTimeSlots&day='+day+'&serviceIDs='+variables['serviceIDs']+'&date='+sendDate;
	fetch(url, function(result) {
		$('.timeSlotsLoading').hide(200);
		var e = $.parseJSON(result);
		if(e[0] == 'failed') {
			showAlert('danger', 'Technical error, contact admin');
		}else if(e[0] == 'success') {
			variables['totalTime'] = e[2];
			$('#timeSlotsContainer').html(e[1]).show(100);
		}else if(e[0] == 'closed') {
			showAlert('warning', 'The shop will be closed at this date');
	}else if(e[0] == 'no employee') {
		showAlert('warning', 'No employees available at this date');
	}
	});
}

function saveService(serviceID, price, e) {
	if(serviceID == 0) {
		 if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		return;
	}
	$('#tyreChangeDateTime').val('');
	$('#timeSlotsContainer').html('');
	$('#continueButton').attr('disabled', true);
	variables['time'] = '';
	variables['totalTime'] = 0;
	
	if(variables['serviceIDs'] == '') {
		variables['servicePrice'] += (price * variables['totalTyres']);
		//variables['price'] += (price * variables['totalTyres']);
		//$('#orderPrice').html(variables['price']);
		variables['serviceIDs'] = serviceID+','; 
		variables['serviceUnitPrice'] += price;
		var maxNum = parseInt($('#maxNum'+serviceID).find(':selected').val());
		variables['service'+serviceID] = price * maxNum;
		variables['price'] += variables['service'+serviceID];
		$('#orderPrice').html(variables['price']);
	}
	else {
		var present = 0;
		var IDs = variables['serviceIDs'].split(',');
		IDs.forEach(function (id) {
			if(id == serviceID) { present = 1; return; }
		});

		if(present == 0) {
			variables['serviceIDs'] += serviceID+',';
			variables['servicePrice'] += (price * variables['totalTyres']);
			//variables['price'] += (price * variables['totalTyres']);
			//$('#orderPrice').html(variables['price']);
			variables['serviceUnitPrice'] += price;
			
			var maxNum = parseInt($('#maxNum'+serviceID).find(':selected').val());
			variables['service'+serviceID] = price * maxNum;
			variables['price'] += variables['service'+serviceID];
			$('#orderPrice').html(variables['price']);
		}else {
			$('.service'+serviceID).removeClass('activeService');
			var newServiceIDs = variables['serviceIDs'].replace(serviceID+',', '');
			variables['serviceIDs'] = newServiceIDs;
		//	variables['price'] -= (price * variables['totalTyres']);
			variables['servicePrice'] -= (price * variables['totalTyres']);
			variables['serviceUnitPrice'] -= price;
			variables['price'] -= variables['service'+serviceID];
			$('#orderPrice').html(variables['price']);
			variables['service'+serviceID] = undefined;
			return;
		}
	}
	
	$('.service'+serviceID).addClass('activeService');
}

$('#buyTyreModal').on('show.bs.modal', function (e) {
	
	$('#continueButton').attr('disabled', true);
	
	if(variables['started'] != 1) {
		var button = $(e.relatedTarget);
		variables['price'] = parseInt(button.data('price'));
		variables['pricePerUnit'] = variables['price'];
		$('#orderPrice').html(variables['price']);
		variables['serviceIDs'] = '';
		variables['time'] = '';
		variables['totalTime'] = 0;
		variables['tyreID'] = parseInt(button.data('tyreid'));
		variables['servicePrice'] = 0;
		variables['totalTyres'] = 1;
		variables['serviceUnitPrice'] = 0;
		
		$('.servicesLoading').show(200);
		$('#servicesContainer').hide();
		$('.timeSlotsLoading').hide();
		$('#timeSlotsContainer').hide();
		
		var url = 'method=getServices';
		fetch(url, function(result) {
			var e = JSON.parse(result);
			if(e[0] == 'success') {
				$('.servicesLoading').hide();
				$('#servicesContainer').html(e[1]).show(200);
				variables['started'] = 1;
			}else {
				showAlert('danger', 'Some error occurred, inform admin');
			}
		});
	}
  
  
})

$('#continueButton').on('click', function() {
	var emptyField = 0;
	$('.inputMod').each(function() {
		if($(this).attr('id') == 'orgNr') { return true; }
		if($(this).val() == '') {
			//$(this).css('border', '1px solid red');
			emptyField = 1;
		}
	});
	if(emptyField == 1) {
		//showModal('Empty fields', 'All the fields are required');
		showAlert('danger', 'All fields are required');
		return;
	}
	
	$('#paymentOptionModal').css('z-index', '10030');
	$('.modal-backdrop').css('z-index', '10020');
	$('#paymentOptionModal').modal({
		show: true,
		backdrop: false
	}); 
	variables['paymentMode'] = 'payNow';
	variables['orgNr'] = '';
	
}); //tyreChangeOrder(); });

$('#paymentOptionModal').on('hide.bs.modal', function(e) {
	$('.modal-backdrop').css('z-index', '10000');
	$('#paymentOption').val('payNow');
	$('.orgNr').hide();
	$('#orgNr').val('');
	variables['orgNr'] = '';
	variables['paymentMode'] = 'payNow';
});

function tyreChangeOrder() {
	var emptyField = 0;
	$('.inputMod').each(function() {
		if($(this).attr('id') == 'orgNr') { return true; }
		if($(this).val() == '') {
			//$(this).css('border', '1px solid red');
			emptyField = 1;
		}
	});
	if(emptyField == 1) {
		//showModal('Empty fields', 'All the fields are required');
		showAlert('danger', 'All fields are required');
		return;
	}
	
	var regNr = variables['regNr'] = $('#regNr').val();
	var name = variables['name'] = $('#name').val();
	var mobile = variables['mobile'] = $('#mobile').val();
	var date = variables['date'] = $('#tyreChangeDateTime').val();
	var tyres = variables['tyres'] = $('#tyres').find(':selected').val();
	var email = variables['email'] = $('#email').val();
	var paymentMode = variables['paymentMode'];
	var orgNr = variables['orgNr'];
	
	var servicesURL = '';
	var IDs = variables['serviceIDs'].split(',');
	IDs.forEach(function (id) {
		if(id == '' || id == ' ' || id == 'undefined') { return true; }
		servicesURL += '&service'+id+'='+variables['service'+id];
	});
	
	showLoadingBar();
	var url = 'method=tyreOrderWithoutLogin&paymentDone=0&orgNr='+variables['orgNr']+'&paymentMode='+paymentMode+'&tyreID='+variables['tyreID']+'&email='+email+'&totalTime='+variables['totalTime']+'&workType=newTyre&price='+variables['price']+'&regNr='+regNr+'&name='+name+'&mobile='+mobile+'&date='+date+'&serviceIDs='+variables['serviceIDs']+'&time='+variables['time']+'&tyres='+tyres+servicesURL;
	fetch(url, function(result) {
		var e = $.parseJSON(result);
		if(e[0] == 'failed') {
			showAlert('danger','Technical error, contact admin');
		}else if(e[0] == 'paySessionSuccess') {
			var token = e[1]; 
			var url = e[2];
			showPaymentModal(token, url);
		}else if(e[0] == 'empty fields') {
			showAlert('danger','All fields are required');
		}else if(e[0] == 'already ordered') {
			showAlert('danger','This Reg Nr is already under process');
		}else if(e[0] == 'no work') {
			showAlert('danger','This work has not been assigned');
		}else if(e[0] == 'no employee') {
			showAlert('danger','No employee available at this time');
		}else if(e[0] == 'api error') {
			showAlert('danger', 'API error, contact admin');
		}else if(e[0] == 'success') {
			// mode=orgNr || payAtShop
			//showAlert('success', 'Successfully placed your order');
			showAlert('success', 'Bestillingen din er nå mottatt og registrert. Du vil snart få e-mail med bekreftelse.');
			$('.modal').modal('hide');
			variables = [];
			location.reload(true);
			
		}
		
		if(e[0] != 'paySessionSuccess') { hideLoadingBar(); }
	});
	
}

$('#buyTyreModal').on('hidden.bs.modal', function (e) {
  
  $('#regNr').val('');
  $('#name').val('');
  $('#mobile').val('');
  $('#email').val('');
  $('#tyreChangeDateTime').val('');
  $('#servicesContainer').html('');
  $('#timeSlotsContainer').html('');
  $('#orderPrice').html('');
  $('#continueButton').attr('disabled', true);
  variables = [];
  
})

$('#tyres').on('change', function() {
	var tyres = parseInt($(this).find(':Selected').val());
	variables['totalTyres'] = tyres;
	variables['servicePrice'] = variables['serviceUnitPrice'] * tyres;
	//var totalPrice = (variables['pricePerUnit'] * tyres) + variables['servicePrice'];
	
	var totalServicePrice = 0;
	var IDs = variables['serviceIDs'].split(',');
	IDs.forEach(function (id) {
		if(id == '' || id == ' ' || id == undefined) { return true; }
		totalServicePrice += parseInt(variables['service'+id]);
	});
	
	var totalPrice = (variables['pricePerUnit'] * tyres) + totalServicePrice;
	variables['price'] = totalPrice;
	$('#orderPrice').html(totalPrice);
});

function saveMaxNum(i, price) {
	var maxNum = $('#maxNum'+i).find(':selected').val();
	
	var present = 0;
	var IDs = variables['serviceIDs'].split(',');
	IDs.forEach(function (id) {
		if(id == i) { present = 1; return; }
	});
	
	if(isset(variables['service'+i]) && present == 1) {
		variables['price'] -= variables['service'+i];
		variables['service'+i] = maxNum * price;
		//variables['servicePrice'] += price * maxNum;
		variables['price'] += variables['service'+i];
		$('#orderPrice').html(variables['price']);
	}else {
		variables['service'+i] = undefined;
	}
};

$('#paymentOption').on('change', function(e) {
	var paymentMode = $(this).find(':selected').val();
	$('.orgNr').hide(200);
	if(paymentMode == 'orgNr') {
		variables['paymentMode'] = 'orgNr';
		// show orgNr input and verify
		$('.orgNr').show(200);
	}else if(paymentMode == 'payAtShop') {
		// skip bambora.. save order details 
		variables['paymentMode'] = 'payAtShop';
	}else if(paymentMode == 'payNow') {
		//proceed normally.. call tyreChangeOrder()
		variables['paymentMode'] = 'payNow';
	}
	
});

$('#paymentOptionContinue').on('click', function(e) {
	var mode = variables['paymentMode'];
	$('#paymentOptionModal').css('z-index', '10010');
	$('.modal-backdrop').css('z-index', '10000');
	if(mode == 'payNow') {
		//call tyreChangeOrder()
		tyreChangeOrder();
	}else if(mode == 'payAtShop') {
		// call tyreChangeOrder() with mode=payAtShop and skip payment
		tyreChangeOrder();
	}else if(mode == 'orgNr') {
		//call verifyOrgNr() and continue without payment by calling tyreChangeOrder with mode=orgNr
		var orgNr = $('#orgNr').val();
		if(orgNr == '') {
			showAlert('danger', 'Organisation number required');
			return;
		}
		
		verifyOrgNr(orgNr);

	}
});

function verifyOrgNr(orgNr) {
	$('#paymentOptionModal').css('z-index', '10010');
	$('.modal-backdrop').css('z-index', '10000');
	showLoadingBar();
	var url = 'method=verifyOrgNr&orgNr='+orgNr;
	fetch(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			$('#paymentOptionModal').modal('hide');
			variables['paymentMode'] = 'orgNr';
			variables['orgNr'] = orgNr;
			tyreChangeOrder();
		}else if(e[0] == 'incorrect') {
			showAlert('danger', 'Incorrect organisation number');
		}else {
			showAlert('danger', 'Error verfying organisation number');
		}
		
		if(e[0] != 'success') {
			$('#paymentOptionModal').css('z-index', '10030');
			$('.modal-backdrop').css('z-index', '10020');
		}
	});
	
	
}


</script>
