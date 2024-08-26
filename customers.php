<?php

$con = dbCon();

$tr = '';
$i = 1;
$q = mysqli_query($con, "SELECT * FROM shop_customers");
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		

		
		$button = '<button class="btn btn-sm btn-warning text-white py-0 m-1 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-1 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;
					<button class="btn btn-sm btn-danger text-white py-0 m-1 deleteButton'.$f['id'].' button'.$f['id'].'" onclick="deleteCustomer('.$f['id'].')">Delete</button>';
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['fullName'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['fullName'].'" id="fullName'.$f['id'].'" style="display:none;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['username'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['username'].'" id="username'.$f['id'].'" style="display:none;"></td>';
		$tr .= '<td title="'.$f['password'].'"><input type="password" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="" id="password'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['email'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['email'].'" id="email'.$f['id'].'" style="display:none;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['mobile'].'</span><input class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['mobile'].'" id="mobile'.$f['id'].'" style="display:none;"></td>';
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
}


?>
<div style="">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Customers
			</div>
			<div class="card-body">
				<div class="row" style="">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
						</div>
						<input type="text" class="form-control" id="fullName" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="fullName">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
						</div>
						<input type="text" class="form-control" id="email" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="email">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4" style="">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Reg Nr</span>
						</div>
						<input type="text" class="form-control" id="regNr" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="regNr">Search</button>
						</div>
					</div>
					
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
						  <th scope="col">#</th>
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
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="customersBody">
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>

<script>

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

function deleteCustomer(id) {
	var conf = confirm('Customer will be permanantly delete, are you sure?');
	if(conf === false) {
		return;
	}
	
	deleteRow('customers', id, 1);
}

</script>