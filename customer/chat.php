<?php

?>

<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Tyre Change History
			</div>
			<div class="card-body">
				<div class="row">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Change Date</span>
						</div>
						<input type="text" class="form-control" id="purchaseDate" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="purchaseDate" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Brand/Modal</span>
						</div>
						<input type="text" class="form-control" id="brandModal" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary search" data-type="brandModal" type="button">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4" style="display:none;">
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
						  <th scope="col">Reg Nr</th>
						  <th scope="col">Change Date</th>
						  <th scope="col">Changed By</th>
						  <th scope="col">Comments</th>
						  <th scope="col">Pictures</th>
						  <th scope="col">Products Used</th>
						  <th scope="col">Time Taken</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="tyreChangeBody">
						<?php // echo $tr; ?>
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

</script>