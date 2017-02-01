<?php

/*

Requires Pixelpost version 1.3
Browse Thumb Addon version 0.1
Written by: Remy Sharp
Contact: mail@ihatemusic.com - ensure you put 'pixelpost' in the subject other the email will get rejected as spam.


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

$addon_name = "Browse Thumbs";
$addon_description = "Adds two new tags to allow the user to browse through the thumbnails.<br /><br />
For example, if you are showing 5 thumbs at the bottom of the page, browsing left will show the previous
5 thumbnaiil images and show the middle picture from the collection of thumbnails.<br /><br />
New tags to use are:<br />
&lt;BROWSE_THUMBS_LEFT&gt;, &lt;BROWSE_THUMBS_RIGHT&gt;<br /><br />
These will return the image ids for the image in the middle of the next set of thumbnails.<br /><br />
For example the tag could be used as: <br />&lt;a href=\"index.php?showimage=&lt;BROWSE_THUMBS_LEFT&gt;\"&gt;previous thumbnail set&lt;/a&gt;
";
$addon_version = "0.1";

if(!isset($image_id)) {
	// then we're showing the page from the admin section and don't bother doing anyhing.	
} else {
	// Get the number of thumbnails shown at once from the admin config
	$cfgquery = mysql_query("select * from ".$pixelpost_db_prefix."config");
	$cfgrow = mysql_fetch_array($cfgquery);
	$thumbnumber = $cfgrow['thumbnumber'];
	
	// Code taken from index.php to get the thumbs ahead and behind
	if(function_exists(gd_info)) {
	    $gd_info = gd_info();
	    if($gd_info != "") { // check that gd is here before this

	    // get all the images new than the current shown image, and get the $thumbnumber image ahead of the current viewed image
	    $browsequery = mysql_query("select id from ".$pixelpost_db_prefix."pixelpost where datetime >= '$image_datetime' and datetime<='$cdate' and id!=$image_id order by datetime limit " . $thumbnumber);
		mysql_data_seek($browsequery, mysql_num_rows($browsequery)-1);
	        $browserow = mysql_fetch_array($browsequery);

		$ahead_id = $browserow['id'] ? $browserow['id'] : $image_id;

		$browsequery = mysql_query("select id from ".$pixelpost_db_prefix."pixelpost where datetime <= '$image_datetime' and datetime<='$cdate' and id!=$image_id order by datetime desc limit " . $thumbnumber);
		mysql_data_seek($browsequery, mysql_num_rows($browsequery)-1);
	        $browserow = mysql_fetch_array($browsequery);

		$behind_id = $browserow['id'] ? $browserow['id'] : $image_id;
		
		$tpl = ereg_replace("<BROWSE_THUMBS_LEFT>","$behind_id",$tpl);
		$tpl = ereg_replace("<BROWSE_THUMBS_RIGHT>","$ahead_id",$tpl);

	        } // gd_info()
	    } // func exist
	}

?>
