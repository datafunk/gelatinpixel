<?php

/*

Requires Pixelpost version 1.4
PIXELGRAIN template Addon version 0.1
Written by: John Ryan Brubaker
Contact: ryan@pixelgrain.org

To see it in action go to: http://www.pixelgrain.org

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

*/

$addon_name = "PIXELGRAIN template ADDON";
$addon_version = "0.1";



// Addon description
$addon_description = "$alert
This addon adds additional functionality to the PIXELGRAIN template found at www.pixelgrain.org/template/. <br /><br />
";

// format date/time in header

$tpl = str_replace("<IMAGE_DATETIME>",$image_datetime_formatted,$tpl);


// set body width based on image width

if($image_width > $image_height) {
$bodywidth = $image_width+13;
$bodywidth = 'style="width:' . $bodywidth . 'px"';
} else {
$bodywidth = $image_width+284;
$bodywidth = 'style="width:' . $bodywidth . 'px"';
}
$tpl = str_replace("<BODY_WIDTH>",$bodywidth,$tpl);


// Add the name of the image to the page title

if($_SERVER['QUERY_STRING'] == '') { 
$tpl = str_replace("<SITE_TITLE_EXT>","",$tpl);
$tpl = str_replace("<SITE_DESC_EXT>","",$tpl);
} else {
$tpl = str_replace("<SITE_TITLE_EXT>"," &raquo; " . $image_title,$tpl);

$cleannotes = strip_tags($image_notes);

$tpl = str_replace("<SITE_DESC_EXT>"," &raquo; " . $image_title . " &raquo; " . strip_tags($image_notes),$tpl);
}


// If the image is portrait set the width of the image description div to 500px and float the thumbnails div to the right.

if($image_width > $image_height) {
$notesdevwidth = $image_width-250;
$textstyling = 'style="width:' . $notesdevwidth . 'px:"';
$thumbstyling = 'style="float:right;"';
} else {
$textstyling = "";
}

$tpl = str_replace("<TEXTSTYLING>",$textstyling,$tpl);
$tpl = str_replace("<THUMBSTYLING>",$thumbstyling,$tpl);



// change the previous and next links to arrows.  Overrides the language settings...

$image_next_pg = "<a href='$showprefix$image_next_id'>&raquo;</a>";
$image_previous_pg = "<a href='$showprefix$image_previous_id'>&laquo;</a>";

if($image_previous_id == $image_id) {
    $image_previous_pg = "";
    }
    
if($image_next_id == $image_id) {
    $image_next_pg = "";
    }

$tpl = str_replace("<IMAGE_NEXT_PG>",$image_next_pg,$tpl);
$tpl = str_replace("<IMAGE_PREVIOUS_PG>",$image_previous_pg,$tpl);


?>