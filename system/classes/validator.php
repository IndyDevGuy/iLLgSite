<?php
class validator
{
	public function __construct()
	{
		
	}
	
	public function checkLength($string, $min)
	{
		if (strlen($string) >= $min)
		{
			return true;
		}
		return false;
	}
	
	public function checkEmail()
	{
		
	}
	
	public function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
    }
    
    public function getMimeType($filename)
	{
	    $mimetype = false;
	    if(function_exists('finfo_fopen')) {
	        // open with FileInfo
	    } elseif(function_exists('getimagesize')) {
	        // open with GD
	    } elseif(function_exists('exif_imagetype')) {
	       // open with EXIF
	    } elseif(function_exists('mime_content_type')) {
	       $mimetype = mime_content_type($filename);
	    }
	    return $mimetype;
	}
	
}
?>