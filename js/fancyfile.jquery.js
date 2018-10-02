(function($) {
	
	 $.fn.fancyFile = function(options) 
    {
    	var ff_Html = '<form id="ff_form" action="" enctype="multipart/form-data"  method="POST"><input id="ff_selector" name="ff_selector" type="file" /><div id="ff_progressDiv"><div id="ff_progressBar"></div></div></form><div id="ff_imageContainer" style="display:none;"><span id="ff_deleteBtn" style="float:right;">X</span></div>';
	$(this).append(ff_Html);
		var settings = $.extend({
			siteUrl : '',
			postUrl : '',
			deleteUrl : '',
			showFileOnLoad : false,
			fileLocation : '',
			imageWidth : 125,
			imageHeight : 125,
			loadingTextColor : '#fff',
			loadingBackgroundColor : '#b7410e',
			loadingBarBackgroundColor : '#111',
			loadingRadius : 15,
			loadingBarColor : '#f75a17',
			loadingBarWidth : 1,
			showLoadingText : true,
			loadingTextSize : '10px',
			multipleFiles : false,
			fileForm : document.getElementById("ff_form"),
			fileInput : document.getElementById("ff_selector"),
			progressDiv : document.getElementById("ff_progressDiv"),
			progressBar : document.getElementById("ff_progressBar"),
			imageContainer : document.getElementById("ff_imageContainer"),
			deleteBtn : document.getElementById("ff_deleteBtn")
    	}, options);
    	
    	//add the necessary html the fancy file upload
    	if(settings.showFileOnLoad == true)
    	{
			ff_Html = '<form id="ff_form" action="" style="display:none;" enctype="multipart/form-data"  method="POST"><input id="ff_selector" name="ff_selector" type="file" /><div id="ff_progressDiv"><div id="ff_progressBar" style="display:none;"></div></div></form><div id="ff_imageContainer" style="display:block;"><img src="'+settings.siteUrl+'/'+settings.fileLocation+'" id="ff_image" /><span id="ff_deleteBtn" style="float:right;">X</span></div>';
			
			$(this).html('');
			$(this).append(ff_Html);
			
			settings.fileForm = document.getElementById("ff_form"),
			settings.fileInput = document.getElementById("ff_selector"),
			settings.progressDiv = document.getElementById("ff_progressDiv"),
			settings.progressBar = document.getElementById("ff_progressBar"),
			settings.imageContainer = document.getElementById("ff_imageContainer"),
			settings.deleteBtn = document.getElementById("ff_deleteBtn")
		}
		
    	$(this).css({'overflow':'auto','clear':'both','height': '150px'});
    
    	$(settings.progressBar).circularloader({
			backgroundColor: settings.loadingBackgroundColor,
			fontColor: ""+settings.loadingTextColor,
			fontSize: ""+settings.loadingTextSize,
			radius: settings.loadingRadius,
			progressBarBackground: ""+settings.loadingBarBackgroundColor,
			progressBarColor: ""+settings.loadingBarColor,
			progressBarWidth: settings.loadingBarWidth,
			progressPercent: 0,
			progressValue: "0%",
			showText: settings.showLoadingText
		});
    	
    	//set the postUrl to the action of the form
    	$(settings.fileForm).attr("action", settings.postUrl);
    	//if file was selected in users filebrowser
    	settings.fileInput.addEventListener('change', function(){
    		//get the name of the file
    		var fileName = $(this).val();
			$(settings.fileForm).ajaxSubmit({
                beforeSubmit: function() {
                    
                },
                uploadProgress: function (event, position, total, percentComplete){
                	$(settings.progressBar).circularloader({
						progressPercent: percentComplete,
						progressValue: percentComplete+'%'
					});	
                },
                success:function (data){
                	if(data["success"] == "success")
                	{
                		settings.fileLocation = data["image"];
                    	$(settings.progressBar).hide();
                   		$(settings.imageContainer).append('<img src="'+settings.siteUrl+'/'+data["image"]+'" id="ff_image" />');
                   		$(settings.imageContainer).show();
                    	$(settings.fileForm).hide();
                    }
                    else
                    {
						alert(data);
					}
                },
                resetForm: true 
			});
    	}, false);
    	settings.deleteBtn.addEventListener('click',function()
    	{
    		$.get(settings.deleteUrl+"&image="+settings.fileLocation, function(data){
    			if(data["success"] == "success")
			  	{
			  		$(settings.progressBar).html('');
			  		$(settings.progressBar).show();
			  		alert(data["image"]);
					$(settings.imageContainer).html('');
		    		$(settings.fileForm).show();
		    		$(settings.progressBar).circularloader({
						backgroundColor: settings.loadingBackgroundColor,
						fontColor: ""+settings.loadingTextColor,
						fontSize: ""+settings.loadingTextSize,
						radius: settings.loadingRadius,
						progressBarBackground: ""+settings.loadingBarBackgroundColor,
						progressBarColor: ""+settings.loadingBarColor,
						progressBarWidth: settings.loadingBarWidth,
						progressPercent: 0,
						progressValue: "0%",
						showText: settings.showLoadingText
					});
				}
    		});
    		
		});
    }
	
}(jQuery));