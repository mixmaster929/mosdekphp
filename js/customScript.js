function isset(variable) {
	if(typeof(variable) != 'undefined' && typeof(variable) !== null) {
		return true;
	}
	return false;
}

function showModal(title, msg) {
	if($('div').is('#modalMsg')) { 
		$('#modalMsg').modal('show');
	}else {
		var modalHtml = '<div class="modal fade" id="modalMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'+
				  '<div class="modal-dialog modal-dialog-centered" role="document">'+
					'<div class="modal-content">'+
					  '<div class="modal-header">'+
						'<h5 class="modal-title" id="exampleModalLongTitle">'+title+'</h5>'+
						'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
						'  <span aria-hidden="true">&times;</span>'+
						'</button>'+
					 ' </div>'+
					 ' <div class="modal-body">'+msg+'</div>'+
					 ' <div class="modal-footer">'+
						'<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'+
						'<!--<button type="button" class="btn btn-primary">Save changes</button>-->'+
					 ' </div>'+
					'</div>'+
				 ' </div>'+
				'</div>';
				
		$('body').append(modalHtml);
		$('#modalMsg').modal('show');
	}
}

function showAlert(type, msg, width) {
	if($('div').is('#alertMsg')) {
		$('#alertMsg').alert();
	}else {
		if(width === undefined) { width = '500px;' } 
		var alertHTML = '<div id="alertMsg" class="alert alertM alert-'+type+' alert-dismissible fade show " role="alert" style="">'+
						msg
			  +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
				'<span aria-hidden="true">&times;</span>'+
			  '</button>'+
			'</div>';
		$('body').append(alertHTML);
		$('#alertMsg').alert();
		
		var timer = setInterval(function () {
			$('#alertMsg').alert('close');
			clearInterval(timer);
		}, 5000);
	}
}

function hideModal() {
	$('#modalMsg').modal('dispose');
}

function fetch(link, callback) {

	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");  
	}
	 
	 let data = link;
	 xhr.open("POST", "query.php", true);
	 xhr.setRequestHeader("Content-Type" ,"application/x-www-form-urlencoded");
	 xhr.onreadystatechange = display_data; 
	 xhr.send(data);

	function display_data() {
		if (xhr.readyState == 4) {
		if (xhr.status == 200) {
			callback(xhr.responseText);
		} else {
			//$('#mapMsg').html('Error<br><span style="cursor:pointer; color:#eee;" onclick="initialize();">Refresh the map</span>');
			// alert("There was a technical problem. Try again.");
	 } } } 
}

function fetchA(link, callback, form) {
	if(form == 1) {
		var FD = new FormData(document.getElementById(link));
	}
	
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");  
	}
	 
	 xhr.open("POST", "queryAdmin.php", true);
	 xhr.onreadystatechange = display_data; 
	 if(form == 1) {
		 //xhr.setRequestHeader("Content-Type" ,"application/x-www-form-urlencoded");
		 xhr.send(FD);
	 }else {
		 let data = link;
		 xhr.setRequestHeader("Content-Type" ,"application/x-www-form-urlencoded");
		 xhr.send(data);
	 }
	 
	 

	function display_data() {
		if (xhr.readyState == 4) {
		if (xhr.status == 200) {
			callback(xhr.responseText);
		} else {
			//$('#mapMsg').html('Error<br><span style="cursor:pointer; color:#eee;" onclick="initialize();">Refresh the map</span>');
			// alert("There was a technical problem. Try again.");
	 } } } 
}

function fetchC(link, callback, form) {
	if(form == 1) {
		var FD = new FormData(document.getElementById(link));
	}
	
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");  
	}
	 
	 xhr.open("POST", "queryCustomer.php", true);
	 xhr.onreadystatechange = display_data; 
	 if(form == 1) {
		 //xhr.setRequestHeader("Content-Type" ,"application/x-www-form-urlencoded");
		 xhr.send(FD);
	 }else {
		 let data = link;
		 xhr.setRequestHeader("Content-Type" ,"application/x-www-form-urlencoded");
		 xhr.send(data);
	 }
	 
	function display_data() {
		if (xhr.readyState == 4) {
		if (xhr.status == 200) {
			callback(xhr.responseText);
		} else {
			//$('#mapMsg').html('Error<br><span style="cursor:pointer; color:#eee;" onclick="initialize();">Refresh the map</span>');
			// alert("There was a technical problem. Try again.");
	 } } } 
}

function showLoadingBar(admin) {
	if(admin == 1) { var imgURL = '../images/Rolling.gif'; } else { var imgURL = 'images/Rolling.gif'; }
	var loadingBarHTML = '<div class="modalBg modal modalLoading" style="position: fixed; width: 100%; height: 100%; margin: auto; top: 0px; right: 0px; left: 0px; bottom: 0px; background-color: rgb(0, 0, 0); opacity: 0.5; display: block;">'+
						'</div>'+
						'<div class="modal modalLoading" style="overflow: visible; position: fixed; width: 260px; height: 60px; margin: auto; top: 0px; right: 0px; left: 0px; bottom: 0px; border-radius: 3px; background-color: rgb(255, 255, 255); padding: 10px; box-shadow: rgb(51, 51, 51) 0px 2px 4px; display: block;">'+
							'<div style="padding:20px; text-align:center; position:absolute; width:auto; height:auto; margin:auto; top:0; right:0; left:0; bottom:0;">'+
								'<img src="'+imgURL+'" style="width:20px; height:auto; margin-right:5px;" />'+
								'<font style="font-family:roboto;">Processing your request..</font>'+
							'</div>'+
						'</div>';
	$('body').append(loadingBarHTML);
}

function hideLoadingBar() {
	$('.modalLoading').remove();
}


function deleteStatusRowRemove(deletedID) {
  var rowToRemove = document.getElementById('tr' + deletedID);
  if (rowToRemove) {
    rowToRemove.remove();
    console.log("removed: "+deletedID);
  }
}


function deleteRowResponse(e,id) { console.log(e);
	if(e == 'no admin') { showAlert('danger', 'Not logged in as Admin'); return; }
	if(e == 'success') { showAlert('success', 'Row successfully deleted');  deleteStatusRowRemove(id); return; }
	if(e == 'failed') { showAlert('danger', 'Row delete failed'); return; }
	showAlert('danger', 'Error while deleting row'); 
	return;
}
function deleteRow(table, id, admin) {
	var conf = confirm('Are you sure you want to delete this particular entry?');
	if(conf == false) { return; }
	var url = 'method=deleteRow&table='+table+'&id='+id;
if (admin != 1) {
		fetch(url, function(response) {
			deleteRowResponse(response, id); // Skicka id till deleteRowResponse
		});
	} else {
		fetchA(url, function(response) {
			deleteRowResponse(response, id); // Skicka id till deleteRowResponse
		});
	}
}

function validateAdminPassword(callback){
    var password = prompt("Skriv inn admin-passordet:");    
    if (password != null) {
        var url = 'method=validateAdminPassword&password=' + password;
        fetch(url, function(result) {
            hideLoadingBar();
            var e = JSON.parse(result);
            if(e[0] == 'success') {
                callback(true);
            } else {
                callback(false);
            }
        });
    } else {
        return false;
    }
}
