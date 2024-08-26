<?php

$con = dbCon();
$tr = '';
$regNr = '';

// $url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
// $postData= [
//     'method' => 'tableCopy'
//     ];
// $response = get_web_page($url, $postData);
// $resArr = json_decode($response);

// if(is_object($resArr))
// {
//     foreach($resArr -> fetchData as $f)
//     {
//     	$q = mysqli_query($con, "INSERT INTO shop_customers (`id`,`createdOn`, `username`,`fullName`,`password`,`email`,`regNr`, `mobile`, `postCode`,`address`,`city`,`misc`) VALUES (NULL,'', '$f->username', '$f->firstName', '$f->password','$f->email','$f->regNr','$f->mobile','$f->postCode','$f->address','','')");

//     }
// }
$customerID = $_SESSION['customerID'];
$f = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_customers WHERE id=$customerID"), MYSQLI_ASSOC);
$regNrs = $f['regNr'];
$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
$postData = [
	'method' => 'getDekkhotellTyres',
	'regNr' => $regNrs
	];
$response = get_web_page($url, $postData);
$resArr = json_decode($response);


if(is_object($resArr)) {

	foreach($resArr->fetchData as $f) {
		$tr .= '<tr id="tr">';
		$tr .= '<td>'.$f->filed.'</td>';
		$tr .= '<td>'.$f->department.'</td>';
		$tr .= '<td>'.$f->regNr.'</td>';
		$tr .= '<td>'.$f->location.'</td>';
		$tr .= '<td>'.$f->tyreSize.'</td>';
		$tr .= '<td>'.$f->pattern.'</td>';
		$tr .= '<td>'.$f->season.'</td>';
		$tr .= '<td>'.$f->washed.'</td>';
		$tr .= '<td>'.$f->condition.'</td>';
		$tr .= '<td style="max-width:100px;">'.$f->ordered.'</td>';
		$tr .= '</tr>';
	}
}
?>

<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Dekkhotell
			</div>
			<div class="card-body">
				<!-- <div class="row">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Reg Nr</span>
						</div>
						<input type="text" class="form-control" id="regNr" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="regNr" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4" style="">
						<button class="btn btn-sm btn-outline-primary  " onclick="showAllDekkhotelTyres()" style="">View complete list</button>
					</div>
					
					
				</div>
				 -->
				
				
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
						  
						  <th scope="col">Innlevert</th>
						  <th scope="col">Avdeling</th>
						  <th scope="col">Reg Nr</th>
						  <th scope="col">Lokasjon</th>
						  <th scope="col">Dekk Str</th>
						  <th scope="col">MØnster Dybde</th>
						  <th scope="col">Season</th>
						  <th scope="col">Vasket</th>
						  <th scope="col">Tilstand</th>
						  <th scope="col">Order Status</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="dekkhotellBody">
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>



<script>

function showAllDekkhotelTyres() {
	showLoadingBar(1);
	var url = 'method=showAllDekkhotelTyres';
	fetchC(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		
		if(e[0] == 'success') {
			$('#dekkhotellBody').html(e[1]);
		}else if(e[0] == 'no entry') {
			showAlert('warning', 'No tyres stored in dekkhotell');
		}else {
			showAlert('danger', 'Technical error, contact admin');
		}
		
	});
}

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

</script>