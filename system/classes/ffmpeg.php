<?php
class Ffmpeg
{
	protected $registry;
	protected $db;
	public $ffmpegpath;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
		$this->ffmpegpath = 'C:\\ffmpeg\\bin\\ffmpeg';
	}
	
	public function getVideoThumbnail($filename,$size,$seconds,$videoId)
	{
		$imagefolder = 'uploads/vids/thumbs/'.$videoId;
		$secondFile = str_replace('.','_',$seconds);
		$imagefile = $imagefolder .'/'.$size.'_'.$secondFile . '.jpg';
		if(!is_dir($imagefolder))
		{
			mkdir($imagefolder);
		}
		if(file_exists($imagefile))
		{
			return $imagefile;
		}
		$ffmpeg = $this->ffmpegpath;
		$cmd = "$ffmpeg -i $filename -an -ss $seconds -r 1 -s $size $imagefile";
		if(shell_exec($cmd))
		{
			return 'error';
		}
		else
		{
			return $imagefile;
		}
	}
	
	public function getVidInfo($vid)
	{
		
		// Determine the full path for our video
		$vid = realpath($vid);
		// Create the ffmpeg instance and then display the information about the video clip.
		$ffmpegInstance = new ffmpeg_movie($vid);
		echo "getDuration: " . $ffmpegInstance->getDuration() . "<br />".
		"getFrameCount: " . $ffmpegInstance->getFrameCount() . "<br />".
		"getFrameRate: " . $ffmpegInstance->getFrameRate() . "<br />".
		"getFilename: " . $ffmpegInstance->getFilename() . "<br />".
		"getComment: " . $ffmpegInstance->getComment() . "<br />".
		"getTitle: " . $ffmpegInstance->getTitle() . "<br />".
		"getAuthor: " . $ffmpegInstance->getAuthor() . "<br />".
		"getCopyright: " . $ffmpegInstance->getCopyright() . "<br />".
		"getArtist: " . $ffmpegInstance->getArtist() . "<br />".
		"getGenre: " . $ffmpegInstance->getGenre() . "<br />".
		"getTrackNumber: " . $ffmpegInstance->getTrackNumber() . "<br />".
		"getYear: " . $ffmpegInstance->getYear() . "<br />".
		"getFrameHeight: " . $ffmpegInstance->getFrameHeight() . "<br />".
		"getFrameWidth: " . $ffmpegInstance->getFrameWidth() . "<br />".
		"getPixelFormat: " . $ffmpegInstance->getPixelFormat() . "<br />".
		"getBitRate: " . $ffmpegInstance->getBitRate() . "<br />".
		"getVideoBitRate: " . $ffmpegInstance->getVideoBitRate() . "<br />".
		"getAudioBitRate: " . $ffmpegInstance->getAudioBitRate() . "<br />".
		"getAudioSampleRate: " . $ffmpegInstance->getAudioSampleRate() . "<br />".
		"getVideoCodec: " . $ffmpegInstance->getVideoCodec() . "<br />".
		"getAudioCodec: " . $ffmpegInstance->getAudioCodec() . "<br />".
		"getAudioChannels: " . $ffmpegInstance->getAudioChannels() . "<br />".
		"hasAudio: " . $ffmpegInstance->hasAudio();
	}
}
?>