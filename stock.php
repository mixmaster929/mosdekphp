<?php

$con = dbCon();

$tr = '';
$i = 1;
$q = mysqli_query($con, "SELECT * FROM shop_stock");
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		$tyreID = $f['tyreID'];
		
		$purchasePrice = 0;
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['purchasePrice'])) {
				$purchasePrice = $misc['purchasePrice'];
			}
		}
		
		$ft = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID"), MYSQLI_ASSOC);
		$brand = $ft['brand'];
		$model = $ft['model'];
		$tyreSize = $ft['size'];
		$productDesc = $brand.' '.$model.' - '.$tyreSize;
		
		$button = '<button class="btn btn-sm btn-warning text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;		';
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td style="max-width:200px;">'.$productDesc.'</td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['stock'].'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['stock'].'" id="stock'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$purchasePrice.'</span>		
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$purchasePrice.'" id="purchasePrice'.$f['id'].'" style="display:none; font-weight:bold; max-width:100px;"></td>';
        $tr .= '<td><span class="txtField'.$f['id'].'">'.$f['delay'].'</span>
                    <input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['delay'].'" id="delay'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
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
				Product Stock 
			</div>
			<div class="card-body">
				<div class="row" style="">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Brand/Model</span>
						</div>
						<input type="text" class="form-control" id="brandModel" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="brandModel">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Tyre Size</span>
						</div>
						<input type="text" class="form-control" id="tyreSize" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="tyreSize">Search</button>
						</div>
					</div>
					
					<button class="btn btn-sm btn-outline-success py-0 " onclick="showStock('in')" style="margin-right:10px;" >In stock</button>
					<button class="btn btn-sm btn-outline-danger py-0 m-0 " onclick="showStock('out')" >Out of stock</button>
					<div class="input-group input-group-sm col-4" style="display:none;">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Model</span>
						</div>
						<input type="text" class="form-control" id="model" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="model">Search</button>
						</div>
					</div>
					
				</div>
				<hr>
				<div class="row">
					<div class="col-6 col-lg-auto" style="">Total tyres in stock: <span style="font-weight:600;"><?php echo $totalStock; ?></span>
					</div><div class="col-6 col-lg-auto" style="">Total value of stock: <span style="font-weight:600;"><?php echo $totalStockValue; ?></span>
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
						  <th scope="col">Product Desc</th>
						  <th scope="col">In Stock</th>
						  <th scope="col">Purchase Price</th>
						  <th scope="col">Delay Date</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="stockBody">
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>

<script>

function seen(id) {
	var url = 'method=querySeen&id='+id;
	showLoadingBar(1);
	fetchA(url, function(e) {
		hideLoadingBar();
		if(e == 'success') {
			$('#tr'+id).hide();
		}else {
			showAlert('danger', 'Some error occurred');
		}
	});
}

function showStock(type) {
	var url = 'method=showStock&type='+type;
	showLoadingBar(1);
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Entries found');
			$('#stockBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else if(e[0] == 'invalid type') {
			showAlert('danger', 'Invalid type');
		}else {
			showAlert('danger', 'Unknown error, contact developer');
		}
	});
}

function edit(i) {
	$('.txtField'+i).hide();
	$('.editField'+i).show();
	$('.editButton'+i).hide();
	$('.saveButton'+i).show();
}

function save(i) {
	var stock = $('#stock'+i).val();
	var delay = $('#delay'+i).val();
	if(stock == '' || stock == ' ') { stock = 0; }
	var purchasePrice = $('#purchasePrice'+i).val();
	
	
	showLoadingBar(1);
	var url = 'method=saveStockInfo&stock='+stock+'&delay='+delay+'&id='+i+'&purchasePrice='+purchasePrice;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully saved the changes');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'no tyre') {
			showAlert('danger', 'Tyre not found');
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
	var url='method=stockSearch&type='+type+'&value='+value;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#stockBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});

</script>