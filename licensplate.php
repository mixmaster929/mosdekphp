<html>
  <head>
    <title>Licensplate</title>
    <style>
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
      if(isset($_SESSION['adminID'])) {
        $adminID_temp = $_SESSION['adminID'];
        session_unset();
        $_SESSION['adminID'] = $adminID_temp;
        }else session_unset();

    
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
  #welcomeLicensplateText {
  margin-top: 10px;
  }
  
  .dekkskift-btn {
      padding: 5px 20px;
      border-radius: 6px;
  }
  </style>

  <?php 
  $fullName = RegNr();
  if ($fullName) {
      echo "<span id='welcomeLicensplate' style='text-align:center'><h3>Velkommen ".$fullName."</h3>Ønsker du å Bestille tid for DEKKSKIFT?</br>
      <button class='button btn-success dekkskift-btn' id='jaCheckbox'>Ja</button>
      <button class='button btn-dark dekkskift-btn' id='neiCheckbox'>Nei, gå til andre tjenester</button>".
  /*<input type='checkbox'  name='jaCheckbox'><span class='licensplateJa'> Ja</span> <input type='checkbox'  name='neiCheckbox'><span class='licensplateNei'> Nei, gå til andre tjenester</span><br>*/
  "<span id='welcomeLicensplateText' style='font-size: 16px;width: 100%;background-color: #33333340;border-radius: 3px;display: block;float: inherit;text-align: left;padding: 10px;'></span></span>";
      }
  else{ 
      echo "<span id='welcomeLicensplate' style='font-size: 16px;'><span id='welcomeLicensplateText' style='font-size: 16px;'></span> </span> </br> <button class='btn btn-outline-primary search' type='button' onclick='closeLicensplate();'>Steng</button>
      <script>
         
    
    
    
    
      setTimeout(closeLicensplate, 33000);
      </script>";
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

document.addEventListener("DOMContentLoaded", function(event) {
    setTimeout(function(){
        inputFieldLicensplate.focus();
    }, 500);
});

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
  neiCheckbox.addEventListener('click', function() {
    
      closeLicensplate();
     
  });
}

const jaCheckbox = document.getElementById('jaCheckbox');
if (jaCheckbox) {
  jaCheckbox.addEventListener('click', function() {
    
      closeLicensplate();
      $('#option1').click();
      var regNrWarehouseModal = '<?php echo isset($_SESSION["regNrLicensplate"]) ? $_SESSION["regNrLicensplate"] : ''; ?>';
      var locationWarehouseModal = '<?php echo isset($_SESSION["regNrlocation"]) ? $_SESSION["regNrlocation"] : '';?>';
      document.getElementById("regNrRegistration").value = document.getElementById("regNrDekk").value = regNrWarehouseModal;
      document.getElementById("locationID").value = locationWarehouseModal; 
      setTimeout(autofillRegNr, 1200);
    
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



</script>
</body>
</html>