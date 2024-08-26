<?php
/*
$con = dbCon();

$tr = '';
$i = 1;
$q = mysqli_query($con, "SELECT * FROM shop_orders");
if(mysqli_num_rows($q) > 0) {
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		$status = '';


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
				<td>'.$f['changeDate'].'</td>
				<td>'.$status.'</td>
				<td></td>
				</tr>';
		$i++;
	}
}
*/
?>

<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Customer Orders
			</div>
			<div class="card-body">
				<div class="row">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
						</div>
						<input type="text" class="form-control" id="name" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="name" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Reg Nr</span>
						</div>
						<input type="text" class="form-control" id="regNr" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="regNr" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Change Date</span>
						</div>
						<select class="custom-select" id="date">
							<option selected>Choose date</option>
							<option value="1">25/12/2022</option>
							<option value="2">Two</option>
							<option value="3">Three</option>
						</select>
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="date" type="button">Search</button>
						</div>
					</div>
					
				</div>
				<button class="btn btn-sm btn-outline-primary py-0 " onclick="showAllOrders()" style="margin:10px 10px 0px 0px;">View complete list</button>
				<hr>
				
				
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
						  <th scope="col">Reg Nr</th>
						  <th scope="col">Mobile</th>
						  <th scope="col">Brand</th>
						  <th scope="col">Size</th>
						  <th>Tyres</th>
						  <th scope="col">Price</th>
						  <th scope="col">Payment Mode</th>
						  <th scope="col">Change Date</th>
						  <th scope="col">Status</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="orderBody">
						<?php // echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>



<script>

function showAllOrders() {
	var url = 'method=showAllOrder';
	showLoadingBar(1);
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Found entries');
			$('#orderBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error, contact developer');
		}
	});
}

$('.search').on('click', function() {
	var type = $(this).data('type');
	var value = $('#'+type).val();
	if(value == '') { 
		showModal('Empty Field', 'Field cannot be left empty');
		return;
	}
	
	showLoadingBar(1);
	var url='method=orderSearch&type='+type+'&value='+value;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#orderBody').html(e[1]);
		}else if(e[0] == 'no entry') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});

</script>