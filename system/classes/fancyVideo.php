<?php
class fancyVideo
{
	protected $registy;
	public $videos;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->registry->siteHeader .= '
		<script type="text/javascript" src="/js/fancyVideo.jquery.js"></script>
		<link rel="stylesheet" href="/css/fancyVideo.css" type="text/css" media="all" />
		';
	}
	
	public function setSettings($width,$height)
	{
		echo '
		<script>
		    $(document).ready(function(){
					
		    $("video_player_box").fancyVideo({
		    	\'width\' : \''.$width.'\',
		    	\'height\' : \''.$height.'\',
		    	\'videos\' : {
		    		';
		    		$i = 1;
		    		foreach ($this->videos as $vid)
		    		{
						echo "
						'$i' : {
							'title' : '".$vid['title']."',
							'id' : '".$vid['id']."',
							'high' : '".$vid['high']."',
							'low' : '".$vid['low']."',
							'thumb' : '".$vid['thumb']."'
						},
						";
						$i++;
					}
		    		echo'
		    	}
		    });
		    
		    })</script>';
	}
	
	public function Display($width,$height)
	{
		echo '
		<div id="video_player_box">
		<video id="my_video" width="'.$width.'" height="'.$height.'" autoplay>
			<source src="http://cdn.akamai.steamstatic.com/steam/apps/81954/movie_max.webm?t=1432922939" type="video/mp4">
		</video>
		<div id="video_controls_bar" style="height:35px;z-index: 2147483647;width:100%;background-color: #111;border-radius: 3px;border: 1px solid #333;-webkit-box-shadow:  1px 1px 2px 0px rgba(0, 0, 0, .3);box-shadow:1px 1px 2px 0px rgba(0, 0, 0, .3);-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;background: #45484d; background: -moz-linear-gradient(top, #45484d 0%, #000000 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#45484d), color-stop(100%,#000000));background: -webkit-linear-gradient(top, #45484d 0%,#000000 100%);background: -o-linear-gradient(top, #45484d 0%,#000000 100%); background: -ms-linear-gradient(top, #45484d 0%,#000000 100%);background: linear-gradient(to bottom, #45484d 0%,#000000 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#45484d\', endColorstr=\'#000000\',GradientType=0 ); display:none; position:relative; top:-35px; opacity:0.95; float:left;">
			<div id="playpausebtn" class="playBtn" style="margin-left:-8px;width:30px;float:left;margin-top:-9px;"></div>
			<div id="progressbar_time_container" style="width:75%;float:left;">
			<div id="progressbar" style="float: left;width:100%;">
				<div id="bufferBar"></div>
				<div id="loadingprogress"></div>
				<div id="vid_time_container" style="position: absolute; top:-2px;left:10px;">
					<span id="curtimetext">00:00</span> / <span id="durtimetext">00:00</span>
				</div>	
				<span class="progress_selector" style="display:none;"></span>
			
			</div>
			<span class="progress_tooltip" style="display:none;"></span>
			<div id="live_video_preview" style="display:none;">
				<div id="live_img_preview">
				</div>
			</div>
			</div>

			<div id="volume_container" style="float:left;">
				<section> 
					<div id="tooltip_slider_container" style="display:none;">
						<span class="tooltip" style="display:none;"></span> 
						<div id="slider"></div>
					</div>	
					<span id="volume_icon" class="volume"></span>
				</section>
			</div>
			<span id="playlistbtn" class="playlistbtn"></span>
			<span id="fullscreenbtn" class="fullscreenbtn"></span>
			<div id="playlist_container" style="display:none;">

			</div>
		</div>
		</div>
		';
		$this->setSettings($width,$height);
	}
}
?>