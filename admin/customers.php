<div style="">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Customers 
					<button class="btn btn-outline-primary show" id="showAllCustomerButton" type="button">Show all customers</button>
					<button class="btn btn-outline-primary show" id="smsSelectedCustomerButton" type="button">SMS to selected customers</button>	
			</div>
			<div class="card-body">
				<div class="row" style="">
					<div class="input-group input-group-sm col-3" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
						</div>
						<input type="text" class="form-control" id="fullName" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="fullName">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
						</div>
						<input type="text" class="form-control" id="email" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="email">Search</button>
						</div>
					</div>
					<div class="input-group input-group-sm col-3" style="">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Mobile</span>
						</div>
						<input type="text" class="form-control" id="mobile" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="mobile">Search</button>
						</div>
					</div>
					<div class="input-group input-group-sm col-3" style="">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Reg Nr</span>
						</div>
						<input type="text" class="form-control" id="regNr" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="regNr">Search</button>
						</div>
					</div>
					<div class="filterButtons">
					    Filter Result Buttons: <br>
					    <button class="btn btn-outline-primary show" id="blueButton" onclick="hideNoneRows()" style="font-weight:bold;">Show all tyres</button>
					    <button class="btn btn-outline-primary show" id="greenButton" onclick="hideNotStoredRows()">Show stored tyres</button>	
					    <button class="btn btn-outline-primary show" id="redButton" onclick="hideStoredRows()">Show not stored tyres</button>
					</div>

				<!--
				<h5 class="card-title text-center">List of all products</h5>
				<div class="container-fluid">
					<div class="row px-0">
					<div class="col">
						<nav aria-label="products">
							<ul class="pagination pagination-sm justify-content-end">
								<li class="page-item disabled">
									<a class="page-link" href="#" tabindex="-1">Previous</a>
								</li>
								<li class="page-item"><a class="page-link" href="#">1</a></li>
								<li class="page-item active">
									<a class="page-link" href="#">2</a>
								</li>
								<li class="page-item"><a class="page-link" href="#">3</a></li>
								<li class="page-item"><a class="page-link" href="#">Next</a></li>
							</ul>
						</nav>
					</div>
					</div>
				</div>
				-->
				
				<table class="table table-hover table-sm" style="margin-top:30px; font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col"># Total </th>
						  <th scope="col">Location</th>	
						  <th scope="col"># Stored status</th>
						  <th scope="col">Name</th>
						  <th scope="col">Username</th>
						  <th scope="col">Password</th>
						  <th scope="col">Email</th>
						  <th scope="col">Mobile</th>
						  <th scope="col">Reg Nr</th>
						  <th scope="col">PostCode</th>
						  <th scope="col">Addresss</th>
						  <th scope="col">City</th>
						  <th scope="col">Price</th>
						  <th scope="col">Car Type</th>
						  <th><span class="selectAllCustomers" style="cursor: pointer; text-decoration: underline;">SMS</span> Edit Delete</th>
						</tr>
					  </thead>
					  <tbody id="customersBody">
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
		<!-- Form for SMS data -->
        <div class="sms-popup" id="smsPopup">
            <div class="sms-popup-content">
                <span class="sms-close-button" id="closeSmsPopup">&times;</span>
                <p>Send SMS to: <span id="smsHeader"></span></p>
                <form id="smsForm">
                    <input type="hidden" id="mobileValue" value="">
                    <!-- Lägg till input för Sender Name -->
                    <div class="form-group">
                        <label for="senderName">Sender Name (max 11 char)</label>
                        <input type="text" class="form-control" id="senderName" value="Mossdekk">
                    </div>
                    <textarea id="smsMessage" class="form-control" placeholder="Write your SMS here..."></textarea>
                    <button type="submit" id="sendSms">Send SMS</button>
                </form>
            </div>
        </div>
</div>

<script>




function closeSmsPopup() {
    document.getElementById("smsPopup").style.display = "none";
}

document.getElementById("closeSmsPopup").addEventListener("click", function() {
  closeSmsPopup();
});

function sendSMS(number) {
    var smsHeader = document.getElementById("smsHeader");
    smsHeader.innerHTML = '';
    var spanElement = document.createElement("span");
    spanElement.textContent = number;
    smsHeader.appendChild(spanElement);

    document.getElementById("smsPopup").style.display = "block";

    smsForm.addEventListener("submit", function(event) {
        event.preventDefault();
        var smsMessage = document.getElementById("smsMessage").value;
        var mobileNumber = [number]; // Konvert to array 
        var sender = document.getElementById("senderName").value;
        
        showLoadingBar(1);

        var xhr = new XMLHttpRequest();
        var url = 'sendSms.php';
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                
                if (xhr.status === 200) {
                    if (response.status === 200) {
                        showAlert('success', 'Successfully sent SMS');
                        closeSmsPopup();
                    } else {
                        var errorMessage = JSON.stringify(response.result, null, 2); 
                        showAlert('danger', 'Error: ' + errorMessage);
                    }
                } else {
                        showAlert('danger', 'An error occurred while sending SMS. HTTP Status: ' + xhr.status);
                    }
                
                hideLoadingBar();
            }
        };
        
        var jsonData = {
            mobileNumber: mobileNumber,
            smsMessage: smsMessage,
            sender: sender
        };
        
        xhr.send(JSON.stringify(jsonData));
        hideLoadingBar();
    });
}




function edit(i) {
	$('.txtField'+i).hide();
	$('.editField'+i).show();
	$('.editButton'+i).hide();
	$('.saveButton'+i).show();
}

function save(i) {
	var fullName = $('#fullName'+i).val();
	var username = $('#username'+i).val();
	var password = $('#password'+i).val();
	var email = $('#email'+i).val();
	var mobile = $('#mobile'+i).val();
	var regNr = $('#regNr'+i).val();
	var postCode = $('#postCode'+i).val();
	var address = $('#address'+i).val();
	var city = $('#city'+i).val();
	var price = $('#price'+i).val();
	var carType = $('#carType'+i).val();
	
	
	showLoadingBar(1);
	var url = 'method=saveCustomerInfo&fullName='+fullName+'&id='+i+'&username='+username+'&password='+password+'&email='+email+'&mobile='+mobile+'&regNr='+regNr+'&postCode='+postCode+'&address='+address+'&city='+city+'&price='+price+'&carType='+carType;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully saved the changes');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'Entries not found');
		}else {
			showAlert('danger', 'Technical error, contact developer');
		}
	});
}

$('.search').on('click', function() {
	var type = $(this).data('type');
	var value = $('#'+type).val();
	if(value == '') { 
		showAlert('warning', 'Field cannot be left empty');
		return;
	}
	
	showLoadingBar(1);
	var url='method=customerSearch&type='+type+'&value='+value;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#customersBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});



$('#showAllCustomerButton').on('click', function() {
	showLoadingBar(1);
	var url='method=showAllCustomer';
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#customersBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});
//SMS to selected
$('#smsSelectedCustomerButton').on('click', function() {
    var selectedMobileNumbers = [];
    
    // Loop through each customer checkbox
    $('.customerCheckbox:checked').each(function() {
        var mobileNumber = $(this).data('mobile');
        if (mobileNumber && !selectedMobileNumbers.includes(mobileNumber)) {
            selectedMobileNumbers.push(mobileNumber);
        }
    });

    if (selectedMobileNumbers.length === 0) {
        showAlert('danger', 'No records selected');
        return;
    }

    var numSelectedCustomers = selectedMobileNumbers.length;
    var smsHeader = document.getElementById("smsHeader");
    smsHeader.innerHTML = '';
    var spanElement = document.createElement("span");
    spanElement.innerHTML = "Selected Customers (" + numSelectedCustomers + ")<br> Warning: This will send SMS to multiple Customers.";
    smsHeader.appendChild(spanElement);

    document.getElementById("smsPopup").style.display = "block";

    smsForm.addEventListener("submit", function(event) {
        event.preventDefault();
        var smsMessage = document.getElementById("smsMessage").value;
        var sender = document.getElementById("senderName").value;

        showLoadingBar(1);
        
        var xhr = new XMLHttpRequest();
        var url = 'sendSms.php';
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = JSON.parse(xhr.responseText);
                
                if (xhr.status === 200) {
                    if (response.status === 200) {
                        showAlert('success', 'Successfully sent SMS');
                        closeSmsPopup();
                    } else {
                        var errorMessage = JSON.stringify(response.result);
                        showAlert('danger', 'Error: ' + errorMessage);
                    }
                } else {
                        showAlert('danger', 'An error occurred while sending SMS. HTTP Status: ' + xhr.status);
                    }
                
                hideLoadingBar();
            }
        };
        
        var jsonData = {
            mobileNumber: selectedMobileNumbers,
            smsMessage: smsMessage,
            sender: sender
        };
        
        xhr.send(JSON.stringify(jsonData));
        hideLoadingBar();
    });
});


function hideNotStoredRows() {
    var table = document.getElementById("customersBody");
    var rows = table.getElementsByTagName("tr");
    var blueButton = document.getElementById("blueButton");
    var greenButton = document.getElementById("greenButton");
    var redButton = document.getElementById("redButton");

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var redCircle = row.getElementsByClassName("redcircle");

        if (redCircle.length > 0) {
            row.style.display = "none";

            // Uncheck the checkbox when the row is hidden
            var checkbox = row.querySelector(".customerCheckbox");
            if (checkbox) {
                checkbox.checked = false;
            }
        } else {
            row.style.display = "table-row";
        }
    }

    blueButton.style.fontWeight = "normal";
    blueButton.style.boxShadow = "none";
    greenButton.style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 0, 0, 0.2)";
    greenButton.style.fontWeight = "bold";
    redButton.style.fontWeight = "normal";
    redButton.style.boxShadow = "none";
}


function hideNoneRows() {
  var table = document.getElementById("customersBody");
  var rows = table.getElementsByTagName("tr");
  var blueButton = document.getElementById("blueButton");
  var greenButton = document.getElementById("greenButton");
  var redButton = document.getElementById("redButton");
  
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    row.style.display = "table-row";
    
  }
    blueButton.style.fontWeight = "bold";
    blueButton.style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 0, 0, 0.2)";
    greenButton.style.boxShadow = "none";
    greenButton.style.fontWeight = "normal";
    redButton.style.fontWeight = "normal";
    redButton.style.boxShadow = "none";
}
function hideStoredRows() {
  var table = document.getElementById("customersBody");
  var rows = table.getElementsByTagName("tr");
  var blueButton = document.getElementById("blueButton");
  var greenButton = document.getElementById("greenButton");
  var redButton = document.getElementById("redButton");
  
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    var redCircle = row.getElementsByClassName("greencircle");

    if (redCircle.length > 0) {
      row.style.display = "none";
        // Uncheck the checkbox when the row is hidden
        var checkbox = row.querySelector(".customerCheckbox");
        if (checkbox) {
                checkbox.checked = false;
        }
    } else{
    row.style.display = "table-row";
    }
  }
    blueButton.style.fontWeight = "normal";
    blueButton.style.boxShadow = "none";
    greenButton.style.boxShadow = "none";
    greenButton.style.fontWeight = "normal";
    redButton.style.fontWeight = "bold";
    redButton.style.boxShadow = "0 4px 10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 0, 0, 0.2)";
}

function deleteCustomer(id) {
	var conf = confirm('Customer will be permanantly delete, are you sure?');
	if(conf === false) {
		return;
	}
	
	deleteRow('customers', id, 1);
}
$(document).ready(function() {
    var selectAllClicked = false;

    // Mark only visible checkboxes when the "SMS" span is clicked
    $('.selectAllCustomers').click(function() {
        selectAllClicked = !selectAllClicked; // Toggle the flag

        $('.customerCheckbox').each(function() {
            var row = $(this).closest('tr');
            if (row.is(':visible')) {
                $(this).prop('checked', selectAllClicked);
            }
        });
    });

});

</script>