<?php

// Get the API 
// require_once("curl_helper.php");
// $access_token = CurlHelper::get_http_token()->access_token;
// if(!empty($access_token)) {
// 	$data = false;
// 	$method = "GET";
// 	$url = "https://amr-production-api.azurewebsites.net/api/Articles/GetProducts";
// 	$request = CurlHelper::callAPI($method, $url, $data, $access_token); // Send or retrieve data
// 	var_dump($request);
// }

$con = dbCon();

$tr = '';
$i = 1;
$sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "WHERE location = 'Moss Dekk AS'";
$q = mysqli_query($con, "SELECT * FROM shop_tyres $sqlWhere");
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) { 
		
		$bSelect = $mSelect = $pSelect = '';
		if($f['category'] == 'budget') { $bSelect = 'selected'; }
		else if($f['category'] == 'mellom') { $mSelect = 'selected'; }
		else if($f['category'] == 'premium') { $pSelect = 'selected'; }
		
		$noiseSelect = '<select id="noise'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">';
		for($j=50; $j <=100; $j++) {
			if($f['noise'] == $j){
				$noiseSelect .= '<option value="'.$j.'" selected>'.$j.'</option>';
			}
			else
			{
				$noiseSelect .= '<option value="'.$j.'">'.$j.'</option>';
			}
		}
		$noiseSelect .= '</select>';
		$selectGripOptions = $selectFuelOptions = '';
		$gripSelectA = $gripSelectB = $gripSelectC = $gripSelectD = $gripSelectE = $gripSelectF = $gripSelectG = $gripSelectH ="";
		if($f['grip'] == 'a' || $f['grip'] == 'A') { $gripSelectA = 'selected'; }
		else if ($f['grip'] == 'b' || $f['grip'] == 'B') { $gripSelectB = 'selected'; }
		else if ($f['grip'] == 'c' || $f['grip'] == 'C') { $gripSelectC = 'selected'; }
		else if ($f['grip'] == 'd' || $f['grip'] == 'D') { $gripSelectD = 'selected'; }
		else if ($f['grip'] == 'e' || $f['grip'] == 'E') { $gripSelectE = 'selected'; }
		else if ($f['grip'] == 'f' || $f['grip'] == 'F') { $gripSelectF = 'selected'; }
		else if ($f['grip'] == 'g' || $f['grip'] == 'G') { $gripSelectG = 'selected'; }
		else if ($f['grip'] == 'h' || $f['grip'] == 'H') { $gripSelectH = 'selected'; }
		
		$selectGripOptions = '<option  value="a" '.$gripSelectA.'>A</option>';
		$selectGripOptions .= '<option  value="b" '.$gripSelectB.'>B</option>';
		$selectGripOptions .= '<option  value="c" '.$gripSelectC.'>C</option>';
		$selectGripOptions .= '<option  value="d" '.$gripSelectD.'>D</option>';
		$selectGripOptions .= '<option  value="e" '.$gripSelectE.'>E</option>';
		$selectGripOptions .= '<option  value="f" '.$gripSelectF.'>F</option>';
		$selectGripOptions .= '<option  value="g" '.$gripSelectG.'>G</option>';
		$selectGripOptions .= '<option  value="h" '.$gripSelectH.'>H</option>';
		
		$fuelSelectA = $fuelSelectB = $fuelSelectC = $fuelSelectD = $fuelSelectE = $fuelSelectF = $fuelSelectG = $fuelSelectH = "";
		if($f['fuel'] == 'a' || $f['fuel'] == 'A') { $fuelSelectA = 'selected'; }
		else if ($f['fuel'] == 'b' || $f['fuel'] == 'B') { $fuelSelectB = 'selected'; }
		else if ($f['fuel'] == 'c' || $f['fuel'] == 'C') { $fuelSelectC = 'selected'; }
		else if ($f['fuel'] == 'd' || $f['fuel'] == 'D') { $fuelSelectD = 'selected'; }
		else if ($f['fuel'] == 'e' || $f['fuel'] == 'E') { $fuelSelectE = 'selected'; }
		else if ($f['fuel'] == 'f' || $f['fuel'] == 'F') { $fuelSelectF = 'selected'; }
		else if ($f['fuel'] == 'g' || $f['fuel'] == 'G') { $fuelSelectG = 'selected'; }
		else if ($f['fuel'] == 'h' || $f['fuel'] == 'H') { $fuelSelectH = 'selected'; }
		
		$selectFuelOptions = '<option  value="a" '.$fuelSelectA.'>A</option>';
		$selectFuelOptions .= '<option  value="b" '.$fuelSelectB.'>B</option>';
		$selectFuelOptions .= '<option  value="c" '.$fuelSelectC.'>C</option>';
		$selectFuelOptions .= '<option  value="d" '.$fuelSelectD.'>D</option>';
		$selectFuelOptions .= '<option  value="e" '.$fuelSelectE.'>E</option>';
		$selectFuelOptions .= '<option  value="f" '.$fuelSelectF.'>F</option>';
		$selectFuelOptions .= '<option  value="g" '.$fuelSelectG.'>G</option>';
		$selectFuelOptions .= '<option  value="h" '.$fuelSelectH.'>H</option>';
		

		$imageLink = '';
		if($f['image'] != '') {
			$imageLink = '<a href="../uploads/tyreImg/'.$f['image'].'" target="_blank">View</a>';
		}
		
		$tyreInfo = $readInfoLink = $editInfoLink = $runFlatSelect = $runFlatNoSelect = $runFlatYesSelect = $runFlat = '';
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['tyreInfo'])) {
				$tyreInfo = $misc['tyreInfo'];
				$readInfoLink = '<button href="#" class="btn btn-sm py-0 m-0 btn-outline-primary editButton'.$f['id'].' button'.$f['id'].'" data-toggle="modal" data-target="#tyreInfoModal" data-tyreid="'.$f['id'].'" data-mode="readonly">View</button>';
				$editInfoLink = '<button href="#" class="btn btn-sm py-0 m-0 btn-outline-warning saveButton'.$f['id'].' button'.$f['id'].'" data-toggle="modal" data-target="#tyreInfoModal" data-tyreid="'.$f['id'].'" data-mode="edit" style="display:none;">Change</button>';
			}
			
			if(isset($misc['runFlat'])) {
				$runFlat = $misc['runFlat'];
				if($runFlat == 'yes') {
					$runFlatYesSelect = 'selected';
				}else {
					$runFlatNoSelect = 'selected';
				}
			}
			$runFlatSelect = '<select id="runFlat'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">
								<option value="yes" '.$runFlatYesSelect.'>Yes</option>
								<option value="no" '.$runFlatNoSelect.'>No</option>
							</select>';
		}
		
		$select = '<select id="category'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
						<option value="budget" '.$bSelect.'>Budget</option>
						<option value="mellom" '.$mSelect.'>Mellom</option>
						<option value="premium" '.$pSelect.'>Premium</option>
					</select>';
					
		$seasonS = $seasonW = $seasonWS = '';
		if($f['season'] == 'summer') { $seasonS = 'selected'; }
		else if($f['season'] == 'winter') { $seasonW = 'selected'; }
		else { $seasonWS = 'selected'; }
		$seasonSelect = '<select id="season'.$f['id'].'" class="form-control form-control-sm txtInput editField'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
						<option value="summer" '.$seasonS.'>Sommerdekk</option>
						<option value="winter" '.$seasonW.'>Vinterdekk - piggfrie</option>
						<option value="winterStudded" '.$seasonWS.'>Vinterdekk - piggdekk</option>
					</select>';
		$season = '';
		if($f['season'] == 'winter') {	$season = 'Vinterdekk - piggfrie'; }
		else if($f['season'] == 'summer') { $season = 'Sommerdekk'; }
		else if($f['season'] == 'winterStudded') { $season = 'Vinterdekk - piggdekk'; }
		$checked = "";
		if($f['recommended'] == 1) 
			{$checked  = "checked";}
			
		$tr .= '<tr id="tr'.$f['id'].'">
				<textarea id="tyreInfo'.$f['id'].'" hidden="hidden">'.$tyreInfo.'</textarea>
				<td>
				    <input id="recommended'.$f['id'].'" type="checkbox" class="form-control-sm"'.$checked.' disabled>
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['location'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['location'].'" id="location'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">	
				</td>				
				<td>
					<span class="txtField'.$f['id'].'">'.$f['category'].'</span>
					'.$select.'
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['brand'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['brand'].'" id="brand'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['model'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['model'].'" id="model'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['speed'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['speed'].'" id="speed'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['load'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['load'].'" id="load'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['size'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['size'].'" id="size'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['price'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['price'].'" id="price'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['fuel'].'</span>
					<select class="form-control form-control-sm editField'.$f['id'].'" id="fuel'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">
						'.$selectFuelOptions.'
					</select>
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['grip'].'</span>
					<select class="form-control form-control-sm editField'.$f['id'].'" id="grip'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">
						'.$selectGripOptions.'
					</select>
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['noise'].'</span>
					'.$noiseSelect.'
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$runFlat.'</span>
					'.$runFlatSelect.'
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$season.'</span>
					'.$seasonSelect.'
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$imageLink.'</span>
					 <input type="file" class="form-control-file editField'.$f['id'].'" id="image'.$f['id'].'" style="display:none;">
					<div id="imageUploadBar'.$f['id'].'" style="display:none;"><img src="../images/Rolling.gif" id="uploadLoading'.$f['id'].'" style="width:20px; margin-right:10px;" /> <span id="uploadPerc'.$f['id'].'" style=""></span>%</div>
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$readInfoLink.'</span>
					'.$editInfoLink.'
				</td>
				<td>
					<button class="btn btn-sm btn-outline-warning py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="edit('.$f['id'].')">Edit</button>
					<button class="btn btn-sm btn-outline-success py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="saveNew('.$f['id'].')" style="display:none;">Save</button>
					<button class="btn btn-sm btn-outline-danger py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="deleteRow(\'tyres\', '.$f['id'].', 1)">Delete</button>
				</td>
				<td>
					<input type="checkbox" class="form-control-sm massdel" id="'.$f['id'].'">
				</td>
				</tr>';
		$i++;
	}
}



?>
<div style="">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Search Products
			</div>
			<div class="card-body">
				<div class="row">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Brand</span>
						</div>
						<input type="text" class="form-control" id="brand" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="brand">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Size</span>
						</div>
						<input type="text" class="form-control" id="size" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="size">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
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
				
				
				
				<table class="table table-hover table-sm" style="margin-top:30px; font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col">Recommended</th>
						  <th scope="col">Location</th>
						  <th>Category</th>
						  <th scope="col">Brand</th>
						  <th scope="col">Model</th>
						  <th scope="col">Speed</th>
						  <th scope="col">Load</th>
						  <th scope="col">Size</th>
						  <th scope="col">Price</th>
						  <th scope="col">Fuel</th>
						  <th scope="col">Wet Grip</th>
						  <th scope="col">Noise</th>
						  <th scope="col">Runflat</th>
						  <th scope="col">Season</th>
						  <th scope="col">Pic</th>
						  <th scope="col">Info</th>
						  <th scope="col"><button onclick="massDel()" class="btn btn-sm btn-outline-danger py-0 m-1"> Mass Delete </button></th>
						  <th><input type="checkbox" class="form-control-sm massdel" id="selectAll" onclick="selectAll()"></th>
						</tr>
					  </thead>
					  <tbody id="tyreBody">
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>

<div class="modal fade" id="tyreInfoModal" tabindex="-1" role="dialog" aria-labelledby="tyreInfoModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tyreInfoModal">Tyre Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <textarea class="form-control" id="tyreInfo" style="height:250px;"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveModalButton" style="display:none;">Save</button>
		<button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelModalButton">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
variables = new Array();

$('#tyreInfoModal').on('show.bs.modal', function (e) {
	var button = $(e.relatedTarget);
	var mode = button.data('mode');
	var id = button.data('tyreid');
	variables['tyreID'] = id;

	if(mode == 'readonly') {
		$('#tyreInfo').prop('readonly', true);
		$('#saveModalButton').hide();
	}else if(mode == 'edit') {
		$('#tyreInfo').prop('readonly', false);
		$('#saveModalButton').show();
	}
	
	var tyreInfo = $('#tyreInfo'+id).val();
	$('#tyreInfo').val(tyreInfo);
	
});

$('#saveModalButton').on('click', function() {
	var id = variables['tyreID'];
	var newInfo = $('#tyreInfo').val();
	$('#tyreInfo'+id).val(newInfo);
	$('#tyreInfoModal').modal('hide');
});

function selectAll() {
	var selectAllEle = $('#selectAll');
	var selectAll = selectAllEle[0].checked;

	var delIDs = $('.massdel');
	var delIDsArray = [];
	for (let index = 0; index < delIDs.length; index++) {
		var element = delIDs[index];
		element.checked = selectAll;
	}
}

function massDel(){
	var conf = confirm('Are you sure you want to delete selected entries?');
	if(conf == false) { return; }

	var delIDs = $('.massdel');
	var delIDsArray = [];
	for (let index = 0; index < delIDs.length; index++) {
		const element = delIDs[index];
		var ifChecked = element.checked;
		if(ifChecked){
			delIDsArray.push(element.id);
		}
	}
	if(delIDsArray.length < 1) {
		showAlert('danger', 'Entries not found');
		return;
	}

	showLoadingBar(1);
	var url = 'method=massDelete&id='+JSON.stringify(delIDsArray)+'&from=shop_tyres';
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		
		if(e[0] == 'success') {
			showAlert('success', 'Successfully deleted');
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

function edit(i) {
	$('.txtField'+i).hide();
	$('.editField'+i).show();
	$('#recommended'+i).removeAttr('disabled');
	$('.editButton'+i).hide();
	$('.saveButton'+i).show();
}

function save(i) {
	var category = $('#category'+i).find(':selected').val();
	var location = $('#location'+i).val();
	var brand = $('#brand'+i).val();
	var model = $('#model'+i).val();
	var speed = $('#speed'+i).val();
	var load = $('#load'+i).val();
	var size = $('#size'+i).val();
	var price = $('#price'+i).val();
	var fuel = $('#fuel'+i).find(':selected').val();
	var grip = $('#grip'+i).find(':selected').val();
	var noise = $('#noise'+i).find(':selected').val();
	var recommended = $('#recommended'+i).val();
	var image = '';
	
	
	showLoadingBar(1);
	var url = 'method=saveTyreInfo&category='+category+'&location='+location+'&brand='+brand+'&model='+model+'&speed='+speed+'&load='+load+'&size='+size+'&price='+price+'&fuel='+fuel+'&grip='+grip+'&noise='+noise+'&image='+image+'&id='+i;
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
		showModal('Empty Field', 'Field cannot be left empty');
		return;
	}
	
	showLoadingBar(1);
	var url='method=tyreSearch&type='+type+'&value='+value;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#tyreBody').html(e[1]);
		}else if(e[0] == 'no entry') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});


function saveNew(i) {
	
	var category = $('#category'+i).find(':selected').val();
	var brand = $('#brand'+i).val();
	var model = $('#model'+i).val();
	var location = $('#location'+i).val();
	var speed = $('#speed'+i).val();
	var load = $('#load'+i).val();
	var size = $('#size'+i).val();
	var price = $('#price'+i).val();
	var fuel = $('#fuel'+i).find(':selected').val();
	var grip = $('#grip'+i).find(':selected').val();
	var noise = $('#noise'+i).find(':selected').val();
	var recommended = 0;
	if ($('#recommended'+i).prop('checked')==true){ 
        recommended = 1;
    }
	
	//var image = $('#image'+i).prop('files');
	var image = $('#image'+i)[0].files[0];
	var tyreInfo = $('#tyreInfo'+i).val();
	var runFlat = $('#runFlat'+i).find(':selected').val();
	var season = $('#season'+i).find(':selected').val();

	var fd = new FormData();
	fd.append("method", "saveTyreInfo");
	fd.append("category", category);
	fd.append("location", location);
	fd.append("brand", brand);
	fd.append("model", model);
	fd.append("speed", speed);
	fd.append("load", load);
	fd.append("size", size);
	fd.append("price", price);
	fd.append("fuel", fuel);
	fd.append("grip", grip);
	fd.append("noise", noise);
	fd.append("id", i);
	fd.append("image", image);
	fd.append("tyreInfo", tyreInfo);
	fd.append("runFlat", runFlat);
	fd.append("season", season);
	fd.append('recommended', recommended)
	$('#image'+i).hide();
	$('#imageUploadBar'+i).show();
	$('#uploadLoading'+i).show();
	$('#uploadPerc'+i).html('0');
	
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'queryAdmin.php', true);
	//xhr.setRequestHeader("Content-Type" ,"application/x-www-form-urlencoded");
	
	xhr.upload.onprogress = function(e) {
		if (e.lengthComputable) {
			var percentComplete = (e.loaded / e.total) * 100;
			$('#uploadPerc'+i).html(percentComplete);
			if(percentComplete == 100) {
				$('#uploadLoading'+i).hide();
			}
			console.log(percentComplete + '% uploaded');
		}
	};

	xhr.onload = function() {
		if (this.status == 200) {
			var e = JSON.parse(this.response);
			
			if(e[0] == 'success') {
				showAlert('success', 'Successfully saved the changes');
				location.reload(true);
			}else if(e[0] == 'no admin') {
				showAlert('danger', 'You are not logged in as admin');
			}else if(e[0] == 'no tyre') {
				showAlert('danger', 'Tyre not found');
			}else if(e[0] == 'image required') {
				showAlert('danger', 'Only image file acceptable');
			}else {
				showAlert('danger', 'Technical error, contact developer');
			}
			
			//var image = document.createElement('img');
			//image.src = resp.dataUrl;
			//document.body.appendChild(image);
		};
	};

	xhr.send(fd);
}

</script>