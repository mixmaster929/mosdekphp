<style>
.toolti {
    position: relative;
    display: inline-block;
    cursor: pointer;
    text-decoration: underline;
}

.toolti .tooltiptex {
    visibility: hidden;
    width: 600px;
    background-color: #ffff;
    color: black;
    padding: 10px;
    border-radius: 5px;
    position: absolute;
    box-shadow: 0px 2px 2px #ccc;
    z-index: 1;
    bottom: -100px; /* Position above the tooltip element */
    left: 50%;
   
    opacity: 0;
    transition: opacity 0.3s;
}

.toolti:hover .tooltiptex {
    visibility: visible;
    opacity: 1;
}
</style>

<?php
$con = dbCon();

$tr = '';
$i = 1;


// get time when updated
    $sql = "SELECT max(updated) FROM `shop_tyres_api`";
    $result = mysqli_query($con, $sql);
    
    $updated = ''; //standard
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $updated  = $row['max(updated)'];
    }    

$q = mysqli_query($con, "SELECT `id`,`property`, `attribute1`,`attribute2`,`attribute3` FROM `shop_misc` WHERE attribute5 = 'Moss Dekk AS' and (`property` = 'shopTyresApiDelay' or `property` = 'shopTyresApiPriceRegulator' or `property` = 'shopTyresApiBrandDiscount' or `property` = 'shopTyresApiBrands' or `property` = 'shopTyresApiRecomendedBrands') order by `property` desc");
if(mysqli_num_rows($q) > 0) {
	
	$fetch = mysqli_fetch_all_n($q, MYSQLI_ASSOC);
	foreach($fetch as $f) {
		$button = '<button class="btn btn-sm btn-warning text-white py-0 m-0 editButton'.$f['id'].' button'.$f['id'].'" onclick="edit('.$f['id'].')" >Edit</button>&nbsp;
					<button class="btn btn-sm btn-success text-white py-0 m-0 saveButton'.$f['id'].' button'.$f['id'].'" onclick="save('.$f['id'].')" style="display:none;">Save</button>&nbsp;';
		if($f['property'] == 'shopTyresApiDelay'){
		    $property = 'Delay';
		}
		else if($f['property'] == 'shopTyresApiPriceRegulator'){
		    $property = 'Price Regulator';
		}
		else if($f['property'] == 'shopTyresApiBrandDiscount'){
		    $property = 'Brand Discount';
		    $button .= '<button class="btn btn-sm btn-danger text-white py-0 m-1 deleteButton'.$f['id'].' button'.$f['id'].'" onclick="deleteRow(\'misc\', '.$f['id'].', 1)">Delete Discount</button>';
		}
		else if($f['property'] == 'shopTyresApiBrands'){
		    $property = 'Allowed Brands';
		}
		else if($f['property'] == 'shopTyresApiRecomendedBrands'){
		    $property = 'Recommended Brands';
		}		
		
		
		
		
		$tr .= '<tr id="tr'.$f['id'].'">';
		$tr .= '<td>'.$i.'</td>';
		$tr .= '<td style="max-width:200px;">'.$property.'</td>';
		
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['attribute1'].'</span>';
		$tr .= '<input type="text" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute1'].'" id="attribute1'.$f['id'].'" style="display:none; font-weight:bold; width:400px;"></td>';
		if( $property == 'Delay' || $property == 'Allowed Brands'|| $property == 'Recommended Brands'){
		$tr .= '<td></td>';
		} else {
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['attribute2'].'</span>
					<input type="number" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute2'].'" id="attribute2'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		}
		if( $property == 'Allowed Brands' || $property == 'Recommended Brands'){
		$tr .= '<td></td>';
		} else {
		$tr .= '<td><span class="txtField'.$f['id'].'">'.$f['attribute3'].'</span>
					<input type="text" min=0 class="form-control form-control-sm txtInput editField'.$f['id'].'" value="'.$f['attribute3'].'" id="attribute3'.$f['id'].'" style="display:none; font-weight:bold; width:100px;"></td>';
		}
		$tr .= '<td>'.$button.'</td>';
		$tr .= '</tr>';
		$i++;
	}
	// After the foreach loop Add button
    $tr .= '<tr>';
    $tr .= '<td></td>';
    $tr .= '<td>Brand Discount</td>';
    $tr .= '<td><input type="text" id="brandnameAdd" placeholder="Enter brand name"></td>'; 
    $tr .= '<td><input type="number" id="discountAdd" placeholder="Enter discount"></td>'; 
    $tr .= '<td><input type="text" id="brandClassAdd" placeholder="Enter category"></td>'; 
    $tr .= '<td><button class="btn btn-sm btn-primary btn-success" onclick="addBrandDiscount()">Add</button></td>'; 
    $tr .= '</tr>';
    
}



?>

<div style="max-width: 1400px;">
		<div class=" card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc;">
			<div class="card-header">
				Norges Dekk - Adjustments 
			</div>
			<div class="card-body">
				<table class="table table-hover table-sm" style="margin-top:30px; font-size:13px;">
					<thead class="thead" >
						<tr>
						  <th scope="col">#</th>
						  <th scope="col">Description</th>
						  <th scope="col">Description</th>
						  <th scope="col">Adjustment %</th>
						  <th scope="col">Adjustment Fixed</th>
						  <th scope="col"></th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody id="stockBody">
						<?php echo $tr; ?>
						<span class="toolti">Informasjon
                            <span class="tooltiptex">
                                    - Price Regulator har % og Fixed pris som påvirker prisen til kunde + mva. <br>
                                   -  Delay er hvor mange dager. <br>
                                    - Allowed Brands er hvilke dekk som skal hentes fra Norgesdata, det skjer automatisk 13:00 og 06:00 da hentes produkter og lagerbeholdning. <br>
                                    - Det er mulig å gjøre hente dekk manuelt med knappen "Get Tyres from SFTP". <br>
                                    - Brand Discount er rabatt til kunden per dekkmerke og hvilken kategori de tilhører.
                            </span>
                        </span>
					  </tbody>
					</table>
			</div>
		</div>
</div>

<div style="">
    <div class="card px-0 m-2" style="overflow:auto; box-shadow:0px 2px 2px #ccc; max-width: 1600px;">
        <div class="card-header">
            <button class="btn btn-sm btn-primary" id="getTyresBtn" onClick="getTyresFromSFTP();">Get Tyres from SFTP</button> 
            Norges Dekk List - Last import: <?php echo $updated; ?>
             <br> <!-- search field -->
            <input class="form-control-sm txtInput" type="text" id="searchInList" placeholder="Search for ItemNr, Brand, Model" style="width: 300px;"> <button class="btn btn-outline-primary search" type="button" data-type="searchInList">Search</button>
        </div>
        <div class="card-body">
            <table class="table table-hover table-sm" id="tyresTable" style="margin-top:30px; font-size:13px; max-width: 1600px;">
                <thead class="thead">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Item Nr</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Season</th>
                        <th scope="col">Model</th>
                        <th scope="col">Width</th>
                        <th scope="col">List Price</th>
                        <th scope="col">Customer Price</th>
                        <th scope="col">Profile</th>
                        <th scope="col">Fuel</th>
                        <th scope="col">Inches</th>
                        <th scope="col">Load</th>
                        <th scope="col">Speed</th>
                        <th scope="col">Noise</th>
                        <th scope="col">EAN</th>
                        <th scope="col">EU Class</th>
                        <th scope="col">EU Directive</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Category</th>
                    </tr>
                </thead>
				<tbody id="tyreListBody">
					Search for data
			    </tbody>
            </table>
        </div>
    </div>
</div>

<script>

$('.search').on('click', function() {
	var value = $('#searchInList').val();
	if(value == '') { 
		showAlert('warning', 'Field cannot be left empty');
		return;
	}
	
	showLoadingBar(1);
	var url='method=listTyresFromSFTP&value='+value;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'no admin') {
			showAlert('danger', 'You are not logged in as admin');
		}else if(e[0] == 'success') {
			showAlert('success', 'Found some entries');
			$('#tyreListBody').html(e[1]);
		}else if(e[0] == 'no entries') {
			showAlert('danger', 'No entries found');
		}else {
			showAlert('danger', 'Technical error occurred, contact developer');
		}
	});
	
});

function getTyresFromSFTP() {
    showLoadingBar(1);
    var url='method=getTyresFromSFTP';
    
    fetchA(url, function(result) {
        hideLoadingBar();
        
        var e = JSON.parse(result);
        
        if(e[0] == 'no admin') {
            showAlert('danger', 'You are not logged in as admin');
        } else if(e[0] == 'success') {
            showAlert('success', 'Inserted');
        } else {
            showAlert('danger', 'Technical error occurred, contact developer');
        }
    });
}
function edit(i) {
	$('.txtField'+i).hide();
	$('.editField'+i).show();
	$('.editButton'+i).hide();
	$('.saveButton'+i).show();
}

function save(i) {
    var attribute1 = $('#attribute1'+i).val();
	var attribute2 = $('#attribute2'+i).val();
    var attribute3 = $('#attribute3'+i).val();
	showLoadingBar(1);
	var url = 'method=saveNorgesDekk&attribute3='+attribute3+'&attribute2='+attribute2+'&attribute1='+attribute1+'&id='+i;
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
function addBrandDiscount() {
    
    var brandName = $('#brandnameAdd').val();
    var brandDiscount = $('#discountAdd').val();
    var brandClass = $('#brandClassAdd').val();
    var attribute5 = 'Moss Dekk AS';
	showLoadingBar(1);
	var url = 'method=addBrandDiscount&brandName='+brandName+'&attribute2='+brandDiscount+'&attribute3='+brandClass+'&attribute5='+attribute5;
	fetchA(url, function(result) {
		hideLoadingBar();
		var e = JSON.parse(result);
		if(e[0] == 'success') {
			showAlert('success', 'Successfully saved the changes');
			location.reload(true);
		}else {
			showAlert('danger', 'Technical error, contact developer');
		}
	});
}

function filterTable() {
        let input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        
        if (!input) {
            console.error("Sökfältet kunde inte hittas");
            return;
        }

        filter = input.value.toUpperCase();
        table = document.getElementById("tyresTable");
        
        if (!table) {
            console.error("Tabellen kunde inte hittas");
            return;
        }

        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }

    let searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", filterTable);
    }


</script>