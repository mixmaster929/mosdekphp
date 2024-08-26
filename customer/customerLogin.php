<div class="" style=" margin:20px auto;">
	<div class="row" style="">
		<div class="col" style="">
		</div><div class="col" style=" text-align:center;">
			<img src="../images/autobutlerOLD2.png" style="width:300px;" />
		</div><div class="col"></div>
	</div>
	
	<div class="row" style="margin-top:20px;">
		<div class="col" style="">
		</div><div class="col" style="padding-top:30px;background-color:#fcfcfc; text-align:center;">
			<?php echo $alertMsg; ?>
			<form method="POST">
				<div class="form-group">
					<label for="username">RegNr</label>
					<input type="text" class="form-control" name="username" aria-describedby="emailHelp" placeholder="Enter regNr">
					
				</div>
				<div class="form-group">
					<label for="pass">Password</label>
					<input type="password" class="form-control" name="pass" placeholder="Password">
				</div>
				
				<a href="../"><input type="button" class="btn btn-warning text-white" value="Go Back To Home" /></a>
				<input type="submit" name="submit" class="btn btn-primary" />
				
			</form>
			
		</div><div class="col"></div>
	</div>
</div>