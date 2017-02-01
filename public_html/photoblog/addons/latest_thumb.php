<?php

/*

Pixelpost version 1.4.1
Image link to latest thumb addon version 1.0
Addon developer: Ramin Mehran (Raminia)

Pixelpost www: http://www.pixelpost.org/



*/

$addon_name = "Image link to latest thumb";
$addon_description = "Makes a link to Pixelpost's latest thumbnail. ";
$addon_version = "1.0";

// latest thumb
if ($_GET['showthumb']=='latest')
{
	// get current time
	$datetime = date("Y-m-d H:i:s");
	$cdate = $datetime; // for future posting


	// Get Current Image.
  $query = mysql_query("select image,headline,datetime,id from ".$pixelpost_db_prefix."pixelpost where datetime<='$cdate' order by datetime DESC limit 0,1");


	$row = mysql_fetch_array($query);
	if(!$row['image']) {
	    echo "Coming Soon (query fail)!";
	    //exit;
	    }
	$image_name         = $row['image'];
	$image_title        = pullout($row['headline']);
	$image_id           = $row['id'];
	$image_datetime     = $row['datetime'];

   // filename
   $filename = 'thumbnails/thumb_'.$image_name;

   // if user wants the thumb image
	if ($_GET['thumbtag'] =='image')
	{
/*	 // make header
   header('Content-type: image/jpeg');
   header('Content-transfer-encoding: binary');
   header('Content-length: '.filesize($filename));

	 // put it on the web!
   readfile($filename);
   exit;*/
       // put it on the web!
	 header('Content-Type: image/jpeg');
	 header('Content-transfer-encoding: binary');
	 $img = imagecreatefromjpeg($filename);
	 imagejpeg($img);
   exit;
  }

  if ($_GET['thumbtag'] == 'getlink')
  {
   $img_link = "<img src='" .$filename ."' title= '".$image_title."' /> " ;
	 echo $img_link ;
	 exit;
  }

   // if user wants the image title
  if ($_GET['thumbtag'] =='title')
  {

	  // echo	 <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	   //echo "<body><html>";

	   echo $image_title;
	   //echo "</body></html>";
   	 exit;
   	}


}

?>
