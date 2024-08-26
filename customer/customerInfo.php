<?php

$con = dbCon();
$customerID = $_SESSION['customerID'];

$tr = '';
$i = 1;
$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE id = $customerID");
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		$button = '<button class="btn btn-sm btn-warning text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;		';
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['fullName'].'</span>
					<input type="text" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['fullName'].'" id="fullName'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span>'.$f['username'].'</span></td>';
		$tr .= '<td title="'.$f['password'].'"><input type="password" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="" id="password'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['email'].'</span>
					<input type="email" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['email'].'" id="email'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['mobile'].'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['mobile'].'" id="mobile'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['regNr'].'</span></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['postCode'].'</span>
					<input type="text" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['postCode'].'" id="postCode'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['address'].'</span>
					<input type="text" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['address'].'" id="address'.$f['id'].'" style="display:none; font-weight:bold; max-width:200px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['city'].'</span>
					<input type="text" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['city'].'" id="city'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td>'.$button.'</td>';
		$tr .= '</tr>';
		$i++;
		continue;
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
}

$totalStock = $totalStockValue = 0;
$q = mysqli_query($con, "SELECT * FROM shop_stock WHERE stock > 0"); 
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		$totalStock += $f['stock'];
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['purchasePrice'])) {
				$totalStockValue += $f['stock'] * (int)$misc['purchasePrice']; //(int)$misc['purchasePrice'];
			}
		}
	}
}

?>
<div style="">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Customer Info
			</div>
			<div class="card-body">
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
						  <th scope="col">Full Name</th>
						  <th scope="col">Username</th>
						  <th scope="col">Password</th>
						  <th scope="col">Email</th>
						  <th scope="col">Mobile</th>
						  <th scope="col">Reg Nr</th>
						  <th scope="col">PostCode</th>
						  <th scope="col">Address</th>
						  <th scope="col">City</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="customerBody">
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
	//var username = $('#username'+i).val();
	var password = $('#password'+i).val();
	var fullName = $('#fullName'+i).val();
	var email = $('#email'+i).val();
	var mobile = $('#mobile'+i).val();
	//var regNr = $('#regNr'+i).val();
	var postCode = $('#postCode'+i).val();
	var address = $('#address'+i).val();
	var city = $('#city'+i).val();
	
	
	showLoadingBar(1);
	var url = 'method=saveCustomerInfo&id='+i+'&password='+password+'&fullName='+fullName+'&email='+email+'&mobile='+mobile+'&postCode='+postCode+'&address='+address+'&city='+city;
	fetchC(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully saved the changes');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'no customer') {
			showAlert('danger', 'Customer not found');
		}else {
			showAlert('danger', 'Technical error, contact developer');
		}
	});
}



</script>