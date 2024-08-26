<?php

$sizeOne = '';
for($i=145; $i<=355; $i=$i+10) {
	$sizeOne .= '<option value='.$i.'>'.$i.'</option>';
}

$sizeTwo = '';
for($i=30; $i<=90; $i=$i+5) {
	$sizeTwo .= '<option value='.$i.'>'.$i.'</option>';
}

$sizeThree = '';
for($i=13; $i<=27; $i++) {
	$sizeThree .= '<option value='.$i.'>'.$i.'</option>';
}

$page = 'home';
if(isset($_GET['p'])) {
	if(p($_GET['p']) != '') {
		$page = p($_GET['p']);
		if(!file_exists($page.'.php')) {
			$page = 'home';
		}
	}
}

if(isset($_GET['s1']) && isset($_GET['s2']) && isset($_GET['s3']) && isset($_GET['s'])) {
	
	$s1 = p($_GET['s1']);
	$s2 = p($_GET['s2']);
	$s3 = p($_GET['s3']);
	$season = p($_GET['s']);
	
	echo "<script>
	$(document).ready(function() {
			$('.seasonSelect').val('".$season."').change();
			$('.sizeOneSelect').val('".$s1."').change();
			$('.sizeTwoSelect').val('".$s2."').change();
			$('.sizeThreeSelect').val('".$s3."').change();
			$('.searchTyreButton').click();
	});
		</script>";
}


?>
<script>
	$(<?php echo '\'.'.$page.'Menu\''; ?>).css('border-bottom', '2px solid #73c019').css('color', '#fff');
</script>

<div class="main" style="background: url('images/backTyre6.png') no-repeat center; height:calc(100vh - 61px); background-color:#000; background-size:1200px;">
    
    <!-- <a class="popup custom-btn" href="#" data-toggle="modal" data-target="#tyreManagementModal" data-type="tyreManagement" data-title="Tyre Management"> -->
	<a class="popup custom-btn" href="#" data-toggle="modal" data-target="#accordianModal" data-type="tyreManagement" data-title="Tyre Management">
        <!-- Dekkhotell Timebestilling
        <br>
        <span class="action">og andre tjenester:</span> -->
		» BOOK TID / TJENESTER / NY KUNDE «
    </a>
	
	<div class="row" style="margin:0;">
		<div class="col-md-3">
		</div>
        <div class="col-md-6 mainTxtContainer" style="">
			<div class="mainTxtHeader" style="margin-bottom:10px; font-size:35px; background:none; text-align:center; font-weight:300; color:#eee;">
				Norges første helautomatiserte dekkutsalg
			</div>
			<div class="mainTxtDesc" style="background:none; text-align:center; color:#ddd;">
				<p>Bestill – Betal – Bytt med noen enkle tastetrykk.</p>
			</div>
		</div>
        <div class="col-md-3">
        </div>
	</div>
	
	<div class="row " style="margin:0;">
		<div class="col-md-3">
		</div><div class="col-md-6 selectContainer" style="">
			<div style=" background-color:none; height:100%;">
				<div class="select cat1" style="">
					Sesong
					<select class="frontSelect seasonSelect" data-type="season" style="">
					    <option value="summer">Sommerdekk</option>
					    <option value="winter">Vinterdekk - piggfrie</option>
						<option value="winterStudded">Vinterdekk - piggdekk</option>
						
					</select>
				</div>
				<div class="select cat2" style="">
					Bredde
					<select class="frontSelect sizeOneSelect" data-type="sizeOne" style="">
						<?php echo $sizeOne; ?>
					</select>
				</div>
				<div class="select cat3" style="">
					Profil
					<select class="frontSelect sizeTwoSelect" data-type="sizeTwo" style=" ">
						<?php echo $sizeTwo; ?>
					</select>
				</div>
				<div class="select cat4" style="">
					Dimensjon
					<select class="frontSelect sizeThreeSelect" data-type="sizeThree" style="">
						<?php echo $sizeThree; ?>
					</select>
				</div>
				<div class="select cat5" style="">
					<button class="btn btn-primary searchTyreButton" style="">
						GO
					</button>
				</div>
			</div>
		</div><div class="col-md-3"></div>
	</div>
	<div class="frontLoading" style="">
		<div style="width:50px; height:50px; margin:auto;">
			<img src="images/Rolling.gif" style="width:50px; height:50px;" />
		</div>
	</div>
	<div class="text-center">
	    <a href="#instruksjoner" style="color: #dd7000;">Hjelp til bestilling?</a>
	</div>
</div>
<div class="tyreBrand" style="padding:50px 40px; /* height:120px; */ height:auto; background:#f7f7f7; overflow:hidden; /* white-space:nowrap; */">
	<div class="" style="display:none; background:; height:100%; text-align:center;">
		<div class="brandCont" style=""><img src="images/brand1.png" /></div>
		<div class="brandCont" style=""><img src="images/brand2.png" /></div>
		<div class="brandCont" style=""><img src="images/brand3.png" /></div>
		<div class="brandCont" style=""><img src="images/brand4.png" /></div>
		<div class="brandCont" style=""><img src="images/brand5.png" /></div>
	</div>
	<?php include('tyreBrands.php'); ?>
</div>
<div class="tyreSearchResult" style="background-color:#73c019a8;">
	<div class="" style="background:none; height:100%;">
		<div class="headTxt" style=" text-align:center; font-size:22px; background:none; font-weight:400; color:#fff;">
			Resultat
		</div>
		
		<div style="text-align: center; padding-bottom:20px; padding-top: 10px;">
    		<div class="alert alert-info d-inline-block" style="font-size: 16px; text-align: center;" role="alert">
    		    
    		    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-exclamation-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                </svg>
    		    
    		    Vi har gjort det simpelt. Kun 3 dekk valg per dekk kategori. Og vår Expert anbefaling i hver kategori.
    		</div>
    	</div>
		<!--<div class="tyreCategory" style="color:#777; padding:10px; background-color:#; width:auto; text-align:center;">
			<div class="cat1" style="display:inline-block; padding:0 20px; border-right:1px solid #ccc;">
				Budget
			</div>
			<div class="cat1" style="color:#000; display:inline-block; padding:0 20px; border-right:1px solid #ccc;">
				Mellom
			</div>
			<div class="cat1" style="display:inline-block; padding:0 20px; ">
				Premium
			</div>
		</div>-->
		
		
		<div class="row " style="margin:0;">
			<div class="list-group col text-center" id="list-tab" role="tablist" style="display:inline-block; padding-right:0; margin:auto;  text-align:center; width:100%;">
			    <span style="font-size: 25px; font-weight: bold; padding-right: 5px; color: #9e9e9e;">Velg kategori:</span>
				<a class="list-group-item list-group-item-action active" id="list-budget-list" data-toggle="list" href="#list-budget" role="tab" aria-controls="budget" style="border:none; width:auto; display:inline-block; border-radius:0.25rem 0 0 0.25rem; border-right:1px solid #eee; ">Budsjettdekk<div class="resultNum budgetNum" >0</div></a>
				<a class="list-group-item list-group-item-action" id="list-mellom-list" data-toggle="list" href="#list-mellom" role="tab" aria-controls="mellom" style="border:none;width:auto; display:inline-block; border-radius:0; border-right:1px solid #eee;">Kvalitetsdekk<div class="resultNum mellomNum" >0</div></a>
				<a class="list-group-item list-group-item-action" id="list-premium-list" data-toggle="list" href="#list-premium" role="tab" aria-controls="premium" style="border:none; width:auto; display:inline-block; margin-bottom:-1px; border-radius:0 0.25rem 0.25rem 0;">Premiumdekk<div class="resultNum premiumNum" >0</div></a>
			</div>
		</div>
				<div class="row" style="margin:0;">
					<div class="tab-content" id="nav-tabContent" style="width:100%;">
						<div class="tab-pane fade show active" id="list-budget" role="tabpanel" aria-labelledby="list-budget-list">
							
								<div class="budgetContainer" style="margin:0; text-align:center;">
									b
								</div>
							
						</div>
						<div class="tab-pane fade" id="list-mellom" role="tabpanel" aria-labelledby="list-mellom-list">
							
								<div class=" mellomContainer" style="margin:0; text-align:center;">
									<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard" style="border-radius:3px; border:1px solid #ccc; display:inline-block; width:250px; height:auto; background:#eee; margin:15px; box-shadow:0px 2px 4px #999;">
											<div class="tyreImg" style="background:url('images/backTyre.jpg') no-repeat center; background-size:contain; border-radius:3px 3px 0px 0px; padding:10px; width:100%; height:200px; background-color:#fff;">
												
											</div>
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> Mukim </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> 255/55-15 </div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">A</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">B</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">C</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK 730,00</b></div>
											<div class="tyreButtons" style="background:#ccc; padding:5px 10px 5px 10px;">
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$f['price'].'" data-tyreid="'.$f['id'].'">Buy</div>
												<a href=""><div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Details</div></a>
											</div>
										</div>
									</div>
									<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard" style="border-radius:3px; border:1px solid #ccc; display:inline-block; width:250px; height:auto; background:#eee; margin:15px; box-shadow:0px 2px 4px #999;">
											<div class="tyreImg" style="background:url('images/backTyre.jpg') no-repeat center; background-size:contain; border-radius:3px 3px 0px 0px; padding:10px; width:100%; height:200px; background-color:#fff;">
												
											</div>
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> Mukim </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> 255/55-15 </div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">A</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">B</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">C</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK 730,00</b></div>
											<div class="tyreButtons" style="background:#ccc; padding:5px 10px 5px 10px;">
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Buy</div>
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Details</div>
											</div>
										</div>
									</div>
									<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard" style="border-radius:3px; border:1px solid #ccc; display:inline-block; width:250px; height:auto; background:#eee; margin:15px; box-shadow:0px 2px 4px #999;">
											<div class="tyreImg" style="background:url('images/backTyre.jpg') no-repeat center; background-size:contain; border-radius:3px 3px 0px 0px; padding:10px; width:100%; height:200px; background-color:#fff;">
												
											</div>
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> Mukim </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> 255/55-15 </div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">A</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">B</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">C</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK 730,00</b></div>
											<div class="tyreButtons" style="background:#ccc; padding:5px 10px 5px 10px;">
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Buy</div>
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Details</div>
											</div>
										</div>
									</div>
									<div class="tyreResultContainer" style="display:inline-block; text-align:center; margin-top:20px; background:none;">
										<div class="tyreCard" style="border-radius:3px; border:1px solid #ccc; display:inline-block; width:250px; height:auto; background:#eee; margin:15px; box-shadow:0px 2px 4px #999;">
											<div class="tyreImg" style="background:url('images/backTyre.jpg') no-repeat center; background-size:contain; border-radius:3px 3px 0px 0px; padding:10px; width:100%; height:200px; background-color:#fff;">
												
											</div>
											<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> Mukim </div>
											<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> 255/55-15 </div>
											<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
												<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">A</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">B</div>
												</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
													<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
														<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
													</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">C</div>
												</div>
											</div>
											<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK 730,00</b></div>
											<div class="tyreButtons" style="background:#ccc; padding:5px 10px 5px 10px;">
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Buy</div>
												<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Details</div>
											</div>
										</div>
									</div>

								</div>
							
						</div>
						<div class="tab-pane fade" id="list-premium" role="tabpanel" aria-labelledby="list-premium-list">
							
								<div class="premiumContainer" style="margin:0; text-align:center;">
								 p
								</div>
							
						</div>
						
					</div>
				</div>
				
				
				
				
		<!--<div class="tyreResultContainer" style="display:none; text-align:center; margin-top:20px; background:none;">
			<div class="tyreCard" style="border-radius:3px; border:1px solid #ccc; display:inline-block; width:250px; height:auto; background:#eee; margin:15px; box-shadow:0px 2px 4px #999;">
				<div class="tyreImg" style="background:url('images/backTyre.jpg') no-repeat center; background-size:contain; border-radius:3px 3px 0px 0px; padding:10px; width:100%; height:200px; background-color:#fff;">
					
				</div>
				<div class="tyreModel" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:bold;"> Mukim </div>
				<div class="tyreSize" style="font-size:15px; background:none;  padding:0 10px;"> 255/55-15 </div>
				<div class="icons" style="margin-top:10px; background:none; padding:0 0px;">
					<div class="iconContainer" style="display:inline-block; margin:0 10px;  vertical-align:top;">
						<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
							<img src="images/fuel.jpg" style="width:100%; display:inline-block;" />
						</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">A</div>
					</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
						<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
							<img src="images/grip.jpg" style="width:100%; display:inline-block;" />
						</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">B</div>
					</div><div class="iconContainer" style="display:inline-block; margin:0 10px; vertical-align:top;">
						<div class="icon1" style="display:inline-block; background:#ccc; width:30px; height:30px; margin:0;">
							<img src="images/noise.jpg" style="width:100%; display:inline-block;" />
						</div><div class="icon1" style="display:inline-block; font-weight:bold; background:#ccc; width:30px; height:30px; margin:0; vertical-align:top; padding-top:4px;">C</div>
					</div>
				</div>
				<div class="tyrePrice" style="background:none; margin:10px 0 5px 0; padding:0 10px; font-weight:;"> Price: <b>NOK 730,00</b></div>
				<div class="tyreButtons" style="background:#ccc; padding:5px 10px 5px 10px;">
					<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Buy</div>
					<div class="" style="cursor:pointer; color:#fff; border-radius:3px; background:#999; padding:3px 10px; display:inline-block; margin:0px 10px;">Details</div>
				</div>
			</div>
		</div>
		-->
	</div>
</div>
<div class="imgTxt" id="instruksjoner" style="">
	<div class="row" style="margin:0; height:100%;">
		<div class="col-md-5 " style="background:none; height:100%; text-align:center;">
			<img src="images/tyreService.jpg" style="width:100%; box-shadow: 2px 2px 4px #999;" />
		</div><div class="col-md-7 imtTxtDesc" style="">
			<div style="background:none; font-size:30px; font-weight:300; color:#333; ">
				Slik Fungerer Det
			</div>
			<div style="margin-top:10px; background:none; color:#555;">
				<ol>
                <li>VELG RIKTIG DEKKST&Oslash;RRELSE</li>
                <li>VELG &Oslash;NSKET DEKK ( budgetdekk, kvalitetsdekk eller premiumdekk)</li>
                <li>FYLL UT INFO OG VELG ANTALL DEKK</li>
                <li>VELG OM DU &Oslash;NSKER OMLEGG OG AVBALANSERING ( lik antall som &oslash;nsket antall dekk)</li>
                <li>VELG TID OG DATO.</li>
                <li>VELG BETALINGSM&Aring;TE</li>
                <li>M&Oslash;T OPP HOS OSS I SKREDDERVEIEN 5,1537 MOSS TIL AVTALT TID.</li>
                <li>EN MONT&Oslash;R VIL KOMME BORT TIL DEG OG TA OVER BILEN</li>
                <li>DU SITTER OG VENTER MENS DU TAR EN KOPP KAFFE.</li>
                <li>N&Oslash;KKEL VIL BLI LEVERT N&Aring;R BILEN ER KLAR.</li>
                </ol>
                
                <p><strong>NB: VÅRE ONLINE PRISER GJELDER KUN VED OVERNEVNT PROSSEDYRE. KUNDEMOTTAK ER IKKE TILGJENGELIG FOR ONLINE KUNDER.</strong></p>
			</div>
		</div>
	</div>
</div>
<div class="imgTxt" style="">
	<div class="row" style="margin:0; height:100%;">
		<div class="col-md-7 imgTxtDesc2" style="">
			<div style="background:none; font-size:30px; font-weight:300; color:#333; text-align: left;">
				Våre rutiner ved Dekkskift
			</div>
			<div style="margin-top:10px; background:none; color:#555; text-align: left;">
				<ol>
                <li>vi l&oslash;fter bilen p&aring; godkjente l&oslash;ftebord.</li>
                <li>hjulboltene blir trekket ut med mutter trekker (normal pipe)</li>
                <li>hjulene blir tatt av og satt deretter p&aring; igjen n&aring;r de er klare. <br /> (i noen tilfeller m&aring; dekket sl&aring;s med gummihammer).</li>
                <li>hjulboltene blir satt tilbake ved bruk av muttertrekker og momentn&oslash;kkel. (riktig moment i henhold til bilen, vi benytter oss av Koken moment piper og kalibrerte moment n&oslash;kler).</li>
                <li>luft trykket blir sjekket og etterfylt til riktig trykk i henhold til bilen.</li>
                <li>Husk &aring; etter stramme boltene etter 60km eller kom innom oss s&aring; etter strammer vi uten noe kostnad.</li>
                </ol> 
			</div>
		</div><div class="col-md-5 imgTxtImg" style="">
			<img src="images/tyreService2.jpg" style="width:100%; box-shadow: 2px 2px 4px #999;" />
		</div>
	</div>
</div>
<div class="imgTxt" id="om-oss" style="">
	<div class="row" style="margin:0; height:100%;">
		<div class="col-md-5 " style="background:none; height:100%; text-align:center;">
			<img src="images/moss-dekk-video%20HD.jpg" style="width:100%; box-shadow: 2px 2px 4px #999;" />
		</div><div class="col-md-7 imtTxtDesc" style="">
			<div style="background:none; font-size:30px; font-weight:300; color:#333; ">
				Om oss
			</div>
			<div style="margin-top:10px; background:none; color:#555;">
				<p>Moss Dekk AS sine medarbeidere har høy fokus på kvalitet på sitt arbeid, effektivitet og kundetilfredshet. Vi utfører service på hjul som møter kundenes forventninger og myndighetenes krav.</p>
				
				<p>Kom gjerne innom våre NYE lokaler i Skredderveien 5 , 1534 Moss!</p>
				
				<div class="row mt-5">
				    <div class="col-md-4">
        				<p>Våre tjenester:</p>
        				<ul>
                        <li>Hjulskift</li>
                        <li>Dekkomlegg</li>
                        <li>Avbalansering</li>
                        <li>Reperasjon av dekk</li>
                        <li>Dekkhotell</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-8">
                        <p>Vi har salg av:</p>
                        <ul>
                        <li>Dekk</li>
                        <li>Felg</li>
                        <li>Hjulbolter/muttere</li>
                        <li>L&aring;sebolter</li>
                        <li>Senterringer</li>
                        <li>TPMS &ndash; Dekktrykksensorer</li>
                        </ul>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<div class="contactCont" style="">
	<div class="row" style="margin:0;">
		<div class="col-6 col-md-3" style="text-align:center; ">
			<i class="fa fa-phone" style="font-size:40px; display:block;"></i>
			<div class="" style="">450 22 450</div>
		</div><div class="col-6 col-md-3" style="text-align:center;">
			<i class="fa fa-envelope" style="font-size:40px; display:block; "></i>
			<div class="" style="">post@mossdekk.no</div>
		</div><div class="col-6 col-md-3" style="text-align:center; ">
			<i class="fa fa-map-marker" style="font-size:40px; display:block;"></i>
			<div class="" style="">skredderveien 5, 1537 Moss</div>
		</div><div class="col-6 col-md-3" style="text-align:center; ">
			<i class="fa fa-facebook-square" style="font-size:40px; display:block;"></i>
			<div class="" style="">/mossdekk</div>
		</div>
	</div>
</div>


<?php include('buyTyreModal.php'); //include('warehouseModal.php'); ?>

<script>
	$(window).on('scroll', function () {
		if(document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
			$('.navTop').addClass('navTopScroll');
		}else {
			$('.navTop').removeClass('navTopScroll');
		}
	});
</script>