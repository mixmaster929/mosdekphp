      <div id="continuous-slider">
        <div class="continuous-slider--wrap">
          <ul id="continuous-slider--list" class="clearfix" style="margin:0;">
            <li><div class="brandCont" style=""><img src="images/brand1.png" /></div></li>
            <li><div class="brandCont" style=""><img src="images/brand2.png" /></div></li>
            <li><div class="brandCont" style=""><img src="images/brand3.png" /></div></li>
            <li><div class="brandCont" style=""><img src="images/brand4.png" /></div></li>
            <li><div class="brandCont" style=""><img src="images/brand5.png" /></div></li>
            <li><div class="brandCont" style=""><img src="images/brand6.png" /></div></li>
          </ul>
        </div>
</div>
<script>

$(function(){
  var $slider = $('#continuous-slider--list');
  var sizeImage = 200;
  var items = $slider.children().length;
  var itemswidth = (items * sizeImage); // 140px width for each client item 
  $slider.css('width',itemswidth+80+'px');
  
  var rotating = true;
  var sliderspeed = 0;
  var seeitems = setInterval(rotateSlider, sliderspeed);
  
  $(document).on({
    mouseenter: function(){
      rotating = true; // turn off rotation when hovering
    },
    mouseleave: function(){
      rotating = true;
    }
  }, '#continuous-slider');
  
  function rotateSlider() {
    if(rotating != false) {
	  var $first = $('#continuous-slider--list li:first');
      $first.animate({ 'margin-left': '-'+sizeImage+'px' }, 3000, "linear", function() {
        $first.remove().css({ 'margin-left': '0px' });
        $('#continuous-slider--list li:last').after($first);
      });
    }else{
		//$('#continous-slider--list li:first').stop();
		//$first.stop();
		//clearInterval(seeitems);
    }
  }
});


</script>