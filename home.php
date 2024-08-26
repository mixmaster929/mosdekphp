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
	
    $s1 = isset($_GET['s1']) ? p($_GET['s1']) : null;
    $s2 = isset($_GET['s2']) ? p($_GET['s2']) : null;
    $s3 = isset($_GET['s3']) ? p($_GET['s3']) : null;
    $season = isset($_GET['s']) ? p($_GET['s']) : null;

}

?>
<script>
	$(<?php echo '\'.'.$page.'Menu\''; ?>).css('border-bottom', '2px solid #73c019').css('color', '#fff');
</script>

<div class="main" style="background-color:black; background-size:800px;">
    
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
			<div class="mainTxtHeader" style="margin-bottom:10px; font-size:35px; background:none; text-align:center; font-weight:300; color:white;">
			Norges første helautomatiserte dekkutsalg
			</div>
			<div class="mainTxtDesc" style="background:none; text-align:center; color:white;">
				<p>Bestill – Betal – Bytt med noen enkle tastetrykk.</p>
			</div>
		</div>
        <div class="col-md-3">
        </div>
	</div>
	
<style>
.vehicle-details {
    font-family: 'Arial', sans-serif;
    background-color: #373737;
    width: 913px;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgb(35 35 35 / 90%);
}
#getTyreRecommendation {
    margin: auto;
    padding: 10px;
}
.vehicle-details .title {
    font-size: 18px;
    margin-bottom: 10px;
}

.vehicle-details .registration-number {
    font-weight: bold;
    font-size: 24px;
    color: #73c019;
    margin-bottom: 5px;
}

.vehicle-details .model {
    font-size: 18px;
    color: #ffff;
    margin-bottom: 10px;
}

.vehicle-details .tyre-size {
    font-size: 16px;
    color: #ffff;
}
.col-md-2 {
    flex-basis: 16.66667%;
    max-width: 16.66667%;
    padding: 0 10px;
}
.col-md-12 {
    padding-bottom: 5px;
}

.imageContainer {

    flex-basis: 50%;
}
.col-md-12 {
    padding-right: 0px;
    padding-left: 0px;
}
.cat5 {
    float: left;
}

#filterPanel {
    padding: 10px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    border-radius: 3px;
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 4px rgb(35 35 35 / 90%);
}
#filterPanel label {
    margin-bottom: 0rem;
}


.resetButton {
    cursor: pointer;
    color: #fff;
    border-radius: 3px;
    border: 0px;
    background: #a43232; /* Ändrad till en rödaktig färg för "Återställ" */
    padding: 3px 10px;
    display: inline-block;
    margin: 0px 10px;
    transition: background 0.3s; /* Lägg till en övergång för en mjuk ändring av bakgrundsfärgen vid hover */
}

.resetButton:hover {
    background: #8a2929; /* Mörkare nyans av röd vid hover */
}

.applyFilterButton {
    cursor: pointer;
    border: 0px;
    color: #fff;
    border-radius: 3px;
    background: #327a8a; /* Valde en blåaktig färg för "Tillämpa filter" */
    padding: 3px 10px;
    display: inline-block;
    margin: 0px 10px;
    transition: background 0.3s; /* Mjuk övergång av bakgrundsfärg vid hover */
}

.applyFilterButton:hover {
    background: #296570; /* Mörkare nyans av blå vid hover */
}


#filterPanel select {
    margin-right: 10px;
}
#divWidth40 {
    max-width: 50%;
}
#divWidth60 {
    max-width: 50%;
}
button#goButton {
    margin: auto;
    margin-top: 10px;
    background-color: #373737;
    box-shadow: 0 2px 4px rgb(35 35 35 / 90%);
    width: 913px;
}
button#goButton:hover {
    background: #232323;
}
.imageContainer img {
    max-width: 440px;
    height: auto;
    padding-top: 20px;
    float: right;
}

#tireSizeContainer {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
}
div#nav-tabContent {
    display: block;
}
div#list-budget {
    margin-left: auto;
    min-width: 280px;
}
div#list-mellom {
    min-width: 280px;
}
div#list-premium {
    margin-right: auto;
    min-width: 280px;
}
@media (max-width: 767px) {
    #filterPanel {
        flex-wrap: wrap;
    }
    .imageContainer {
        height: auto;
        flex-basis: 100%;
    }
    #getTyreRecommendation {
    margin-top: 50px;
    max-width: 100%;
    }
    div#nav-tabContent {
    display: block;
}
    .vehicle-details, .imageContainer img {
    max-width: 100%;
    }
    .col-md-12 {
        padding-right: 0;
        padding-left: 0;
    }
    .cat5 {
        float: none;
    }
    div#list-premium, div#list-budget {
    margin-left: 0px;
    margin-right: 0px;
}
    .cat1,.cat2,.cat3,.cat4,.sizeOneSelect,.sizeThreeSelect {
    width: 100%;
}
    #divWidth40,
    #divWidth60 {
        max-width: 100%;
    }
    .tyreResultContainer{
        width: 50%;
    }
}

</style>

<div id='tireSizeContainer' class="row" style="margin: 0;margin-top: 20px;">
    <div class="col-md-12" id='getTyreRecommendation'></div>
    <div id='divWidth60' class="col-md-6 imageContainer">
        <div class="imageContainer">
            <img src="images/mossdekk_tyresearch.png" alt="Däckbild">
        </div>
    </div>
    <div id='divWidth40' class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="select cat1" style="">
                    Sesong
                    <select class="frontSelect seasonSelect" data-type="season" style="">
                        <option value="summer">Sommerdekk</option>
                        <option value="winter">Vinterdekk - piggfrie</option>
                        <option value="winterStudded">Vinterdekk - piggdekk</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="select cat2" style="">
                    Bredde
                    <select class="frontSelect sizeOneSelect" data-type="sizeOne" style="">
                        <?php echo $sizeOne; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="select cat3" style="">
                    Profil
                    <select class="frontSelect sizeTwoSelect" data-type="sizeTwo" style=" ">
                        <?php echo $sizeTwo; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="select cat4" style="">
                    Dimensjon
                    <select class="frontSelect sizeThreeSelect" data-type="sizeThree" style="">
                        <?php echo $sizeThree; ?>
                    </select>
                </div>
            </div>
            
            
            
        </div>

    </div>
    <div class="col-md-12 d-flex justify-content-center">
        <button id="goButton" class="btn btn-primary searchTyreButton" style="">
                        GO
        </button>
    </div>           
                    
                
</div>



    <div class="col-md-3"></div>
</div><div class="col-md-3"></div>
	</div>
	<div class="frontLoading" style="">
		<div style="width:50px; height:50px; margin:auto;">
			<img src="images/Rolling.gif" style="width:50px; height:50px;" />
		</div>
	</div>
	<div class="text-center" style="background: url('images/backTyre6.png') no-repeat center; height:calc(600px); background-color:#000; background-size:1200px;">
	    <a href="#instruksjoner" style="color: #73c019; height: 40vh;display: block; margin: 10px;">Hjelp til bestilling?</a>
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
<div class="tyreSearchResult" style="background-color:rgb(45 45 45);;">
	<div class="" style="background:none; height:100%;">
		<div class="headTxt" style=" text-align:center; font-size:22px; background:none; font-weight:400; color:#fff;">
			Resultat
		</div>
		
		<div style="text-align: center; padding-bottom:20px; padding-top: 10px;">
    		<div class="alert alert-info d-inline-block" style="box-shadow: 0 2px 4px rgb(0 0 0 / 90%); font-size: 16px; text-align: center;" role="alert">
    		    
    		    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-exclamation-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                </svg>
    		    
    		    Vi har gjort det simpelt. Kun 3 kategorier. Og vår Expert anbefaling i hver kategori.
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
		<div id="filterPanel">
    <label for="speedFilter">Min Hastighet:</label>
    <select id="speedFilter">
        <!-- Varje möjlig hastighet läggs till som ett val -->
        <option value="K">K</option>
        <option value="M">M</option>
        <option value="N">N</option>
        <option value="P">P</option>
        <option value="Q">Q</option>
        <option value="R">R</option>
        <option value="S">S</option>
        <option value="T">T</option>
        <option value="U">U</option>
        <option value="H">H</option>
        <option value="V">V</option>
        <option value="W">W</option>
        <option value="Y">Y</option>
        <option value="ZR">ZR</option>
    </select>

    <label for="loadFilter">Min Lasting:</label>
    <select id="loadFilter">
        <!-- Belastningsvärden från 50 till 120 -->
        <option value="50">50</option>
        <option value="60">60</option>
        <option value="70">70</option>
        <option value="80">80</option>
        <option value="85">85</option>
        <option value="86">86</option>
        <option value="87">87</option>
        <option value="88">88</option>
        <option value="89">89</option>
        <option value="90">90</option>
        <option value="91">91</option>
        <option value="92">92</option>
        <option value="93">93</option>
        <option value="94">94</option>
        <option value="95">95</option>
        <option value="96">96</option>
        <option value="97">97</option>
        <option value="98">98</option>
        <option value="99">99</option>
        <option value="100">100</option>
        <option value="101">101</option>
        <option value="102">102</option>
        <option value="103">103</option>
        <option value="104">104</option>
        <option value="105">105</option>
        <option value="106">106</option>
        <option value="107">107</option>
        <option value="108">108</option>
        <option value="109">109</option>
        <option value="110">110</option>
        <option value="111">111</option>
        <option value="112">112</option>
        <option value="113">113</option>
        <option value="114">114</option>
        <option value="115">115</option>
        <option value="116">116</option>
        <option value="117">117</option>
        <option value="118">118</option>
        <option value="119">119</option>
        <option value="120">120</option>

    </select>
    
    <div> 
        <button id="applyFilter" class="applyFilterButton m-1">Bruk filter</button>
        <button id="resetFilter" class="resetButton m-1">Fjern filter</button>
    </div> 
    <div> 
        <button class="detailsButton m-1" id="toggleBudgetButton" style="background-color: rgb(48, 135, 51)";>Budsjett</button>
        <button class="detailsButton m-1" id="toggleKvalitetButton" style="background-color: rgb(48, 135, 51)";>Kvalitet</button>
        <button class="detailsButton m-1" id="togglePremiumButton" style="background-color: rgb(48, 135, 51)";>Premium</button>
    </div>


</div>

		
		<div class="row " style="margin:0;">
			<div class="list-group col text-center" id="list-tab" role="tablist" style="display:none; padding-right:0; margin:auto;  text-align:center; width:100%;">
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
						<div class="tab-pane fade show active" id="list-mellom" role="tabpanel" aria-labelledby="list-mellom-list">
							
								<div class="mellomContainer" style="margin:0; text-align:center;">
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
						<div class="tab-pane fade show active" id="list-premium" role="tabpanel" aria-labelledby="list-premium-list">
							
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
				<p>Moss Dekk sine medarbeidere har høy fokus på kvalitet på sitt arbeid, effektivitet og kundetilfredshet. Vi utfører service på hjul som møter kundenes forventninger og myndighetenes krav.</p>
				
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
			<div class="" style="">post@MossDekk.no</div>
		</div><div class="col-6 col-md-3" style="text-align:center; ">
			<i class="fa fa-map-marker" style="font-size:40px; display:block;"></i>
			<div class="" style="">skredderveien 5, 1537 Moss</div>
		</div><div class="col-6 col-md-3" style="text-align:center; ">
			<i class="fa fa-facebook-square" style="font-size:40px; display:block;"></i>
			<div class="" style="">/Moss Dekk</div>
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
	
function isSpeedGreaterOrEqual(selectedSpeed, tyreSpeed) {
    const speedValues = ["K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "H", "V", "W", "Y", "ZR"];
    return speedValues.indexOf(tyreSpeed) >= speedValues.indexOf(selectedSpeed);
}
document.getElementById('resetFilter').addEventListener('click', function() {
    // Återställ dropdown-menyer till deras första värde
    document.getElementById('speedFilter').selectedIndex = 0;
    document.getElementById('loadFilter').selectedIndex = 0;
    
    // Visa alla tyreResultContainer-element
    const tyreContainers = document.querySelectorAll('.tyreResultContainer');
    tyreContainers.forEach(container => {
        container.style.display = 'inline-block';
    });
});
    function filterTyres(){
    const selectedSpeed = document.getElementById('speedFilter').value;
    const selectedLoad = parseInt(document.getElementById('loadFilter').value);

    const tyreContainers = document.querySelectorAll('.tyreResultContainer');

    tyreContainers.forEach(container => {
        const tyreSizeDiv = container.querySelector('.tyreSize');
        if (tyreSizeDiv) {
            const sizeText = tyreSizeDiv.innerText || tyreSizeDiv.textContent;
            const matched = sizeText.match(/(\d{2,3})\s*([A-Z]{1,2})/);

            if (matched) {
                const [_, load, speed] = matched;

                // Använd isSpeedGreaterOrEqual-funktionen för att jämföra hastighetsindex
                if (!isSpeedGreaterOrEqual(selectedSpeed, speed) || parseInt(load) < selectedLoad) {
                    container.style.display = 'none';
                } else {
                    container.style.display = 'inline-block';
                }
            } else {
                container.style.display = 'none';  // Dölj containern om vi inte kan extrahera något värde
            }
        }
    });
    }	
document.getElementById('applyFilter').addEventListener('click', function() {
    filterTyres();
});

//RegNr From licensplate to warehouseModal´and buytyremodal
$(document).ready(function() {
  var regNrWarehouseModal = '<?php echo isset($_SESSION["regNrLicensplate"]) ? $_SESSION["regNrLicensplate"] : ''; ?>';
      <?php if (isset($s1)): ?>
        $('.seasonSelect').val('<?php echo $season; ?>').change();
        $('.sizeOneSelect').val('<?php echo $s1; ?>').change();
        $('.sizeTwoSelect').val('<?php echo $s2; ?>').change();
        $('.sizeThreeSelect').val('<?php echo $s3; ?>').change();
        $('.searchTyreButton').click();
    <?php endif; ?>
    
  function getTyreRecommendation(regNr) {
      
        //showLoadingBar(); // Antaget att du har en laddningsindikator
        console.log('Reg API '+regNr);
        if(regNr){
            var message = "";
            var vehicleDetails = "";
            var url = 'method=vegvesenAPIGet&regNr='+regNr;
            
        	fetch(url, function(result) {
        	//	hideLoadingBar();
        		var data = JSON.parse(result);
        		if(data['status'] == 'success') {
                    // Kontrollera vilken av load1 och load2 som är störst
                    let maxLoad = Math.max(data.load1, data.load2);
                    // Kontrollera vilken av speed1 och speed2 som kommer sist i alfabetet
                    let maxSpeed = (data.speed1 > data.speed2) ? data.speed1 : data.speed2;
                    
        		    if(data['size1'] != data['size2'])
        		    {
        		        var twoTyres = 1;
        		          message = `<strong>Kjøretøydetaljer<br>${data.regNr}</strong> - ${data.brand} ${data.model}<br> Standard fordekk: ${data.size1} ${data.speed1} ${data.load1}  Bakdekk: ${data.size2} ${data.speed2} ${data.load2}`;
        		    message += "<br>Standardinformasjon er nå forhåndsutfylt.";
                    vehicleDetails = `
                    <div class="vehicle-details col-md-12" id="getTyreRecommendation">
                        <div class="registration-number">${data.regNr}</div>
                        <div class="model">- <strong>${data.brand} ${data.model}</strong></div>
                        <div class="tyre-size">Standard fordekk - <strong>${data.size1}</strong> Hastighet - <strong>${data.speed1}</strong> Lastekode -  <strong>${data.load1}</strong> <br> Bakdekk - <strong>${data.size2}</strong> Hastighet - <strong>${data.speed2}</strong> Lastekode - <strong>${data.load2}</strong></div>
                    </div>`;
    
        		    }
        		    if(twoTyres != 1){
    
                    message = `<strong>Kjøretøydetaljer<br> </strong>  - ${data.brand}  ${data.model}<br> Standard dekk: ${data.size1} ${maxSpeed} ${maxLoad}`;
                    message += "<br>Standardinformasjon er nå forhåndsutfylt.";
                    vehicleDetails = `
                    <div class="vehicle-details col-md-12" id="getTyreRecommendation">
                        <div class="registration-number">${data.regNr}</div>
                        <div class="model"> - <strong>${data.brand} ${data.model}</strong></div>
                        <div class="tyre-size">Standard dekk - <strong>${data.size1}</strong> Hastighet - <strong>${maxSpeed}</strong> Lastekode - <strong>${maxLoad}</strong></div>
                    </div>`;
                     
        		    } 
        		    //splitta upp size och fyll i dekksök, remove spaces
                    var sizeParts = data.size1.split(/[/R]/).map(function(value) {
                        return value.trim();
                    });
                    // För sizeParts[2], behåll bara numeriska värdena
                    var matchedNumbers = sizeParts[2].match(/\d+/);
                    if (matchedNumbers) {
                        sizeParts[2] = matchedNumbers[0];
                    }
                    
                    var s1Value = <?php echo json_encode($s1); ?>;
                    var s2Value = <?php echo json_encode($s2); ?>;
                    var s3Value = <?php echo json_encode($s3); ?>;
                    var seasonValue = <?php echo json_encode($season); ?>;
            
                    if (s1Value) {
                        $(".sizeOneSelect").val(s1Value);
                    }else $(".sizeOneSelect").val(sizeParts[0]);
                    if (s2Value) {
                        $(".sizeTwoSelect").val(s2Value); 
                    }else $(".sizeTwoSelect").val(sizeParts[1]);
                    if (s3Value) {
                        $(".sizeThreeSelect").val(s3Value); 
                    } else $(".sizeThreeSelect").val(sizeParts[2]);
                    if (seasonValue) {
                        $(".seasonSelect").val(seasonValue);  
                    }
                    
                    console.log(sizeParts[0]);
                    console.log(sizeParts[1]);
                    console.log(sizeParts[2]);
                    $("#speedFilter").val(maxSpeed);
                    $("#loadFilter").val(maxLoad);
                    
        		    document.getElementById("getTyreRecommendation").innerHTML = vehicleDetails;
        		    
        		   if (s1Value) {
            		$('.searchTyreButton').click();
            		}else document.getElementById("welcomeLicensplateText").innerHTML = message;
        		} else {
        		    console.log('Error vegvesenAPIGet')
        		    message = 'Ingen bildetaljer funnet på registreringsnummeret';
        		    document.getElementById("welcomeLicensplateText").innerHTML = message;
        		}
    
            });
        }    
    }
      $("#toggleBudgetButton").click(function() {
            var divElement = $("#list-budget");

            if (divElement.css("display") === "none") {
                divElement.css("display", "block");
                $(this).css("background-color", "#308733"); // active color
            } else {
                divElement.css("display", "none");
                $(this).css("background-color", "gray"); // grey button if not active
            }
        });    
     $("#toggleKvalitetButton").click(function() {
            var divElement = $("#list-mellom");

            if (divElement.css("display") === "none") {
                divElement.css("display", "block");
                $(this).css("background-color", "#308733"); // active color
            } else {
                divElement.css("display", "none");
                $(this).css("background-color", "gray"); // grey button if not active
            }
        });   
         $("#togglePremiumButton").click(function() {
            var divElement = $("#list-premium");

            if (divElement.css("display") === "none") {
                divElement.css("display", "block");
                $(this).css("background-color", "#308733"); // active color
            } else {
                divElement.css("display", "none");
                $(this).css("background-color", "gray"); // grey button if not active
            }
        });   
  getTyreRecommendation(regNrWarehouseModal);
  if (regNrWarehouseModal) {
    $('[data-toggle="modal"][data-target="#registerModal"]').click(function() {
      document.getElementById("regNrRegistration").value = regNrWarehouseModal;
      setTimeout(autofillRegNrNewCustomer, 1200);
    });
    $('[data-toggle="modal"][data-target="#warehouseModal"]').click(function() {
      document.getElementById("regNrDekk").value = regNrWarehouseModal;
      setTimeout(autofillRegNr, 1200);
    });
    $('#buyTyreModal').on('show.bs.modal', function (e) {
     document.getElementById("regNr").value = regNrWarehouseModal;
      setTimeout(autofillRegNrBuyTyre, 1200);
    });
  }
});
</script>