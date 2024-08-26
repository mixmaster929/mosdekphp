
<?php
//Your authentication key
$authKey = "OCCqPb_-dshDYZhji54LKSWI";

//Multiple mobiles numbers separated by comma
$mobileNumber = "+34631774838";

//Sender ID,While using route4 sender id should be 6 characters long.
$senderId = "PHPTPN";

//Your message to send, Add URL encoding here.
$message = urlencode("Welcome to the world of Test API");

//Define route 
$route = "default";
//Prepare you post parameters
$postData = array(
    'authkey' => $authKey,
    'mobiles' => $mobileNumber,
    'message' => $message,
    'sender' => $senderId,
    'route' => $route
);

//API URL
$url="http://sms.phptpoint.com/api/sendhttp.php";

// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData
    //,CURLOPT_FOLLOWLOCATION => true
));


//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

//get response
$output = curl_exec($ch);

//Print error if any
if(curl_errno($ch))
{
    echo 'error:' . curl_error($ch);
}


curl_close($ch);


?>
<div class="row text-center" style="margin:0;">
	<div class="col-12" style="padding:0;">
		<div class="row" style="margin:0; padding:40px 0 80px 0;">
			<div class="col-sm-8" style=" padding:40px; padding-bottom:20px;">
				<h2>Kontaktinformasjon</h2>
				<p>Har du spørsmål? Ta kontakt med oss i dag så hjelper vi deg. </p>
				<div class="row" style="margin:0; margin-top:20px;">
					<div class="col-sm-4" style="margin-bottom:20px;">
						<i class="fa fa-phone" style="display:block; font-size:30px; color:#333;"></i>
						450 22 450
						
					</div><div class="col-sm-4" style="margin-bottom:20px;">
						<i class="fa fa-map-marker" style="display:block; font-size:30px; color:#333;"></i>
						skredderveien 5, 1537 Moss
					</div><div class="col-sm-4" style="margin-bottom:20px;">
						<i class="fa fa-envelope" style="display:block; font-size:30px; color:#333;"></i>
						post@mossdekk.no
					</div>
				</div>
			</div><div class="col-sm-4" style=" padding:40px;">
				<i class="fa fa-clock-o" style="font-size:40px; display:block;"></i>
				Åpningstider <br>
				Mandag - fredag <br>
				08:00 - 16:00<br>
				Lørdag<br>
				10:00 - 14:00
			</div>
		</div>
		<div class="row" style="margin:0; background-color:#eee;">
			<div class="col-sm-6" style="background-color:;">
				<div class="card px-0" style="box-shadow:0px 10px 20px #999; margin:-80px auto 40px auto; max-width:400px;">
					<div class="card-header" style="display:none;">
						Add Product
					</div>
	
					<div class="card-body">
						<h5 class="card-title" style="display:block;">Full ut alle feltene</h5>
						<br>
						
						
    						<div class="row">
    							<div class="form-group col" style="">
    								<label for="fullNameContact">Reg NR :</label>
    								<input type="text" class="form-control" id="regNr" name="regNr" placeholder="Reg Nr" style="font-weight:500;" />
    							</div>
    						</div>
    						
    						<div class="row">
    							<div class="form-group col" style="">
    								<label for="fullNameContact">Navn :</label>
    								<input type="text" class="form-control" id="fullNameContact" name="fullNameContact" placeholder="For og etter navn" style="font-weight:500;" />
    							</div>
    						</div>
    						
    						<div class="row">
    							<div class="form-group col" style="">
    								<label for="email">Email :</label>
    								<input type="email" required class="form-control" id="email" name="email" placeholder="Email address" style="font-weight:500;" />
    							</div>
    						</div>
    						
    						<div class="row">
    							<div class="form-group col" style="">
    								<label for="phone">Mobil Nr :</label>
    								<input type="text" class="form-control" id="phone" name="phone" placeholder="8 siffer mobil nr" style="font-weight:500;" />
    							</div>
    						</div>
    						
    						<div class="row">
    							<div class="form-group col" style="">
    								<label for="subject">Emne :</label>
    								<input type="text" class="form-control" id="subject" name="subject" placeholder="Hva gjelder henvendelsen" style="font-weight:500;" />
    							</div>
    						</div>
    						
    						<div class="row">
    							<div class="form-group col" style="">
    								<label for="message">Melding</label>
    								<textarea class="form-control" id="message" name="message" placeholder="Skriv inn melding her...." style="font-weight:500;"></textarea>
    							</div>
    						</div>
    						
    						<input type='submit' class="btn btn-primary mt-2" id="send" name="send" value ="Send Hendvende">
					
					</div>
				</div>
			</div><div class="col-sm-6" style="background-color:#eee;">
			<div style="text-align:center; margin:20px;"></div>
				<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d8109.663153793965!2d10.6886197!3d59.4594927!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf0c938655e220549!2sMoss%20Dekk%20AS!5e0!3m2!1sno!2sno!4v1591397614919!5m2!1sno!2sno" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
			</div>
		</div>
		
	</div>
</div>


<script>

$('#send').on('click', function() {
	var name = $('#fullNameContact').val();
	var email = $('#email').val();
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!re.test(String(email).toLowerCase()))
    {
        $('#email').focus();
        return;
    }
    
	var phone = $('#phone').val();
	var subject = $('#subject').val();
	var msg = $('#message').val();
	var regNr = $('#regNr').val();
	if(name == '' || email == '' || phone == '' || subject == '' || msg == '' || regNr == '') { showAlert('warning', 'All the fields are required'); return; }
	
	showLoadingBar();
	var url = 'method=saveContact&name='+name+'&email='+email+'&phone='+phone+'&sub='+subject+'&msg='+msg+'&regNr='+regNr+'&location=Moss Dekk AS';
	fetch(url, function (e) {
		hideLoadingBar();
		if(e == 'success') {
			showAlert('success', 'Your query was successfully submitted');
			location.reload(true);
		}else if(e == 'empty fields') {
			showAlert('warning', 'All the fields are required');
		}else {
			showAlert('danger', 'There was some error while submitting you query');
		}
	});
});

</script>