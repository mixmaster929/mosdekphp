<?php 
	$customerID = $_SESSION['customerID'];
	$f = mysqli_fetch_array_n(mysqli_query($con, "SELECT * FROM shop_customers WHERE id=$customerID"), MYSQLI_ASSOC);
	$customerName = $f['fullName'];
?>
<div class="container-fluid navbar-top">
	<div class="row border-bottom">
		<div class="col" style="background-color:#f7f7f7; height:30px; position:fixed; top:0;" >
			<div class="row  h-100 px-1">
				<div class="col-auto text-left small pt-1" >
				<?php echo $customerName; ?>
				</div>
				<div class="col text-right small pt-1" style="display:none;">
					<div class="d-inline-block px-1" >
						Cart
					</div>
					<div class="d-inline-block px-1">
						Register
					</div>
					<div class="d-inline-block px-1">
						Login
					</div>
				</div>
			</div>
		</div>
	</div>
</div>