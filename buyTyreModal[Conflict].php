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
        <button type="button" class="btn btn-primary" id="continueButton">Continue</button>
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
		
		var payURL = '&amount='+payVariables['amount']+'&cardNo='+payVariables['cardNo']+'&currency='+payVariables['currency']+'&payDate='+payVariables['date']+'&eci='+payVariables['eci']+'&feeID='+payVariables['feeID']+'&hash='+payVariables['hash']+'&issuerCountry='+payVariables['issuerCountry']+'&orderID='+payVariables['orderID']+'&paymentType='+payVariables['paymentType']+'&reference='+payVariables['reference']+'&payTime='+payVariables['time']+'&txnID='+payVariables['txnID'];
		
		var url = 'method=tyreOrderWithoutLogin&paymentDone=1&tyreID='+variables['tyreID']+'&totalTime='+variables['totalTime']+'&workType=tyreChange&price='+variables['price']+'&regNr='+variables['regNr']+'&name='+variables['name']+'&mobile='+variables['mobile']+'&date='+variables['date']+'&serviceIDs='+variables['serviceIDs']+'&time='+variables['time']+'&tyres='+variables['tyres']+'&email='+variables['email']+payURL;
		fetch(url, function(result) {
			bamboraNewInstance(1);
			hideLoadingBar();
			var e = JSON.parse(result);
			if(e[0] == 'failed') {
				showAlert('danger', 'Technical error, contact admin');
			}else if(e[0] == 'success') {
				showAlert('success', 'Successfully placed your order');
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

function saveService(serviceID, price) {
	$('#tyreChangeDateTime').val('');
	$('#timeSlotsContainer').html('');
	$('#continueButton').attr('disabled', true);
	variables['time'] = '';
	variables['totalTime'] = 0;
	
	if(variables['serviceIDs'] == '') { 
		variables['servicePrice'] += price
		variables['price'] += price;
		$('#orderPrice').html(variables['price']);
		variables['serviceIDs'] = serviceID+','; 
	}
	else {
		var present = 0;
		var IDs = variables['serviceIDs'].split(',');
		IDs.forEach(function (id) {
			if(id == serviceID) { present = 1; return; }
		});

		if(present == 0) {
			variables['serviceIDs'] += serviceID+',';
			variables['price'] += price;
			$('#orderPrice').html(variables['price']);
		}else {
			$('.service'+serviceID).removeClass('activeService');
			var newServiceIDs = variables['serviceIDs'].replace(serviceID+',', '');
			variables['serviceIDs'] = newServiceIDs;
			variables['price'] -= price;
			$('#orderPrice').html(variables['price']);
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

$('#continueButton').on('click', function() { tyreChangeOrder(); });

function tyreChangeOrder() {
	var emptyField = 0;
	$('.inputMod').each(function() {
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
	
	showLoadingBar();
	var url = 'method=tyreOrderWithoutLogin&paymentDone=0&tyreID='+variables['tyreID']+'&email='+email+'&totalTime='+variables['totalTime']+'&workType=newTyre&price='+variables['price']+'&regNr='+regNr+'&name='+name+'&mobile='+mobile+'&date='+date+'&serviceIDs='+variables['serviceIDs']+'&time='+variables['time']+'&tyres='+tyres;
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
	var totalPrice = (variables['pricePerUnit'] * tyres) + variables['servicePrice'];
	variables['price'] = totalPrice;
	$('#orderPrice').html(totalPrice);
});

</script>
