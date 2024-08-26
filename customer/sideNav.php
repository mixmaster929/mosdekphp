<!--<nav id="sideNavBar" style="width:220px; margin:0;">
	<div class="sideNavHeader" style="border:1px solid #000; width:auto; height:100px; display:block;"></div>
	
	<ul class="sideNav-nav" style="">
		<li class="sideNav-item" style="">
			<a class="sideNav-link" href="#" style="" />
		</li>
		

</nav>
-->
<!-- Side Navbar -->
<div class="border-right px-0 h-100" style="margin-top:30px; position:fixed; height:100vh; background-color:#fff; width:220px;">
	<div class="nav-header mb-3" style="height:100px; display:block;">
		<img src="../images/autobutlerOLD2.png" style="width:100%;" />
	</div>
	<div class="list-group list-group-flush text-left">
		<a href="?=home" class="list-group-item list-group-item-action  rounded-0 py-2">
			Home
		</a>
		<a href="?p=carinfo" class="list-group-item list-group-item-action py-2">Car Info</a>
		<a href="?p=customerInfo" class="list-group-item list-group-item-action py-2">Customer Info</a>
		<!--<a href="#" class="active list-group-item list-group-item-action py-2 extendedMenuHead" data-extend="productsMenuExtended" data-extended=0>Products <span class="productsMenuCollapseSign float-right">+</span></a>
			<div class="productsMenuExtended " style="display:none;">
			<a href="?p=addProduct" class="list-group-item list-group-item-action py-2 pl-5 small" style="background-color:#007bff; color:#fff;">Add Products</a>
			<a href="?p=productsList" class="list-group-item list-group-item-action py-2  pl-5 small" style="background-color:#fbfbfb;">List of Products</a>
			<a href="?p=productSearch" class="list-group-item list-group-item-action py-2  pl-5 small" style="background-color:#fbfbfb;">Search</a>
			</div>
		-->
		<!--<a href="?p=productsList" class="list-group-item list-group-item-action py-2">Product List</a>-->
		<a href="?p=tyrePurchaseHistory" class="list-group-item list-group-item-action py-2">Tyre Purchase History</a>
		<a href="?p=tyreChangeHistory" class="list-group-item list-group-item-action py-2">Work History</a>
		<a href="?p=dekkhotell" class="list-group-item list-group-item-action py-2">Dekkhotell</a>
		<a href="?p=chatHistory" class="list-group-item list-group-item-action py-2">Chat</a>
		<a href="../" class="list-group-item list-group-item-action py-2">Main Page</a>
		<a href="javascript:logout();" class="list-group-item list-group-item-action py-2">Logout</a>
		<!--<a href="?p=settings" class="list-group-item list-group-item-action py-2">Settings</a>-->
		<!--<a href="#" class="list-group-item list-group-item-action disabled rounded-0 py-2 border-bottom" tabindex="-1" aria-disabled="true">Disabled</a>-->
	</div>
</div>

<script>

$('.extendedMenuHead').on('click', function() {
	var extended = $(this).data('extended');
	var extendMenu = $(this).data('extend');
	$('.'+extendMenu).slideToggle();
});

function logout() {
	var url = 'method=logoutCustomer';
	showLoadingBar(1);
	fetchC(url, function(e) {
		hideLoadingBar();
		if(e == 'success') {
			location.reload(true);
		}else {
			showAlert('danger', 'Error logging you out');
		}
		
	});
}

</script>

