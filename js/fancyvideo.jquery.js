function playPause(settings){
	if(settings.vid.paused){
		settings.isPaused = false;
		settings.vid.play();
		$(settings.playbtn).removeClass("pauseBtn");
		$(settings.playbtn).addClass("playBtn");
	} else {
		settings.isPaused = true;
		settings.vid.pause();
		$(settings.playbtn).removeClass("playBtn");
		$(settings.playbtn).addClass("pauseBtn");
	}
}
function seektimeupdate(vid){
	var nt = vid.currentTime * (100 / vid.duration);
	var curmins = Math.floor(vid.currentTime / 60);
	var cursecs = Math.floor(vid.currentTime - curmins * 60);
	var durmins = Math.floor(vid.duration / 60);
	var dursecs = Math.floor(vid.duration - durmins * 60);
	if(cursecs < 10){ cursecs = "0"+cursecs; }
	if(dursecs < 10){ dursecs = "0"+dursecs; }
	if(curmins < 10){ curmins = "0"+curmins; }
	if(durmins < 10){ durmins = "0"+durmins; }
	curtimetext.innerHTML = curmins+":"+cursecs;
	durtimetext.innerHTML = durmins+":"+dursecs;
}
function vidmute(settings){
	if(settings.vid.muted){
		settings.vid.muted = false;
		$(settings.slider).slider({"value":settings.vid.volume * 100});
	} else {
		settings.vid.muted = true;
		$(settings.slider).slider({"value":0});
	}
}
function setvolume(vid,value){
	vid.volume = value / 100;
}
// Whack fullscreen
function exitFullscreen() {
  if(document.exitFullscreen) {
    document.exitFullscreen();
  } else if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if(document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  }
}
// Find the right method, call on correct element
function launchIntoFullscreen(element) {
  if(element.requestFullscreen) {
    element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
}
function toggleFullScreen(settings)
{
	if(settings.fullscreen == false)
	{
		launchIntoFullscreen(settings.vid)
		bigBar(settings);
		settings.fullscreen = true;
		$(settings.fullscreenbtn).removeClass("fullscreenbtn");
		$(settings.fullscreenbtn).addClass("smallscreenbtn");
		reportProgress(settings);
		return settings.fullscreen;
	}
	else
	{
		exitFullscreen();
		//smallBar(settings);
		settings.fullscreen = false;
		$(settings.fullscreenbtn).removeClass("smallscreenbtn");
		$(settings.fullscreenbtn).addClass("fullscreenbtn");
		return settings.fullscreen;
	}
}

function showControls(settings)
{
	$(settings.controls).fadeIn('fast');
	if(settings.fullscreen == true)
	$('body').css('cursor', 'auto');
}

function hideControls(settings)
{
	$(settings.controls).fadeOut('fast');
	if(settings.fullscreen == true)	
		$('body').css('cursor', 'none');
}

// update the progress bar
function reportProgress(settings) {
	var barwidth = settings.loadingWidth;
	// get current time
	var time = settings.vid.currentTime;
	//get the media play time
	var duration = settings.vid.duration;
	//update the progress bar
	var percentage = 100 * (time / duration);
	settings.loadingBar.style.width=percentage + "%";
	
}

function findEventPositionWithinElement(e) {

      var $_target=$(e.currentTarget);

      var _offset=$_target.offset();

      var _relativeX=e.pageX-_offset.left;

      var _relativeY=e.pageY-_offset.top;

      return {left: _relativeX, top: _relativeY};

}

function clickProgressBar(event,settings)
{
	var _position=findEventPositionWithinElement(event);

      var _percentage=100*_position.left/$(settings.progressBar).width();
      /* Move The Video(s) To The New Position */
      if (typeof settings.vid.duration != "undefined") {

            if (settings.vid.duration>0) {
				settings.vid.pause();
                settings.vid.currentTime=settings.vid.duration*_percentage/100;
				if (settings.isPaused == false)
				{
					settings.vid.play();
				}
            }

      }
}

function bigBar(settings)
{
	//$(settings.controlBar).removeAttr('style');
	$(settings.controlBar).css({
		"position":"fixed",
		"bottom":"0px",
		"left":"0px",
		"top":""
	});
	var windowWidth = $(window).width();
	var progressWidth = (windowWidth/12)*11;
	settings.loadingWidth = progressWidth;
	//$(settings.progressBar).removeAttr('style');
	$(settings.progressTimeContainer).css({
		"width":progressWidth+"px",
		"float":"left"
	});
}

function smallBar(settings)
{
	//$(settings.controlBar).removeAttr('style');
	$(settings.controlBar).css({
		"position":"relative",
		"top":"-35px",
		"bottom":""
	});
	var vidWidth = $(settings.vidBox).width();
	var progressWidth = (vidWidth/4)*3;
	settings.loadingWidth = progressWidth;
	//$(settings.progressBar).removeAttr('style');
	$(settings.progressTimeContainer).css({
		"width":progressWidth+"px",
		"float":"left"
	});
	reportProgress(settings);
	
}

(function($) {
	
    $.fn.fancyVideo = function(options) 
    {
    	//default settings if none have been defined
		var settings = $.extend({
			width : 550,
			height : 310,
			videoUrl : '',
			videoThumbPath : '',
			vidBox : document.getElementById("video_player_box"),
			controlBar : document.getElementById("video_controls_bar"),
			progressBar : document.getElementById("progressbar"),
			loadingBar : document.getElementById("loadingprogress"),
			bufferBar : document.getElementById("bufferBar"),
			loadingWidth : 300,
			playerBox : document.getElementById("video_player_box"),
			controls : document.getElementById("video_controls_bar"),
	        vid         : document.getElementById("my_video"),
	        playbtn        : document.getElementById("playpausebtn"),
	        curtimetext : document.getElementById("curtimetext"),
	        durtimetext : document.getElementById("durtimetext"),
	        fullscreenbtn : document.getElementById("fullscreenbtn"),
	        progressTimeContainer : document.getElementById("progressbar_time_container"),
	        fullscreen : false,
	        slider : document.getElementById("slider"),
	        tooltip : $(".tooltip"),
	        progressTooltip : $(".progress_tooltip"),
	        progressSelector : $(".progress_selector"),
	        progressHover : false,
	        volumeIcon : document.getElementById("volume_icon"),
	        volumeSlider : document.getElementById("tooltip_slider_container"),
	        volumeSliderActive : false,
	        isPaused : false,
	        usingSlider : false,
	        livePreview : document.getElementById("live_video_preview"),
	        ajax_loading : false,
	        liveImgPreview : document.getElementById("live_img_preview"),
	        videos : {},
	        playlistContainer : document.getElementById("playlist_container"),
	        highDef : true,
	        playlistContainers : {},
	        playlistBtn : document.getElementById("playlistbtn"),
	        liveImages : {}
    	}, options);
    	$(settings.slider).slider({
    		range: "min",
    		value: 35,
    	});
    	var thenum = 0;
    	$.each(settings.videos, function(index, value)
    	{
    		thenum = thenum + 1;
    		var div = '<div id="playlist_video_container_'+thenum+'" class="playlist_video_container"><img id="video_playlist_image" src="'+value['thumb']+'" width="75" height="50"/><h3 id="video_playlist_title">'+value['title']+'</h3></div>';
    		$(settings.playlistContainer).append(div);
    		settings.playlistContainers[thenum] = document.getElementById('playlist_video_container_'+thenum);
			settings.playlistContainers[thenum].addEventListener('click',function()
			{
				var id = $(this).attr('id');
				id = id.split('_');
				num = id[3];
				if(settings.highDef == true)
				{
					settings.vid.src = settings.videos[num].high;
					settings.videoUrl = settings.videos[num].high;
					settings.videoThumbPath = settings.videos[num].id;
				}
				else
				{
					settings.vid.src = settings.videos[num].low;
					settings.videoUrl = settings.videos[num].low;
					settings.videoThumbPath = settings.videos[num].id;
				}
			});
			if(index == 1)
			{
				//see if the video url is a youtube video					
		        var isYoutube = value['high'] && value['high'].match(/(?:youtu|youtube)(?:\.com|\.be)\/([\w\W]+)/i);
		        //set the video information for the youtube video
		        if (isYoutube) 
		        {
		            var id = isYoutube[1].match(/watch\?v=|[\w\W]+/gi);
		            id = id.toString();
		            id = id.split(",");
		            //alert(id[1])
		            var mp4url = "http://www.youtubeinmp4.com/redirect.php?video=";
		            settings.vid.src = mp4url + id[1];
		            settings.videoUrl = mp4url + id[1];
		            settings.videoThumbPath = id[1];
		        }
				else
				{
					if(settings.highDef == true)
					{
						//set the video information for the non youtube video
						settings.vid.src = value['high'];
						settings.videoUrl = value['high'];
						settings.videoThumbPath = value['id'];
					}
					else
					{
						settings.vid.src = value['low'];
						settings.videoUrl = value['low'];
						settings.videoThumbPath = value['id'];		
					}
				}
			}
		});
    	
    	$(document).ajaxStart(function() {
        	settings.ajax_loading = true;
    	});
    	$(document).ajaxStop(function() {
        	settings.ajax_loading = false;
    	});
    	
    	this.fullScreenMode = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen; // This will return true or false depending on if it's full screen or not.
    	$(document).on ('mozfullscreenchange webkitfullscreenchange fullscreenchange',function()
    	{
			this.fullScreenMode = !this.fullScreenMode;
			//-Check for full screen mode and do something..
			if (this.fullScreenMode == false)
			{
				smallBar(settings);
				settings.fullscreen = false;
			}
		});
		settings.vid.width = settings.width;
		settings.vid.height = settings.height;
		$(settings.playlistContainer).css('height',(settings.height-45)+'px');
		$(settings.playlistContainer).css('top','-'+(settings.height)+'px');
		$(settings.vidBox).width = settings.width;
		$(settings.vidBox).height = settings.height;
		var vidWidth = $(settings.vidBox).width();
		var progressWidth = (vidWidth/4)*3;
		settings.loadingWidth = progressWidth;
		//$(settings.progressBar).removeAttr('style');
		$(settings.progressTimeContainer).css({
			"width":progressWidth+"px",
			"float":"left"
		});
		
		settings.vid.volume = 35 / 100;
		settings.tooltip.hide();
		settings.progressBar.addEventListener("mouseleave", function(e){
			$(settings.progressTooltip).fadeOut("fast");
			$(settings.progressSelector).fadeOut("fast");
			$(settings.livePreview).fadeOut('fast');
		});
		settings.progressBar.addEventListener("mouseenter", function(e){
			$(settings.progressTooltip).fadeIn("fast");
			$(settings.progressSelector).fadeIn("fast");
			$(settings.livePreview).fadeIn('fast');
		});
		settings.progressBar.addEventListener("mousemove",function(e){
			pos = findEventPositionWithinElement(e);
			var _percentage=100*pos.left/$(settings.progressBar).width();
			if (typeof settings.vid.duration != "undefined") 
			{
				var num = settings.vid.duration*_percentage/100;
					if (settings.vid.duration>num && num > 0) 
					{
						var nt = num * (100 / settings.vid.duration);
						var curmins = Math.floor(num / 60);
						var cursecs = Math.floor(num - curmins * 60);
						var durmins = Math.floor(settings.vid.duration / 60);
						var dursecs = Math.floor(settings.vid.duration - durmins * 60);
						if(cursecs < 10){ cursecs = "0"+cursecs; }
						if(dursecs < 10){ dursecs = "0"+dursecs; }
						if(curmins < 10){ curmins = "0"+curmins; }
						if(durmins < 10){ durmins = "0"+durmins; }
						var current = curmins+":"+cursecs;
						var duration = durmins+":"+dursecs;
						settings.progressTooltip.css('left', pos.left + 40);
				    	settings.progressTooltip.css('top', -10).text(current);
				    	settings.progressSelector.css('left', pos.left-3);
				    	settings.progressSelector.css('top',0);
				    	$(settings.livePreview).css('left',pos.left-10);
				    	$(settings.livePreview).css('top',-110);
				    	if($(settings.progressSelector).css('display') == 'none')
				    	{
							$(settings.progressTooltip).fadeIn("fast");
							$(settings.progressSelector).fadeIn("fast");
							$(settings.livePreview).fadeIn('fast');
						}
						if(settings.ajax_loading == false && typeof settings.liveImages[num] == 'undefined')
						{
							$.ajax({
								dataType: "json",
								url: '/json/getVideoThumbnail/',
								data: {
									'ajax':true,
									'second':num,
									'videoUrl':settings.videoUrl,
									'size':'120x90',
									'videoId':settings.videoThumbPath
								},
								success: function(data)
								{
									if(data.success)
									{
										settings.liveImages[num] = data.image;
										$(settings.liveImgPreview).html('');
										var image = '<img id="live_image" src="'+data.image+'" />';
										$(settings.liveImgPreview).append(image);
										var imageObj = $("#live_image");
										$(settings.livePreview).css('top',-110);
										imageObj.error(function()
										{
											$(settings.liveImgPreview).html('');
											$(settings.liveImgPreview).append('<img src="http://illusiongroup.us/images/loading.gif" />');
											$(settings.livePreview).css('top','-50px');
										});
										
									}
								}
							});
						}
						else if(typeof settings.liveImages[num] != 'undefined')
						{
							$(settings.liveImgPreview).html('');
							var image = '<img id="live_image" src="'+settings.liveImages[num]+'" />';
							$(settings.liveImgPreview).append(image);
							var imageObj = $("#live_image");
							$(settings.livePreview).css('top',-110);
						}
						else
						{
							$(settings.liveImgPreview).html('');
							$(settings.liveImgPreview).append('<img src="http://illusiongroup.us/images/loading.gif" />');
							$(settings.livePreview).css('top','-50px');
						}
					}
					else
					{
						$(settings.progressTooltip).fadeOut("fast");
						$(settings.progressSelector).fadeOut("fast");
						$(settings.livePreview).fadeOut('fast');
					}
				
			}
			//alert("num: "+num);
		},false);
		
		settings.volumeIcon.addEventListener("mouseenter",function()
		{
			//make sure volume slider is inactive to display it
			var value = $(settings.slider).slider('value');
			if(value <= 5) 
		    { 
		        $(settings.volumeIcon).css('background-position', '-25px 0');
		    } 
		    else if (value <= 25) 
		    {
		        $(settings.volumeIcon).css('background-position', '-25px -25px');
		    } 
		    else if (value <= 75) 
		    {
		        $(settings.volumeIcon).css('background-position', '-25px -50px');
		    } 
		    else 
		    {
		        $(settings.volumeIcon).css('background-position', '-25px -75px');
		    };
			
			if(settings.volumeSliderActive == false)
			{
   				$(settings.volumeSlider).fadeIn('fast');
			}
   		}, false);
   		settings.playlistBtn.addEventListener("click",function()
   		{
   			$(settings.playlistContainer).fadeIn("fast");
   		}, false);
   		settings.playlistContainer.addEventListener("mouseleave",function()
   		{
   			$(settings.playlistContainer).fadeOut("fast");	
   		});
   		settings.volumeIcon.addEventListener("mouseleave",function()
   		{
   			var counter = 0;
			var interval = setInterval(function() {
			    counter++;
			    if (counter == 5) {
			    	if(settings.volumeSliderActive == false)
					{
						var value = $(settings.slider).slider('value');
						if(value <= 5) 
					    { 
					        $(settings.volumeIcon).css('background-position', '0 0');
					    } 
					    else if (value <= 25) 
					    {
					        $(settings.volumeIcon).css('background-position', '0 -25px');
					    } 
					    else if (value <= 75) 
					    {
					        $(settings.volumeIcon).css('background-position', '0 -50px');
					    } 
					    else 
					    {
					        $(settings.volumeIcon).css('background-position', '0 -75px');
					    };
						$(settings.volumeSlider).fadeOut('fast');
					}
			        clearInterval(interval);
			    }
			}, 1000);
   			
   		}, false);
   		settings.volumeSlider.addEventListener("mouseenter",function()
   		{
   			settings.volumeSliderActive = true;
   		}, false);
   		settings.volumeSlider.addEventListener("mouseleave",function()
   		{
   			settings.volumeSliderActive = false;
   			var value = $(settings.slider).slider('value');
			if(value <= 5) 
		    { 
		        $(settings.volumeIcon).css('background-position', '0 0');
		    } 
		    else if (value <= 25) 
		    {
		        $(settings.volumeIcon).css('background-position', '0 -25px');
		    } 
		    else if (value <= 75) 
		    {
		        $(settings.volumeIcon).css('background-position', '0 -50px');
		    } 
		    else 
		    {
		        $(settings.volumeIcon).css('background-position', '0 -75px');
		    };
   			$(settings.volumeSlider).fadeOut('fast');
   		},false);
    	//add our event listeners to the html
    	settings.playbtn.addEventListener("click",function()
		{
			playPause(settings);
		},false);
		settings.vid.addEventListener("timeupdate",function()
		{
			seektimeupdate(settings.vid);
			reportProgress(settings);
		},false);
		settings.vidBox.addEventListener("mouseleave",function(e)
		{
			
			hideControls(settings);
		},false);
		settings.vidBox.addEventListener("mousemove",function(e)
		{
			if(settings.usingSlider == false)
			{
				
				if(settings.fullscreen == false)
				{
					offSet = $(settings.vidBox).offset();
					var relX = e.pageX - offSet.left;
					var relY = e.pageY - offSet.top;
					
					var barTop = settings.height - 35;
					//alert(relY + "    "+barTop);
					//check to see if the mouse is hovering the bar
					if(relY >= barTop)
					{
						if($(settings.controlBar).css('display') == 'none')
						{
							//show the bar
							showControls(settings);
						}
					}
					else
					{
						//mouse isnt on the bar, start the 5 second timer
						//show the bar
						showControls(settings);
						var counter = 0;
						var interval = setInterval(function() {
						    counter++;
						    settings.vidBox.addEventListener("mousemove",function(e)
							{
								clearInterval(interval);	
							});
						    if (counter == 5) 
						    {
						    	if(settings.usingSlider == false)
					    		{
					    			hideControls(settings);
					    			clearInterval(interval);
					        	}
					        	counter = 0;
						    }
						}, 1000);
					}
				}
				else
				{
					var barTop = $(window).height() - 33;
					newY = (e.pageY - 600);
					if(newY >= barTop)
					{
						if($(settings.controlBar).css('display') == 'none')
						{
							//show the bar
							showControls(settings);
						}
					}
					else
					{
						//mouse isnt on the bar, start the 5 second timer
						//show the bar
						showControls(settings);
						var counter = 0;
						var interval = setInterval(function() {
						    counter++;
						    settings.vidBox.addEventListener("mousemove",function(e)
							{
								clearInterval(interval);	
							});
							if (counter == 5) 
							{
						    	if(settings.usingSlider == false)
					    		{
					    			hideControls(settings);
					    			clearInterval(interval);
					        	}
					        	counter = 0;
					        }
						}, 1000);
					}
				}
			}
		},false);
		settings.volumeIcon.addEventListener("click",function()
		{
			vidmute(settings);
		},false);
		settings.fullscreenbtn.addEventListener("click",function()
		{
			settings.fullscreen = toggleFullScreen(settings);	
		},false);
		settings.progressBar.addEventListener("click", function(e)
		{
			clickProgressBar(e,settings);
		}, false);
		settings.vid.addEventListener("play",function()
		{
			var startBuffer = function() {
				var maxduration = settings.vid.duration;
				var currentBuffer = settings.vid.buffered.end(0);
				var percentage = 100 * currentBuffer / maxduration;
				settings.bufferBar.style = "width:"+ percentage+'%;';

				if(currentBuffer < maxduration) {
				setTimeout(startBuffer, 500);
				}
			};
			setTimeout(startBuffer, 500);
		});
		//volume main function
		$(settings.slider).slider
		({
			orientation: "vertical",
			range: "min",
			min: 1,
			value: 35,
			start: function(event,ui) 
			{
				settings.tooltip.fadeIn('fast');
				settings.usingSlider = true;
			},

			slide: function(event, ui) 
			{
			    var value = $(settings.slider).slider('value');
			    volume = $('.volume');
				setvolume(settings.vid,value);
				values = 90 - value;
				settings.tooltip.css('left', 20);
			    settings.tooltip.css('top', values).text(ui.value);
				if(settings.volumeSliderActive == true)
				{
					if(value <= 5) 
				    { 
				        volume.css('background-position', '-25px 0');
				    } 
				    else if (value <= 25) 
				    {
				        volume.css('background-position', '-25px -25px');
				    } 
				    else if (value <= 75) 
				    {
				        volume.css('background-position', '-25px -50px');
				    } 
				    else 
				    {
				        volume.css('background-position', '-25px -75px');
				    };
				}
				else
				{
				    if(value <= 5) 
				    { 
				        volume.css('background-position', '0 0');
				    } 
				    else if (value <= 25) 
				    {
				        volume.css('background-position', '0 -25px');
				    } 
				    else if (value <= 75) 
				    {
				        volume.css('background-position', '0 -50px');
				    } 
				    else 
				    {
				        volume.css('background-position', '0 -75px');
				    };
				}

			},
			stop: function(event,ui) 
			{
			  settings.tooltip.fadeOut('fast');
			  settings.usingSlider = false;
			},
		});
		
    }

}(jQuery));
