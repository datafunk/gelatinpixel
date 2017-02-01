<?php
/*

Requires Pixelpost version 1.3+
Random Image Addon version 0.4
Written by: Ramin Mehran
Contact: raminia@yahoo.com


Pixelpost www: http://www.pixelpunk.se/software/
License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*/

$addon_name = "Random Image ";
$addon_description = "This addon will enables the tag &lt;IMAGE_RANDOM&gt;
 which is the ID of a random image of your Pixelpost photoblog. Using this ID, you can link to a random image easily.
 Usage:<br>
 &lt;a href='index.php?showimage=&lt;IMAGE_RANDOM&gt;' &gt; Random photo &lt;/a&gt; <br>
The above tag links to your photoblog with a random photo.";

$addon_version = "0.3";

   // Get the number of thumbnails shown at once from the admin config
   $cfgquery = mysql_query("select * from ".$pixelpost_db_prefix."config");
   $cfgrow = mysql_fetch_array($cfgquery);

   //---------------------------

   $thmb_numb_r = 1 ;



	if (isset($image_id)){
	$thumbs_ahead = mysql_query("select DISTINCT id,headline,image from ".$pixelpost_db_prefix."pixelpost where id!=".$image_id." and datetime<='$cdate' order by rand() asc limit 0,$thmb_numb_r");
	}
	else{
	$thumbs_ahead = mysql_query("select DISTINCT id,headline,image from ".$pixelpost_db_prefix."pixelpost where datetime<='$cdate' order by rand() asc limit 0,$thmb_numb_r");
	}


	if (list($id,$headline,$image) = mysql_fetch_row($thumbs_ahead)) {
	$rnd_img_id = $id;

	}

	$tpl = ereg_replace("<IMAGE_RANDOM>",$rnd_img_id,$tpl);



?>