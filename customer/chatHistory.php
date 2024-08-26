<?php

$con = dbCon();

$tr = '';
$i = 1;
$customerID = $_SESSION['customerID'];
$_regNr = '';

if(mysqli_num_rows(mysqli_query($con, "SELECT * FROM shop_customers WHERE id = $customerID")) > 0)
{
    $fetch = mysqli_fetch_all_n(mysqli_query($con, "SELECT * FROM shop_customers WHERE id = $customerID"), MYSQLI_ASSOC);
    $regNr = $fetch[0]['regNr'];
    


$q = mysqli_query($con, "SELECT * FROM shop_contact WHERE reg_nr = '$regNr'");
if(mysqli_num_rows($q) > 0) {
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td>'.$f['name'].'</td>';
		$tr .= '<td>'.$f['reg_nr'].'</td>';
		$tr .= '<td>'.$f['date'].'</td>';
		$tr .= '<td>'.$f['replyDate'].'</td>';
		$tr .= '<td>'.$f['replyBy'].'</td>';
		$tr .= '<td>'.$f['message'].'</td>';
		$tr .= '<td style="max-width:100px;">'.$f['replyMessage'].'</td>';
		$tr .= '</tr>';
		$i++;
	}
}
}
?>
<div style="">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				<p>Queries </p>
				<div class="btn btn-success"  data-toggle="modal" data-target="#sendMessage" style="position:absolute; right:10px; top:5px;">Send Query</div >
			</div>
			<div class="card-body">
				
				<div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						<div class="modal-header border-bottom-0">
							<h5 class="modal-title" id="exampleModalLabel">Send Message</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form onsubmit="return false">
							<div class="modal-body">
						
							<div class="form-group">
								<label for="subject">Subject</label>
								<input type="text" class="form-control" id="subject">
							</div>
							<div class="form-group">
								<label for="subject">Reg Nr</label>
								<input type="text" class="form-control" id="regNr">
							</div>
							<div class="form-group">
								<label for="messagebox">Message</label>
								<textarea rows="10" id="messagebox" class="form-control" style="width:100%"></textarea>
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
						  <th scope="col">Name</th>
						  <th scope="col">Reg Nr</th>
						  <th scope="col">Request On</th>
						  <th scope="col">Reply Date</th>
						  <th scope="col">Reply By</th>
						  <th scope="col">Query</th>
						  <th scope="col">Reply Message</th>
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


function replyMessage() {
	var emptyFields = 0;
	$('.form-control').each(function() {
		if($(this).val() == '') {
			//showModal('Empty Fields', 'All the fields are required');
			emptyFields = 1;
		}
	});
	if(emptyFields == 1)
	{
		showAlert('danger', 'All the fields are required');
		return;
	}
	var message = $('#messagebox').val();
	var subject = $('#subject').val();
	var id = <?php  echo $_SESSION['customerID']; ?>;
	var regNr = $('#regNr').val();
	
	$('.close').click();
	var url = 'method=sendMessage&id='+id+'&subject='+subject+'&message='+message+'&regNr='+regNr;
	showLoadingBar(1);
	fetchC(url, function(e) {
		hideLoadingBar();
		console.log(e);
		if(e == 'success') {
	
			showAlert('success', 'Success! You sent message');
		}else {
		    die(print_r(e));
			showAlert('danger', 'Some error occurred');
		}
	});
}





</script>