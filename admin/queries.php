<?php

$con = dbCon();

$tr = '';
$i = 1;
$sqlWhere = ($_SESSION['superAdmin'] == 1) ? "" : "and location = 'Moss Dekk AS'";
$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE misc = '' $sqlWhere");
if(mysqli_num_rows($q) > 0) {
	$fetch1 = "sd";
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		$seenButton = '';
		$seen = 0;
		if($f['misc'] != '') {
			$misc = json_decode($f['misc'], true);
			if(isset($misc['seen'])) {
				$seen = $misc['seen'];
			}
		}
		if($seen == 0) {
			$seenButton = '<button class="btn btn-sm btn-outline-success py-0 m-0 seenButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" onclick="seen('.$f['id'].')" >Seen</button>';
		}
		$replyButton = '<button class="btn btn-sm btn-outline-danger py-0 m-0 replyButton'.$f['id'].' button'.$f['id'].'" data-id="'.$f['id'].'" data-toggle="modal" data-target="#sendMessage" onclick="reply('.$f['id'].')" >Reply</button>';
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>'.$f['date'].'</td>';
		$tr .= '<td>'.$f['location'].'</td>';
		$tr .= '<td class="username">'.$f['name'].'</td>';
		$tr .= '<td class="email">'.$f['email'].'</td>';
		$tr .= '<td>'.$f['reg_nr'].'</td>';
		$tr .= '<td>'.$f['phone'].'</td>';
		$tr .= '<td style="max-width:100px;">'.$f['subject'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['message'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['replyDate'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['replyBy'].'</td>';
		$tr .= '<td style="max-width:200px;">'.$f['replyMessage'].'</td>';
		$tr .= '<td>'.$replyButton.$seenButton.'</td>';
		$tr .= '</tr>';
		$i++;
	}
}



?>
<div style="">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Queries 
			</div>
			<div class="card-body">
				<div class="row" style="">
					<div class="input-group input-group-sm col-4" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
						</div>
						<input type="text" class="form-control" id="name" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" />
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="name">Search</button>
						</div>
					</div>
					
					<div class="input-group input-group-sm col-4">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
						</div>
						<input type="text" class="form-control" id="email2" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<button class="btn btn-outline-primary search" type="button" data-type="email">Search</button>
						</div>
					</div>
					
					<button class="btn btn-sm btn-outline-success py-0 " onclick="showQueries('old')" style="margin-right:10px;" >Old Queries</button>
					<button class="btn btn-sm btn-outline-success py-0 m-0 " onclick="showQueries('new')" >New Queries</button>
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
	
				<div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						<div class="modal-header border-bottom-0">
							<h5 class="modal-title" id="exampleModalLabel">Send Message</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form onsubmit="return false;">
							<div class="modal-body">
							<div class="form-group">
								<label for="m_username">Name</label>
								<input type="text" disabled class="form-control" id="m_username" aria-describedby="emailHelp" >
							</div>
							<div class="form-group">
								<label for="m_email">Email</label>
								<input type="email" name="email" class="form-control" id="m_email">
							</div>
							<div class="form-group">
								<label for="messagebox">Message</label>
								<textarea rows="10" id="messagebox" style="width:100%"></textarea>
							</div>
							</div>
							<div class="modal-footer border-top-0 d-flex justify-content-center">
							<input type="hidden" id="selectdId" />
							<button  onclick="replyMessage()" class="btn btn-success">Send Message</button>
							</div>
						</form>
						</div>
					</div>
					</div>
				<table class="table table-hover table-sm" style="margin-top:30px; font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Date</th>
						  <th scope="col">Location</th>
						  <th scope="col">Name</th>
						  <th scope="col">Email</th>
						  <th scope="col">Reg NR</th>
						  <th scope="col">Phone</th>
						  <th scope="col">Subject</th>
						  <th scope="col">Message</th>
						  <th scope="col">Reply Date</th>
						  <th scope="col">Reply By</th>
						  <th scope="col">Reply Message</th>
						  <th scope="col">Action</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="queryBody">
						<?php echo $tr; ?>
					  </tbody>
					</table>
			</div>
		</div>
</div>

<script>
var totalData, selectdId;

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

function reply(id)
{
	selectdId = id;
	$('#m_username').val($("#tr"+id+" .username").text());
	$('#m_email').val($("#tr"+id+" .email").text());
	$('#message').val('');
}



function showQueries(type) {
	var url = 'method=showQueries&type='+type;
	showLoadingBar(1);
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		totalData = e;
		if(e[0] == 'success') {
			showAlert('success', 'Entries found');
			$('#queryBody').html(e[1]);
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


function replyMessage() {
	var message = $('#messagebox').val();
	if(message == '' || message == null)
	{
		showAlert('danger','All Field are required');
		return;
	}
	var email = $('#m_email').val();
	var url = 'method=sendMessage&id='+selectdId+'&message='+message+'&email='+email;
	$('.close').click();
	showLoadingBar(1);
	fetchA(url, function(e) {
		hideLoadingBar();
		if(e == 'success') {
			showAlert('success', 'Success! You sent message');
		}else {
			showAlert('danger', 'Some error occurred');
		}
	});
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
		showAlert('warning', 'Field cannot be left empty');
		return;
	}
	
	showLoadingBar(1);
	var url='method=querySearch&type='+type+'&value='+value;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#queryBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});

</script>