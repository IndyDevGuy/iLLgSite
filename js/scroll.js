window.onload = function() {

  function getScrollTop() {
    if (typeof window.pageYOffset !== 'undefined' ) {
      // Most browsers
      return window.pageYOffset;
    }

    var d = document.documentElement;
    if (d.clientHeight) {
      // IE in standards mode
      return d.scrollTop;
    }

    // IE in quirks mode
    return document.body.scrollTop;
  }
  
  var theWindow = $(window),
  	  bg = $("#back_image"),
	  aspectRatio = bg.width() / bg.height();
	    			    		
	function resizeBg() {
		
		if ( (theWindow.width() / theWindow.height()) < aspectRatio ) {
		    bg.removeClass().addClass('bgheight');
		} else {
		    bg.removeClass().addClass('bgwidth');
		}
					
	}
	                   			
	theWindow.resize(resizeBg).trigger("resize");

  window.onscroll = function() {
    var box = document.getElementById('back_image'),
        scroll = getScrollTop();

    if (scroll <= 28) {
      box.style.top = "0px";
    }
    else {
      box.style.top = scroll + "px";
    }
  };

};