<?php
$con = dbCon();

$tr = '';
$q = mysqli_query($con, "SELECT * FROM shop_admins");
if(mysqli_num_rows($q) > 0) {
	
	$i = 1;
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		// services > attribute4 = maxNumber
		
		$tr .= '<tr>';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>
					<span class="txtFieldTyre'.$f['id'].'">'.$f['username'].'</span>
					<input type="text" class="form-control form-control-sm txtInput editFieldTyre'.$f['id'].'" value="'.$f['username'].'" id="usernameTyre'.$f['id'].'" style="display:none; font-weight:bold; width:200px;">
				</td>';
		$tr .= '<td>
					<span class="txtFieldTyre'.$f['id'].'" title="'.$f['password'].'"></span>
					<input type="text" class="form-control form-control-sm txtInput editFieldTyre'.$f['id'].'" value="" id="passwordTyre'.$f['id'].'" style="display:none; font-weight:bold; width:150px;">
				</td>';
		$tr .= '<td>
					<button class="btn btn-sm btn-warning text-white py-0 m-0 editButtonTyre'.$f['id'].' buttonTyre'.$f['id'].'" onclick="edit('.$f['id'].', \'Tyre\')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-danger text-white py-0 m-0 editButtonTyre'.$f['id'].' buttonTyre'.$f['id'].'" onclick="deleteAdmin('.$f['id'].', \'tyreShop\')" >Delete</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButtonTyre'.$f['id'].' buttonTyre'.$f['id'].'" onclick="save('.$f['id'].', \'Tyre\')" style="display:none;">Save</button>&nbsp;		
				</td>';
		$tr .= '</tr>';
		$i++;
	}
}
		$tr .= '<tr>';
		$tr .= '<td></td>';
		$tr .= '<td><input type="text" class="form-control form-control-sm inputMod " id="usernameTyre" placeholder="Services" style="font-weight:bold; width:250px;"></td>';
		$tr .= '<td><input type="text" class="form-control form-control-sm inputMod " id="passwordTyre" style="font-weight:bold; width:100px;"></td>';
		$tr .= '<td><button id="addAdmin" class="btn btn-sm btn-success text-white py-1 m-0" onclick="addAdmin(\'tyreShop\', \'Tyre\')">Add Admin</button></td>';
		$tr .= '</tr>';	
		
		
$trWarehouse = '';
$url = 'http://autobutler.no/dekkhotell/accessTyreHotell.php';
$postData = [
	'method' => 'fetchAdmin'
	];
$response = get_web_page($url, $postData);
$resArr = json_decode($response);
if($resArr->result == 'success') {
	$trWarehouse .= $resArr->html;
}
		$trWarehouse .= '<tr>';
		$trWarehouse .= '<td></td>';
		$trWarehouse .= '<td><input type="text" class="form-control form-control-sm inputMod " id="usernameDekk" placeholder="username" style="font-weight:bold; width:250px;"></td>';
		$trWarehouse .= '<td><input type="text" class="form-control form-control-sm inputMod " id="passwordDekk" style="font-weight:bold; width:100px;"></td>';
		$trWarehouse .= '<td><button id="addAdminDekk" class="btn btn-sm btn-success text-white py-1 m-0" onclick="addAdmin(\'warehouse\', \'Dekk\')">Add Admin</button></td>';
		$trWarehouse .= '</tr>';	


$trManagement = '';
$url = 'http://autobutler.no/management/api/functions.php';
$postData = [
	'method' => 'fetchAdmin'
	];
$response = get_web_page($url, $postData);
$resArr = json_decode($response);
if($resArr->result == 'success') {
	$trManagement .= $resArr->html;
}
		$trManagement .= '<tr>';
		$trManagement .= '<td></td>';
		$trManagement .= '<td><input type="text" class="form-control form-control-sm inputMod " id="usernameManage" placeholder="username" style="font-weight:bold; width:250px;"></td>';
		$trManagement .= '<td><input type="text" class="form-control form-control-sm inputMod " id="passwordManage" style="font-weight:bold; width:100px;"></td>';
		$trManagement .= '<td><button id="addAdminDekk" class="btn btn-sm btn-success text-white py-1 m-0" onclick="addAdmin(\'management\', \'Manage\')">Add Admin</button></td>';
		$trManagement .= '</tr>';	

?>
<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Tyre Shop Admins
			</div>
			<div class="card-body">				
				<table class="table table-hover table-sm" style="font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Username</th>
						  <th scope="col">Password</th>
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
<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Dekkhotell Admins
			</div>
			<div class="card-body">				
				<table class="table table-hover table-sm" style="font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Username</th>
						  <th scope="col">Password</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php echo $trWarehouse; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>
<div style="">
		<div class=" card px-0 m-2" style="box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Management Admins
			</div>
			<div class="card-body">				
				<table class="table table-hover table-sm" style="font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Username</th>
						  <th scope="col">Password</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php echo $trManagement; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>


<script>

function deleteAdmin(i, site) {
	if(site == 'tyreShop') {
		deleteRow('admins', i, 1);
		return;
	}
	
	var conf = confirm('Are you sure to delete admin?');
	if(conf === false) { return; }
	
	showLoadingBar(1);
	var url = 'method=deleteAdmin&i='+i+'&site='+site;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully deleted admin');
			location.reload(true);
		}else {
			showAlert('danger', 'Technical error, contact admin');
		}
	});
	
}

function edit(i, site) {
	$('.txtField'+site+i).hide();
	$('.editField'+site+i).fadeIn(100);
	$('.button'+site+i).hide();
	$('.saveButton'+site+i).fadeIn(100);
}

function save(i, site) {
	var username = $('#username'+site+i).val();
	var password = $('#password'+site+i).val();
	//var email = $('#email'+site+i).val();
	if(username == '' || password == '' ) { alert('All fields are required'); return; }
	
	showLoadingBar(1);
	var url = 'method=saveAdmin&username='+username+'&id='+i+'&password='+password+'&site='+site;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = $.parseJSON(result);
		if(e[0] == 'success') {
			showAlert('success','Successfully updated admin details');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in');
		}else if(e[0] == 'exists') {
			showAlert('danger', 'Username & password combination already exists');
		}else {
			showAlert('danger', 'Technical error contact Admin');
		}
	});
}

function addAdmin(site, type) {
	var username = $('#username'+type).val();
	var password = $('#password'+type).val();
	//var email = $('#email'+type).val();
	if(username == '' || password == '' ) { alert('All fields are required'); return; }
	
	showLoadingBar(1);
	var url = 'method=addAdmin&username='+username+'&password='+password+'&site='+site;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = $.parseJSON(result);
		if(e[0] == 'failed' || e[0] == 'api error') {
			showAlert('danger', 'Technical error occurred, contact admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Successfully added the admin');
			location.reload(true);
		}else if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as Admin');
		}else if(e[0] == 'exists') {
			showAlert('danger', 'Username & password combination already exists');
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






