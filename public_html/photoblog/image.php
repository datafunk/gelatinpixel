<?php

// includes
require("includes/pixelpost.php");
require("includes/functions.php");
include_once("ga-tracking.php");
start_mysql();

class binaryImage {
	var $maxHeight = 0;
	var $maxWidth = 500;
	var $newHeight = 0;
	var $newWidth = 0;
	var $path = "";
	var $base;
	
	function getImage(){
		//return ($base);
		if (array_key_exists("path",$_GET))
		{
			$this->path = $_GET["path"];
		}
		if (array_key_exists("maxwidth",$_GET))
		{
			$this->maxWidth = $_GET["maxwidth"];
			if (!settype($this->maxWidth,"integer"))
			{
				$this->maxWidth = 0;
			}
		}
		if (array_key_exists("maxheight",$_GET))
		{
			$this->maxHeight = $_GET["maxheight"];
			if (!settype($this->maxHeight,"integer"))
			{
				$this->maxHeight = 0;
			}
		}
	
		$fileExists = file_exists($this->base.$this->path);
		//return ($fileExists);

		if ($fileExists)
		{
			$image = imagecreatefromjpeg($this->base.$this->path);
			$imageWidth = imagesx($image);
			$imageHeight = imagesy($image);
			$newHeight = 0;
			$newWidth = 0;

			if ($this->maxWidth > 0 && $this->maxHeight > 0)
			{
				//print ("File exists, width and height provided");
				//If we set the height to maxheight would the width be within max width??
				if (($imageWidth * $this->maxHeight / $imageHeight) > $this->maxWidth)
				{
					//No, setting the height to max height would not give a permissable width
					//Therefore set width to maxwidth and calculate the height
					$newWidth = $this->maxWidth;
					$newHeight = $imageHeight * $this->maxWidth / $imageWidth;
				}
				else
				{
					$newHeight = $this->maxHeight;
					$newWidth = $imageWidth * $this->maxHeight / $imageHeight;
				}
			}
			else
			{	
				if ($this->maxWidth > 0)
				{
					//print ("File exists, width provided");
					$newWidth = $this->maxWidth;
					$newHeight = $imageHeight * $this->maxWidth / $imageWidth;		
				}
				else
				{			
					if ($this->maxHeight > 0)
					{
						//print ("File exists, height provided");
						$newHeight = $this->maxHeight;
						$newWidth = $imageWidth * $this->maxHeight / $imageHeight;
					}
					else
					{
						//print ("File exists, No width or height where provided");
					}
				}
			}
			if ($newHeight > 0 && $newWidth > 0)
			{//if we successfully set the new size
				$newimage = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresized($newimage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
				imagedestroy($image);
				header("Content-type: image/jpeg");
				imagejpeg($newimage);
				imagedestroy($newimage);
			}
			if ($newHeight == 0 && $newWidth == 0)
			{//if we did not change the size values
				header("Content-type: image/jpeg");
				imagejpeg($image);
				imagedestroy($image);
			}	
		}
	}
}


$binaryImage = new binaryImage();

if($cfgrow = sql_array("select * from ".$pixelpost_db_prefix."config")) {
    $binaryImage->base = $cfgrow['imagepath'];
}
$binaryImage->getImage();
?>
