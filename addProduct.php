<?php

	$con = dbCon();
	$q = mysqli_query($con, "SELECT * FROM shop_brand");
	if(mysqli_num_rows($q) > 0) {
	
		$i = 1;
		$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);		
	}


	$no = '';
	for($i=1; $i <= 50; $i++) {
		$no .= '<option value='.$i.'>'.$i.'</option>';
	}
	
	$bSelect = $mSelect = $pSelect = '';
		if($fetch[0]['category'] == 'budget') { $bSelect = 'selected'; }
		else if($fetch[0]['category'] == 'mellom') { $mSelect = 'selected'; }
		else if($fetch[0]['category'] == 'premium') { $pSelect = 'selected'; }
	$seasonS = $seasonW = $seasonWS = '';
	if($fetch[0]['season'] == 'summer') { $seasonS = 'selected'; }
	else if($fetch[0]['season'] == 'winter') { $seasonW = 'selected'; }
    else if($fetch[0]['season'] == 'winterStudded') { $seasonWS = 'selected'; }
	$selectOptions = '<option value="a">A</option>';
	$selectOptions .= '<option value="b">B</option>';
	$selectOptions .= '<option value="c">C</option>';
	$selectOptions .= '<option value="d">D</option>';
	$selectOptions .= '<option value="e">E</option>';
	$selectOptions .= '<option value="f">F</option>';
	$selectOptions .= '<option value="g">G</option>';
	$selectOptions .= '<option value="h">H</option>';
	$noiseOptions = '';
	for($i=50; $i <=100; $i++) {
		$noiseOptions .= '<option value="'.$i.'">'.$i.'</option>';
	}
?>

<div style="display: flex;" class="row">
	<div class="col-md-12 col-lg-6 m-0 p-0"  >
		<form id="addTyreForm" enctype="multipart/form-data" method="post" >
		<input type="hidden" name="method" value="addTyre" />
		<input type="hidden" id="originalImage" name="originalImage" value="<?php echo $fetch[0]['picture']?>" />
		<div class="col card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Add Product
			</div>
			<div class="card-body">
				<h5 class="card-title">Input Product Details</h5>
				<br>
				
				<div class="row">
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="productName">Category</label>
						<select class="form-control" id="category"   name="category" style="font-weight:500;">
							<option value="budget"  <?php echo $bSelect ?>>Budget</option>
							<option value="mellom"  <?php echo $mSelect ?>>Mellom</option>
							<option value="premium"  <?php echo $pSelect ?>>Premium</option>
						</select>							
					</div>
					<div class="btn-group-toggle col-md-12 col-lg-6" data-toggle="buttons" style="padding:30px 20px 20px 15px;">
						<label class="btn btn-danger btnMod">
							<input type="checkbox" id="recommended" autocomplete="off" name="recommended"> Recommend
						</label>
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="productName">Brand</label>
						<!-- <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand name" style="font-weight:500;" /> -->									
						<select class="form-control" id="brand_name" name="brand_name" style="font-weight:500;">
						    
						    <?php
							$array_model = array();
							foreach($fetch as $f){
								if(!in_array($f['brand_name'], $array_model)){ ?>
								<option value='<?php echo $f['brand_name'] ?>'  ><?php echo $f['brand_name'] ?></option> 
								<?php array_push($array_model, $f['brand_name']); }
						    } ?>						
					
						</select>
					</div>
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="productName">Model</label>
						<!-- <input type="text" class="form-control" id="model" name="model" placeholder="Model" style="font-weight:500;" />
						 -->
						 <select class="form-control" id="model" name="model" style="font-weight:500;">
												

						</select>
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="productName">Speed</label>
						<!-- <input type="text" class="form-control" id="speed" name="speed" placeholder="Speed" style="font-weight:500;" /> -->
						<select class="form-control" id="speed" name="speed" style="font-weight:500;">
				
							<?php
							$array_model = array();
							foreach($fetch as $f){
								if(!in_array($f['speed'], $array_model)){ ?>
								<option value='<?php echo $f['speed'] ?>'  ><?php echo $f['speed'] ?></option> 
								<?php array_push($array_model, $f['speed']); }
						    } ?>				
						</select>
					</div>
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="productName">Load</label>
						<!-- <input type="text" class="form-control" id="load" name="load" placeholder="Load" style="font-weight:500;" /> -->
						<select class="form-control" id="load" name="load" style="font-weight:500;">
					
							<?php
							$array_model = array();
							foreach($fetch as $f){
								if(!in_array($f['load'], $array_model)){ ?>
								<option value='<?php echo $f['load'] ?>'  ><?php echo $f['load'] ?></option> 
								<?php array_push($array_model, $f['load']); }
						    } ?>	
						</select>
					</div>
				</div>
				
				<div class="row">
					
					<div class="form-group col-md-12 col-lg-6" style="">
						<div class="form-group" style="margin-bottom:10px;">
							<label for="mobile" class="col-form-label labelMod" style="width:250px;">Number of Size and Price(Kr):</label>
							<select class="form-control inputMod" id="locationsToAdd" name="totalLocations">
								<?php echo $no; ?>
							</select>
						</div>		
					</div>
					<div class="form-group col-md-12 col-lg-6" style=""></div>
				</div>
				<div class="row">
					<div class="form-group col-12" style="">
						<table class="table" style="font-size:13px;">
							<thead>
								<tr>
								<th scope="col">#</th>
								<th scope="col">Size</th>
								<th scope="col">Price</th>
								</tr>
							</thead>
							<tbody class="locationFieldsTbody">
								<tr>			
									<td>1</td>
									<td><input type="text" class="form-control inputMod" name="row-1" placeholder="Row" style="width:60px;"></td>
									<td><input type="text" class="form-control inputMod" name="rack-1" placeholder="Rack" style="width:60px;"></td>
									
								
								</tr>								
							</tbody>
						</table>
					</div>				
				</div>	
				
				<div class="row">
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="fuel">Drivstoff Forbruk</label>
						<select id="fuel" class="form-control" name="fuel" style="font-weight:500;">
							
							<?php
							$array_model = array();
							foreach($fetch as $f){
								if(!in_array($f['drivstoff_forbruk'], $array_model)){ ?>
								<option value='<?php echo $f['drivstoff_forbruk'] ?>'  ><?php echo $f['drivstoff_forbruk'] ?></option> 
								<?php array_push($array_model, $f['drivstoff_forbruk']); }
						    } ?>
							
						</select>
					</div>
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="fuel">Våt Grep</label>
						<select id="grip" name="grip" class="form-control" style="font-weight:500;">
				
							<?php
							$array_model = array();
							foreach($fetch as $f){
								if(!in_array($f['vat_grep'], $array_model)){ ?>
								<option value='<?php echo $f['vat_grep'] ?>'  ><?php echo $f['vat_grep'] ?></option> 
								<?php array_push($array_model, $f['vat_grep']); }
						    } ?>
						</select>
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="noise">Støy Nivå</label>
						<select id="noise" name="noise" class="form-control" style="font-weight:500;">

							<?php
							$array_model = array();
							foreach($fetch as $f){
								if(!in_array($f['stoy_niva'], $array_model)){ ?>
								<option value='<?php echo $f['stoy_niva'] ?>'  ><?php echo $f['stoy_niva'] ?></option> 
								<?php array_push($array_model, $f['stoy_niva']); }
						    } ?>
						</select>
					</div>
					<div class="form-group col-md-12 col-lg-6" style="">
						<label for="season">Season</label>
						<select id="season" name="season" class="form-control" style="font-weight:500;">
							<option value="summer" <?php echo $seasonW; ?>>Sommerdekk</option>
							<option value="winter" <?php echo $seasonS; ?>>Vinterdekk - piggfrie</option>
							<option value="winterStudded" <?php echo $seasonWS; ?>>Vinterdekk - piggdekk</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12 col-lg-6">
						<label for="productName">Product Image</label>
						<br>
						<input type="file" class="" id="image" name="image" accept="image/*" />
			
					</div><div class="form-group col-md-12 col-lg-6" style="">
						<label for="runFlat">Runflat</label>
						<select id="runFlat" name="runFlat" class="form-control" style="font-weight:500;">
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col" style="">
						<label for="tyreInfo">Tyre Info</label>
						<textarea class="form-control" name="tyreInfo" id="tyreInfo" style=""><?php echo $fetch[0]['tyre_info']?></textarea>
					</div>
				</div>
				
				<button class="btn btn-primary mt-2" id="addTyre" name="addTyre">Add Product</button>
			</div>
		</div>
		</form>
		
	</div>
	<div style="width:100%; margin:auto; text-align:center;" class="col-md-12 col-lg-6">
		<img id="imagePreview" src= "../uploads/tyreImg/<?php echo $fetch[0]['picture']?>" style=" width: 400px; height:400px;"/>
	</div>
</div>


<script>
var emptyFields = 0;
$('#addTyre').on('click', function(event) {
	event.preventDefault();
	emptyFields = 0;
	$('.form-control').each(function() {
		console.log($(this).val());
		if($(this).val() == '') {
			
			emptyFields = 1;
		}
	});
	if(emptyFields == 1) { showAlert('danger', 'All the fields are required'); return; }
	
	var form = document.getElementById('#addTyreForm');
	
	showLoadingBar(1);
	fetchA('addTyreForm', function (result) {
		hideLoadingBar();	
		
		var e = JSON.parse(result);
		console.log(e);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully added the tyre');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'empty') {
			showAlert('danger', 'All the fields are required');
		}else if(e[0] == 'no image') {
			showAlert('danger', 'No image selected');
		}else if(e[0] == 'image required') {
			showAlert('danger', 'Only images are allowed');
		}else if(e[0] == 'already added') {
			showAlert('danger', 'Same tyre has been already added');
		}else {
			showAlert('danger', 'Technical error, contact developer');
		}
	}, 1);


	
	
	/*
	var brand = $('#brand').val();
	var model = $('#model').val();
	var speed = $('#speed').val();
	var load = $('#load').val();
	var size = $('#size').val();
	var price = $('#price').val();
	var fuel = $('#fuel').find(':selected').val();
	var grip = $('#grip').find(':selected').val();
	var noise = $('#noise').find(':selected').val();
	//var image = $('#image').val();
	var category = $('#category').find(':selected').val();
	var season = $('#season').find(':selected').val();
	var recommended = 0;
	if($('#recommended').prop('checked')) { recommended = 1; } 
	
	showLoadingBar(1);
	var url = 'method=addTyre&season='+season+'&brand='+brand+'&model='+model+'&speed='+speed+'&load='+load+'&size='+size+'&price='+price+'&fuel='+fuel+'&grip='+grip+'&noise='+noise+'&category='+category+'&recommended='+recommended;
	fetchA(url, function (result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully added the tyre');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'empty') {
			showAlert('danger', 'All the fields are required');
		}else {
			showAlert('danger', 'Technical error, contact developer');
		}
	});
	*/
	
});

$(document).ready(function(){
var data = '<?php echo json_encode($fetch); ?>';	
    data = data.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t");
    data = JSON.parse(data);
		var array = $.map(data, function(value, index){
            return [value];	
  		 });
	
	for(i = 0; i < array.length; i++){
	if(array[i]['brand_name'] === array[0]['brand_name']){
		if (i == 0) selected = 'selected';
		$("#model").append("<option value='" + array[i]['model_name'] + "'" + selected + ">" + array[i]['model_name'] + "</option>")
		}
	}

    $('#brand_name').on('change', function() {
		var cur_value = $(this).val();
		document.getElementById('model').innerHTML = '';
		
		var first = true;
		for(i = 0; i < array.length; i++){
			var selected = '';
			if(array[i]['brand_name'] === cur_value){
			        if(first)
			        {
			            	$('#category').val(array[i]['category']);
        					$('#speed').val(array[i]['speed']);
        					$('#load').val(array[i]['load']);
        					$('#fuel').val(array[i]['drivstoff_forbruk']);
        					$('#grip').val(array[i]['vat_grep']);
        					$('#noise').val(array[i]['stoy_niva']);
        					$('#season').val(array[i]['season']);
        					$('#tyreInfo').val(array[i]['tyre_info']);
        					$('#imagePreview').attr('src', "../uploads/tyreImg/"+array[i]['picture']);
        					$('#originalImage').val(array[i]['picture']);
        					$('#image').innerHTML="";
        					selected = "selected";
							first = false;
			        }
				
			    	$("#model").append("<option value='" + array[i]['model_name'] + "'  "+selected+">" + array[i]['model_name'] + "</option>")
				}
			}
		
	});
	
	$('#model').on('change', function(){
		var cur_brand_name = $('#brand_name').val();
		var cur_model_name = $(this).val();
		for(i = 0; i < array.length; i++){
			if(array[i]['brand_name'] === cur_brand_name && array[i]['model_name'] == cur_model_name){
				$('#category').val(array[i]['category']);
				$('#speed').val(array[i]['speed']);
				$('#load').val(array[i]['load']);
				$('#fuel').val(array[i]['drivstoff_forbruk']);
				$('#grip').val(array[i]['vat_grep']);
				$('#noise').val(array[i]['stoy_niva']);
				$('#season').val(array[i]['season']);
				$('#tyreInfo').val(array[i]['tyre_info']);
				$('#imagePreview').attr('src', "../uploads/tyreImg/"+array[i]['picture']);
				$('#image').innerHTML="";
				$('#originalImage').val(array[i]['picture']);
			}
		}
	});
});

$("#image").change(function() {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#imagePreview").attr('src',e.target.result);
			};
			reader.readAsDataURL(this.files[0]);
    });

$('#locationsToAdd').on('change', function() {
	var total = parseInt($(this).val());
	var locationFields = '';
	
	for(var i=1; i <= total; i++) {
		locationFields += '<tr>'+
				  '<th scope="row">'+i+'</th>'+
				  '<td><input type="text" class="form-control inputMod" name="row-'+i+'" placeholder="Row" style="width:60px;"></td>'+
				  '<td><input type="text" class="form-control inputMod" name="rack-'+i+'" placeholder="Rack" style="width:60px;"></td>'+
				  
				  
				'</tr>';
	}
	$('.locationFieldsTbody').html(locationFields);
	
});


</script>