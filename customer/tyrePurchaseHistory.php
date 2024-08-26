<?php

$customerID = (int)$_SESSION['customerID'];

$tr = '';
$q = mysqli_query($con, "SELECT * FROM shop_orders WHERE customerID = $customerID");
if(mysqli_num_rows($q) > 0) {
	
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			$model = $misc['model'];
			$runFlat = $misc['runFlat'];
			$load = $misc['load'];
			$category = $misc['category'];
			
			if($misc['season'] == 'summer') { $season = 'SommerDekk'; }
			else if($misc['season'] == 'winter') { $season = 'Winter-Piggfrie'; }
			else if($misc['season'] == 'winterStudded') { $season = 'Winter-PiggDekk'; }
		
		}
		
		$tr .= '<tr>';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>'.$f['orderedOn'].'</td>';
		$tr .= '<td>'.$f['tyres'].'</td>';
		$tr .= '<td>'.$f['size'].'</td>';
		$tr .= '<td>'.$f['brand'].'</td>';
		$tr .= '<td>'.$model.'</td>';
		$tr .= '<td>'.$season.'</td>';
		$tr .= '<td>'.$runFlat.'</td>';
		$tr .= '<td>'.$load.'</td>';
		$tr .= '<td>'.$category.'</td>';
		$tr .= '<td>'.$f['price'].'</td>';
		$tr .= '</tr>';
		
	}
}

?>

<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Tyre Purchase History
			</div>
			<div class="card-body">
				<div class="row">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Purchase Date</span>
						</div>
						<input type="text" class="form-control" id="purchaseDate" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="purchaseDate" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Brand</span>
						</div>
						<input type="text" class="form-control" id="brand" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="brand" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4" style="">
						<button class="btn btn-sm btn-outline-primary  " onclick="showAllOrders()" style="">View complete list</button>
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
						  <th scope="col">Purchase Date</th>
						  <th scope="col">Nr of Tyres</th>
						  <th scope="col">Tyre Size</th>
						  <th scope="col">Brand</th>
						  <th scope="col">Model</th>
						  <th scope="col">Season</th>
						  <th scope="col">Runflat</th>
						  <th scope="col">Load</th>
						  <th scope="col">Category</th>
						  <th scope="col">Price</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="orderBody">
						<?php  echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>



<script>


$('.search').on('click', function() {
	var type = $(this).data('type');
	var value = $('#'+type).val();
	if(value == '') { 
		showAlert('danger', 'Field cannot be left empty');
		return;
	}
	
	showLoadingBar(1);
	var url='method=orderSearchForCustomer&type='+type+'&value='+value;
	fetchC(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no customer') {
			showAlert('danger', 'You are not logged in');
			location.reload(true);
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#orderBody').html(e[1]);
		}else if(e[0] == 'no entry') {
			showAlert('danger', 'No entries found');
		}else if(e[0] == 'empty') {
			showAlert('danger', 'Field cannot be left empty');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});

function showAllOrders() {
	showLoadingBar(1);
	var url = 'method=showAllPurchaseOrders';
	fetchC(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#orderBody').html(e[1]);
		}else if(e[0] == 'no entry') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error, contact admin');
		}
	});
}

</script>