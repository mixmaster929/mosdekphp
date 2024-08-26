<?php


$con = dbCon();

$tr = '';
$i = 1;
$q = mysqli_query($con, "SELECT * FROM shop_tyres_api");
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) { 
		$imageLink = '';
		if($f['image'] != '') {
			$imageLink = '<a href="'.$f['image'].'" target="_blank">View</a>';
		}

		$checked = "";
			
		$tr .= '<tr>
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
					<span class="txtField'.$f['id'].'">'.$f['width'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['width'].'" id="width'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['profile'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['profile'].'" id="profile'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['inches'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['inches'].'" id="inches'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['price'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['price'].'" id="price'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['fuel'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['fuel'].'" id="fuel'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$f['noise'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['noise'].'" id="noise'.$f['id'].'" style="display:none; font-weight:bold; width:50px;">	
				</td>
				
				<td>
					<span class="txtField'.$f['id'].'">'.$f['season'].'</span>
				</td>
				<td>
					<span class="txtField'.$f['id'].'">'.$imageLink.'</span>
					 <input type="file" class="form-control-file editField'.$f['id'].'" id="image'.$f['id'].'" style="display:none;">
					<div id="imageUploadBar'.$f['id'].'" style="display:none;"><img src="../images/Rolling.gif" id="uploadLoading'.$f['id'].'" style="width:20px; margin-right:10px;" /> <span id="uploadPerc'.$f['id'].'" style=""></span>%</div>
				</td>
				
				<td>
					<button class="btn btn-sm btn-outline-warning py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="edit('.$f['id'].')">Edit</button>
					<button class="btn btn-sm btn-outline-success py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="saveNew('.$f['id'].')" style="display:none;">Save</button>
					<button class="btn btn-sm btn-outline-danger py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="deleteRow(\'tyres\', '.$f['id'].', 1)">Delete</button>
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
							<span class="input-group-text" id="inputGroup-sizing-sm">Width</span>
						</div>
						<input type="text" class="form-control" id="width" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="width">Search</button>
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
						  <th scope="col">Brand</th>
						  <th scope="col">Model</th>
						  <th scope="col">Speed</th>
						  <th scope="col">Load</th>
						  <th scope="col">Width</t>
						  <th scope="col">Profile</t>
						  <th scope="col">Inches</th>
						  <th scope="col">Price</th>
						  <th scope="col">Fuel Wet Grip</th>
						  <th scope="col">Noise</th>
						  <th scope="col">Season</th>
						  <th scope="col">Pic</th>
						  <th></th>
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

function edit(i) {
	$('.txtField'+i).hide();
	$('.editField'+i).show();
	$('#recommended'+i).removeAttr('disabled');
	$('.editButton'+i).hide();
	$('.saveButton'+i).show();
}

function save(i) {
	var category = $('#category'+i).find(':selected').val();
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
	var url = 'method=saveTyreInfo&category='+category+'&brand='+brand+'&model='+model+'&speed='+speed+'&load='+load+'&size='+size+'&price='+price+'&fuel='+fuel+'&grip='+grip+'&noise='+noise+'&image='+image+'&id='+i;
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
	var url='method=tyreSearchApi&type='+type+'&value='+value;
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