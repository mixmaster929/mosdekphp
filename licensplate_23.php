<html>
  <head>
    <title>Licensplate</title>
    <style>
    body{
    background: black;
    }
    .licensplateHeadline{
        position: absolute;
        top: 7%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        font: max(1.6vw, 28px) 'roboto';
        color: white;

    }
    .blur{
      background: black;
      background-size: cover;
      overflow: hidden;
      filter: blur(9px);
      position: relative;
      height: 300px;
      top: -50px;
      left: -50px;
      right: -50px;
      bottom: -50px;
    }
    .licensplate{ 
      border: 1px solid rgba(0, 0, 0);
      border-radius: 0.25rem;
      background: white;
      width: 33%;
      max-width: 650px;
      position: absolute;
      top: 20%;
      left: 50%;
      margin-right: -50%;
      transform: translate(-50%, -50%);
      
    }
    img#imgLicensplate {
        border-radius: 0.25rem 0 0 0.25rem;  
        margin-right: 2%;
        margin-left: -2px;
        max-height:150px;
        width: 15%;
        aspect-ratio: 3 / 5;
    }
    form#form-licensplate
    {
     display: inline-block;
      margin-top: 2%;
      position: absolute;
      border: none;
      padding: 0;
      width: 85%; 
    }
    #input-licensplate {
      display: inline-block;
      position: absolute;
      width: 100%; 

      border: none;
      margin-top: 2%;
      padding: 0;
      background: repeating-linear-gradient(90deg, dimgrey 0, dimgrey 1.1ch, transparent 0, transparent 1.55ch) 0 100%/ 10.7ch 1px no-repeat;
      font: min(4.6vw, 86px) 'roboto'; font-size: ;
      letter-spacing: 0.5ch;
    }
    #input-licensplate:focus {
      outline: none;
    }
    span.licensplateNei {
        font-size: max(0.7vw, 8px);
        cursor: pointer;
    }
    span.licensplateJa {
        font-size: max(0.7vw, 8px);
        margin-right: 40px;
        cursor: pointer;
    }
    span#welcomeLicensplate {
        font: max(1vw, 12px) 'roboto';
        line-height: 1.6;
    }
    input#jaCheckbox {
        display: inline-block;
        margin-left: 18%;
        cursor: pointer;
    }
    input#neiCheckbox {
        display: inline-block;
        cursor: pointer;
    }
    #modalLicensplate {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }
    /* Blurry background styles */
    .blurry-background {
      position: fixed;
      z-index: -1;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      filter: blur(5px);
      background-size: cover;
    }
    
    @media (max-width: 650px) { /* Larger on mobile screen */
        .licensplate {
          width: 70%; 
          max-width: none; 
        }
        #input-licensplate {
        font: min(9.6vw, 86px) 'roboto';
        }

    }
    </style>
  </head>
  <body>
 <script>
 
 function closeLicensplate(){
    var modalLicensplate = document.getElementById("modalLicensplate");
    modalLicensplate.style.display = "none";
  };  

 </script> 
  <?php
  //Everything with the licensplace is in this document. Need to query data from http://autobutler.no/dekkhotell/query_mossdekk.php to check if regNr has tyres stored
  if (!isset($_GET['p'])) {
    session_unset();
  ?>
  <script>
    // Open the modal when the page loads and clean site if reset
    window.addEventListener('load', function() {
      modalLicensplate.style.display = "block";
      if (window.history.replaceState) {
    window.history.replaceState(null, null, "index.php");
    }
  });

  <?php }?>
  </script>   
  <div class="blurry-background"></div>
  <div id="modalLicensplate"> 
    <p class='licensplateHeadline'>Tast inn ditt RegNr<p>
        <div class='licensplate'> <?php checkLicensplate(); ?>
          <img src="images/norsk.png" id='imgLicensplate' alt="norsk flagga" height='100%'><form id='form-licensplate' method="POST"><input style="text-transform: uppercase;" pattern="[a-zA-Z]{2}[0-9]{5}$"type="text" maxlength="7" autofocus id="input-licensplate" name="input-licensplate" autocomplete="off"></form>
        </div>
  </div>
  <?php
  function RegNr(){
    $_SESSION["regNrLicensplate"] = $value = $regNrLicensplate= strtoupper($_POST['input-licensplate']);
    $pattern = "/^[a-zA-Z]{2}[0-9]{5}$/";
    if(preg_match($pattern, $value)){
      $url = 'http://autobutler.no/dekkhotell/query_mossdekk.php';
      $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url,
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => array(
        'value' => $value
      )
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      // Display the result returned by the other server
      $response;
      if($response == 1){
        $con = dbCon();
        $query= "SELECT fullName, location, price FROM shop_customers WHERE location = 'Moss Dekk AS' and regNr like '%$regNrLicensplate%'";
        $result=mysqli_query($con,$query);
        if($result){
          foreach($result as $row){
            $fullName = $row['fullName'];
            $_SESSION["regNrlocation"] = $row['location'];
            $_SESSION["regNrprice"] = $row['price'];
            return $fullName;    
          }  
        }
      $con->close();
      }
    } 
  }
function checkPrice($regNrPar){
    $con = dbCon();
    $query= "SELECT price FROM shop_customers WHERE location = 'Moss Dekk AS' and regNr like '%$regNrPar%'";
    $result=mysqli_query($con,$query);
    if($result){
      foreach($result as $row){
        $mossdekkPrice = $row['price'];
        return $mossdekkPrice;    
      }  
    }
    $con->close(); 
}

function checkLicensplate(){
if (isset($_POST['input-licensplate'])) {
    ?>
  <style type="text/css">form#form-licensplate{
  display:none;
  }
  img#imgLicensplate{
  display:none;
  }

  .licensplateHeadline{
  display:none;
  }
  .licensplate{
  text-align: center;
  padding: 10px;
  min-width: 300px;
  }
  </style>

  <?php 
  $fullName = RegNr();
  if ($fullName) {
      echo "<span id='welcomeLicensplate' style='text-align:center'>Velkommen ".$fullName."</br>Ønsker du å Bestille tid for DEKKSKIFT?</br>
  <input type='checkbox' id='jaCheckbox' name='jaCheckbox'><span class='licensplateJa'> Ja</span> <input type='checkbox' id='neiCheckbox' name='neiCheckbox'><span class='licensplateNei'> Nei, gå til andre tjenester</span></span>";
      }
  else{ echo "Registreringsnummer ikke funnet, Denne feilmeldingen vises når Reg nr eller kunde info mangler, selvom kunden er dekkhotell kunde. vennligst ta kontakt med kundeservice på tlf: 45022450 eller send e-post til post@mossdekk.no så hjelper vi deg.  <script>setTimeout(closeLicensplate, 33000);</script>";
  } 
  }
}

?>
<script>
// Get the modal
var modalLicensplate = document.getElementById("modalLicensplate");
// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modalLicensplate) {
    closeLicensplate();
  }
};

const inputLicensplate = document.getElementById('input-licensplate').value;
const formLicensplate = document.querySelector('#form-licensplate');
const inputFieldLicensplate = document.querySelector('#input-licensplate');

inputFieldLicensplate.addEventListener('input', function(event) {
  const valueInputFieldLicensplate = inputFieldLicensplate.value;
  if (valueInputFieldLicensplate.length === 1){ document.getElementById("input-licensplate").style.caretColor="transparent"}
  else if (valueInputFieldLicensplate.length === 7 && /^[a-zA-Z]{2}\d{5}$/.test(valueInputFieldLicensplate)) {
  formLicensplate.submit();
  }
  else if (valueInputFieldLicensplate.length === 7){ document.getElementById("input-licensplate").style.color = "red";}
  else document.getElementById("input-licensplate").style.color = "black"
});

function autofillRegNr(){
  $("#regNrDekk").trigger("change");
};
function autofillRegNrNewCustomer(){
  $("#regNrRegistration").trigger("input");
};
function autofillRegNrBuyTyre(){
  $("#regNr").trigger("change");
};

function trigger(){
    $(".licensplateNei").trigger("click");
};

const neiCheckbox = document.getElementById('neiCheckbox');
if (neiCheckbox) {
  neiCheckbox.addEventListener('change', function() {
    if (neiCheckbox.checked) {
      closeLicensplate();
    } 
  });
}

const jaCheckbox = document.getElementById('jaCheckbox');
if (jaCheckbox) {
  jaCheckbox.addEventListener('change', function() {
    if (jaCheckbox.checked) {
      closeLicensplate();
      $('#option1').click();
      var regNrWarehouseModal = '<?php echo isset($_SESSION["regNrLicensplate"]) ? $_SESSION["regNrLicensplate"] : ''; ?>';
      var locationWarehouseModal = '<?php echo isset($_SESSION["regNrlocation"]) ? $_SESSION["regNrlocation"] : '';?>';
      document.getElementById("regNrRegistration").value = document.getElementById("regNrDekk").value = regNrWarehouseModal;
      document.getElementById("locationID").value = locationWarehouseModal; 
      setTimeout(autofillRegNr, 1200);
    } 
  });
}

$('.licensplateNei').on('click',function(){
  closeLicensplate();
});
$('.licensplateJa').on('click',function(){
  closeLicensplate();
  $('#option1').click();
    var regNrWarehouseModal = '<?php echo isset($_SESSION["regNrLicensplate"]) ? $_SESSION["regNrLicensplate"] : ''; ?>';
    var locationWarehouseModal = '<?php echo isset($_SESSION["regNrlocation"]) ? $_SESSION["regNrlocation"] : '';?>';
  document.getElementById("regNrDekk").value = regNrWarehouseModal;
  document.getElementById("locationID").value = locationWarehouseModal;
  setTimeout(autofillRegNr, 1200);
});

//RegNr From licensplate to warehouseModal´and buytyremodal
$(document).ready(function() {
  var regNrWarehouseModal = '<?php echo isset($_SESSION["regNrLicensplate"]) ? $_SESSION["regNrLicensplate"] : ''; ?>';
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
</body>
</html>