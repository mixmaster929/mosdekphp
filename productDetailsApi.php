<?php

$con = dbCon();

if(!isset($_GET['pID'])) { header('location:?p=home'); exit; }
$tyreID = (int)p($_GET['pID']);

$q = mysqli_query($con, "SELECT * FROM shop_tyres_api WHERE id=$tyreID");
if(mysqli_num_rows($q) == 0) { header('location:?p=home');  exit; }
	
$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
$tyrePrice = $f['price'];
$tyreBrand = $f['brand'];
$tyreModel = $f['model'];
$tyreSize = $f['size'];
$fuel = $f['fuel'];
$speed = $f['speed'];
$grip = $f['grip'];
$noise = $f['noise'];
$tyreImg = $f['image'];
$load = $f['load'];
$width = $f['width'];
$profile = $f['profile'];
$inches = $f['inches'];
$euClass = $f['euClass'];

if($f['season'] == 'summer') {
	$season = 'Sommerdekk';
}else if($f['season'] == 'winter') {
	$season = 'Vinterdekk - piggfrie';
}elseif($f['season'] == 'winterStudded') {
	$season = 'Vinterdekk - piggdekk';
}

$tyreInfo = '';

	// Get price adjustments from misc tabel, brand discount and fixed %
    $attributes = mysqli_query($con, "SELECT `property`,`attribute1`,`attribute2`,`attribute3` FROM shop_misc WHERE attribute5 = 'Moss Dekk AS' and (property = 'shopTyresApiPriceRegulator' OR property = 'shopTyresApiBrandDiscount')");
    $attributeValues = mysqli_fetch_all_n($attributes, MYSQLI_ASSOC);

    $priceRegulatorPercent = 0;
    $priceRegulatorFixed = 0;
    $brandDiscounts = [];

    foreach ($attributeValues as $attr) {
        if ($attr['property'] == 'shopTyresApiPriceRegulator') {
            $priceRegulatorPercent = $attr['attribute2'];
            $priceRegulatorFixed = $attr['attribute3'];
        } else if ($attr['property'] == 'shopTyresApiBrandDiscount') {
            $brandDiscounts[$attr['attribute1']] = $attr['attribute2'];
        }
    }
    $brandMultiplier = isset($brandDiscounts[$tyreBrand]) ? 1 - ($brandDiscounts[$tyreBrand] / 100) : 1; // Convert the discount to a multiplier
$customerPrice = ($tyrePrice  * $brandMultiplier * (1 + $priceRegulatorPercent/100) + $priceRegulatorFixed) ;
$customerPrice *= 1.25; // Add moms (25%)

if($f['stock'] > 0) {
	$stock = 'På lager | Levering ca. 3-5 dager';
    $delayCalc = '<input type="hidden" class="delay" value="'.$f['delay'].'">';
	$buyButton = '<button href="#" class="btn btn-primary" data-toggle="modal" data-target="#buyTyreModal" data-price="'.$customerPrice.'" data-tyreidApi="'.$f['id'].'" data-tyreid="0">Kjøp Dekk</button>';
}else {
	$stock = '<span style="color:red; font-weight:600;">Utsolgt</span>';
	$buyButton = '<button href="#" class="btn btn-primary disabled" >Buy Tyre</button>';
}



    



$sizeOne = $width;

$sizeTwo = $profile;
$sizeThree = $inches;

$backLink = 'index.php?s1='.$sizeOne.'&s2='.$sizeTwo.'&s3='.$sizeThree.'&s='.$f['season'];

?>

<div class="productDetailsContainer" style="">
	<div class="detailsContainer" style="border:1px solid #ccc; background-color:#fff; border-radius:3px; width:100%; padding:20px; box-shadow: 0px 10px 11px 2px #dedede;" >
		<div class="title" style="border-bottom:1px solid #ccc;  width:100%; padding:10px; margin:-20px 0 0px ;  text-transform:uppercase; font-weight:bold; color:#555;" >
			<?php echo $tyreBrand.' - '.$tyreModel.' - '.$tyreSize.' '.$speed; ?>
		</div>
		<div class="row" style="margin:0;">
			<div class="col-sm-5 align-self-center pdImgCont" style="">
				<div class="" style="vertical-align:top; height:90%; text-align:center;">
					<img src="<?php echo $tyreImg; ?>" style="user-select:none; max-width:90%; "/>
				</div>
			</div><div class="col-sm-7 pdDetailsCont" style="">
				<div class="desc" style="margin:-20px -20px 20px -20px;  padding:10px 10px 10px 20px; border-bottom:1px solid #ddd; font-size:15px; color:#777;">
					<?php echo $stock;
					      echo $delayCalc;
					?>
				</div>
				<div class="row text-center pdIconsCont" style="">
						<div class="col-6 col-sm" style="">
							<img src="images/fuel.jpg" style=" opacity:0.8;"/>
							<br> Drivstofforbruk <br>
							<span style="font-size:30px; text-transform:uppercase; font-weight:bold;"><?php echo $fuel; ?></span>
						</div>
						<div class="col-6 col-sm" style="">
							<img src="images/grip.jpg" style=" opacity:0.8;"/>
							<br> Våtgrep <br>
							<span style="font-size:30px; text-transform:uppercase; font-weight:bold;"><?php echo $grip; ?></span>
						</div>
						<div class="col-12 col-sm" style="">
							<img src="images/noise.jpg" style=" opacity:0.8;"/>
							<br> Støynivå <br>
							<span style="font-size:30px; text-transform:uppercase; font-weight:bold;"><?php echo $noise; ?>dB</span>
						</div>
				</div>
			<!--	<div class="desc" style="margin:-20px -20px 0px -20px;  padding:10px 10px 10px 20px; border-bottom:1px solid #ddd; font-size:15px; color:#777;">
					<h6 style="color:#333;">Dekk Info:</h6>
					    <textarea style="width:100%" disabled rows = '10'><?php echo $tyreInfo; ?></textarea>
				</div>-->
				<div class="row" style="margin:0 -20px 20px -20px; padding:10px 20px 10px 10px; border-bottom:1px solid #ddd;">
					<div class="col-6 col-lg-3" style="">Load: <span style="font-weight:600;"><?php echo $load; ?></span>
					</div><div class="col-6 col-lg-3" style="">Bredde: <span style="font-weight:600;"><?php echo $width; ?></span>
					</div><div class="col-6 col-lg-3" style="">Profil: <span style="font-weight:600;"><?php echo $profile; ?></span>
					</div><div class="col-6 col-lg-3" style="">Diameter: <span style="font-weight:600;"><?php echo $inches; ?></span>
					</div><div class="col-6 col-lg-3" style="">Eu Klasse: <span style="font-weight:600;"><?php echo $euClass; ?></span>
					</div><div class="col-12 col-lg-6" style="">Season: <span style="font-weight:600;"><?php echo $season; ?></span></div>
				</div>
				<div style="margin-bottom:10px; font-size:18px;">Pris: kr <?php echo round($customerPrice); ?> / stk</div>
				<!--<button href="#" class="btn btn-primary" data-toggle="modal" data-target="#buyTyreModal" data-price="<?php echo round($customerPrice); ?>" data-tyreid="<?php echo $tyreID; ?>">Buy Tyre</button>-->
				<?php echo $buyButton; ?>
				<a href="<?php echo $backLink; ?>"><button href="#" class="btn btn-outline-warning" style="float:right;">Gå tilbake</button></a>
				
			</div>
		</div>
	</div>

</div> 

<?php include('buyTyreModal.php'); ?>
