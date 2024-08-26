<?php
$con = dbCon();

$maxNumberSelect = '';
for($i=1; $i<=40; $i++) {
	$maxNumberSelect .= '<option value="'.$i.'">'.$i.'</option>';
}

$tr = '';
$q = mysqli_query($con, "SELECT * FROM shop_misc WHERE property='services'");
if(mysqli_num_rows($q) > 0) {
	
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		// services > attribute4 = maxNumber
		
		$tr .= '<tr>';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>
					<span class="txtField'.$f['id'].'">'.$f['attribute1'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute1'].'" id="service'.$f['id'].'" style="display:none; font-weight:bold; width:250px;">
				</td>';
		$tr .= '<td>
					<span class="txtField'.$f['id'].'">'.$f['attribute2'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute2'].'" id="price'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
				</td>';
		$tr .= '<td>
					<span class="txtField'.$f['id'].'">'.$f['attribute3'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute3'].'" id="time'.$f['id'].'" style="display:none; font-weight:bold; width:100px;">
				</td>';
		$tr .= '<td>
					<span class="txtField'.$f['id'].'">'.$f['attribute4'].'</span>
					<select class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute4'].'" id="maxNum'.$f['id'].'" style="display:none; font-weight:bold; width:70px;">'.$maxNumberSelect.'</select>
				</td>';
		$tr .= '<td>
					<button class="btn btn-sm btn-warning text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-danger text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="deleteRow(\'misc\', '.$f['id'].', 1)" >Delete</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;		
				</td>';
		$tr .= '</tr>';
		$i++;
	}
}

		$tr .= '<tr>';
		$tr .= '<td></td>';
		$tr .= '<td><input type="text" class="form-control form-control-sm inputMod " id="service" placeholder="Services" style="font-weight:bold; width:250px;"></td>';
		$tr .= '<td><input type="text" class="form-control form-control-sm inputMod " id="price" style="font-weight:bold; width:100px;"></td>';
		$tr .= '<td><input type="text" class="form-control form-control-sm inputMod " id="time" style="font-weight:bold; width:100px;"></td>';
		$tr .= '<td><select class="form-control form-control-sm inputMod " id="maxNum" style="font-weight:bold; width:70px;">'.$maxNumberSelect.'</select></td>';
		$tr .= '<td><button id="addService" class="btn btn-sm btn-success text-white py-1 m-0" onclick="addService()">Add Service</button></td>';
		$tr .= '</tr>';	
		
		
//timeSlots

$trS = '';

	$i = 1;
	for($j=1; $j<8; $j++) {
		
		if($j == 1) { $day = 'Sunday'; } else
		if($j == 2) { $day = 'Monday'; } else
		if($j == 3) { $day = 'Tuesday'; } else
		if($j == 4) { $day = 'Wednesday'; } else
		if($j == 5) { $day = 'Thursday'; } else
		if($j == 6) { $day = 'Friday'; } else
		if($j == 7) { $day = 'Saturday'; }
		
		$q = mysqli_query($con, "SELECT * FROM shop_misc WHERE property='timesForNormalTyreChangeOrder' AND attribute1='$day'");
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		
		$trS .= '<tr>';
		$trS .= '<td>'.$i.'</td>';
		$trS .= '<td>'.$day.'</td>';
		$trS .= '<td>
					<span class="txtFieldT'.$f['id'].'">'.$f['attribute2'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editFieldT'.$f['id'].'" value="'.$f['attribute2'].'" id="timeT'.$f['id'].'" style="display:none; font-weight:bold; width:500px;">
				</td>';
		$trS .= '<td>
					<button class="btn btn-sm btn-warning text-white py-0 m-0 editButtonT'.$f['id'].' buttonT'.$f['id'].'" onclick="editT('.$f['id'].')" >Edit</button>
					<button class="btn btn-sm btn-success text-white py-0 m-0 buttonT'.$f['id'].' saveButtonT'.$f['id'].'" onclick="saveT('.$f['id'].', \''.$day.'\')" style="display:none;">Save</button>
				</td>';
		$trS .= '</tr>';
		$i++;
	}

?>
<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Services
			</div>
			<div class="card-body">
				<h5 class="card-title">List of all services</h5>
				<div class="alert alert-warning" style="font-size:15px; margin:0px -20px 20px; border-radius:0px; padding:5px 20px;">
					Write time for services in <b>MINUTES</b>
				</div>
				
				
				<table class="table table-hover " style="">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Service</th>
						  <th scope="col">Price</th>
						  <th scope="col">Time(minute)</th>
						  <th scope="col">Max</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>

<div class=" card px-0 m-2 mt-3" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Time Slots
			</div>
			<div class="card-body">
				<h5 class="card-title">Times for tyre change order</h5>
				<div class="alert alert-warning" style="font-size:15px; margin:0px -20px 20px; border-radius:0px; padding:5px 20px;">
					Separate each time with <b>comma(,)</b>
				</div>
				
				
				<table class="table table-sm table-hover " style="">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Day</th>
						  <th scope="col">List of times</th>
						  <th scope="col"></th>
						</tr>
					  </thead>
					  <tbody>
						<?php echo $trS; ?>
					  </tbody>
					</table>
			</div>
		</div>

<script>

function edit(i) {
	$('.txtField'+i).hide();
	$('.editField'+i).fadeIn(100);
	$('.button'+i).hide();
	$('.saveButton'+i).fadeIn(100);
}

function save(i) {
	var service = $('#service'+i).val();
	var price = $('#price'+i).val();
	var timeSlots = $('#time'+i).val();
	var maxNum = $('#maxNum'+i).find(':selected').val();
	if(service == '' || price == '' || timeSlots == '') { alert('All fields are required'); return; }
	
	showLoadingBar(1);
	var url = 'method=saveService&service='+service+'&id='+i+'&price='+price+'&timeSlots='+timeSlots+'&maxNum='+maxNum;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = $.parseJSON(result);
		if(e[0] == 'success') {
			showAlert('success','Successfully updated service');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in');
		}else {
			showAlert('danger', 'Technical error contact Admin');
		}
	});
}

function addService() {
	var service = $('#service').val();
	var price = $('#price').val();
	var timeSlots = $('#time').val();
	var maxNum = $('#maxNum').find(':selected').val();
	if(service == '' || price == '' || timeSlots == '' ) { showModal('Empty Fields', 'All the fields are required'); return; }
	
	showLoadingBar(1);
	var url = 'method=addService&service='+service+'&price='+price+'&timeSlots='+timeSlots+'&maxNum='+maxNum;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = $.parseJSON(result);
		if(e[0] == 'failed') {
			showModal('Error', 'Technical error occurred, contact admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Successfully added the service');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as Admin');
		}
	});
}

// timeSlots Section

function editT(i) {
	$('.txtFieldT'+i).hide();
	$('.editFieldT'+i).fadeIn(100);
	$('.buttonT'+i).hide();
	$('.saveButtonT'+i).fadeIn(100);
}

function saveT(i, day) {
	var time = $('#timeT'+i).val();
	//if(time == '') { alert('Time slot is empty'); return; }
	
	showLoadingBar(1);
	var url = 'method=saveTime&day='+day+'&id='+i+'&time='+time;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = $.parseJSON(result);
		if(e[0] == 'failed') {
			showAlert('danger', 'Technical error, contact admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Successfully updated time slot');
			location.reload(true);
		}
	});
}

function addTime() {
	var date = $('#dateT').val();
	var time = $('#timeT').val();
	if(date == '' || time == '') { showModal('Empty Fields', 'All fields are required'); return; }
	
	showLoadingBar(1);
	var url = 'method=addTime&date='+date+'&time='+time;
	fetch(url, function(result) {
		hideLoadingBar();
		var e = $.parseJSON(result);
		if(e[0] == 'failed') {
			showAlert('danger', 'Technical error, contact admin');
		}else if(e[0] == 'success') {
			showAlert('success','Successfully added the service');
			location.reload(true);
		}else if(e[0] == 'exists') {
			showAlert('danger', 'This date already exists');
		}
	});
}


</script>






