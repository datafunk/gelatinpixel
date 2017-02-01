<?php

/*
Pixelpost version 1.3+
Rating addon version 1.2

Copyright (c) 2005 Rob Prouse <http://www.shiftedexposure.com>
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
$addon_name = "Pixelpost Photo Rating";
$addon_description = "Displays a 5 star rating for an image. Check out <a href='http://www.shiftedexposure.com'>http://www.shiftedexposure.com</a> for an example.<br \>
Now includes the overlib library version 4.17.  Go to <a href='http://www.bosrup.com/web/overlib/'>http://www.bosrup.com/web/overlib/</a> for more information on the
cool things you can do with this library.";
$addon_version = "1.2";

// Uncomment this to use the overlib javascript library
// available at http://www.bosrup.com/web/overlib/
$use_overlib = true;

// Check to see if the ratings table exists, if not, create it
$query = "SELECT id FROM {$pixelpost_db_prefix}ratings LIMIT 1";
if( !mysql_query( $query ) ) {
  $query = "CREATE TABLE {$pixelpost_db_prefix}ratings (
	  id int(11) NOT NULL auto_increment,
	  parent_id int(11) NOT NULL default '0',
	  rating smallint(6) NOT NULL default '0',
	  ip varchar(20) NOT NULL default '',
	  PRIMARY KEY  (id),
	  UNIQUE KEY vote (parent_id,ip),
	  KEY parent_id (parent_id)
	)";
   mysql_query( $query );
}

// Don't do this if it is not an image page
if( isset( $image_id ) ) {

// If they rated, insert their value
if( isset( $_GET['rating'] ) && $_GET['rating'] > 0 && $_GET['rating'] <= 5 ) { 
  $query = "REPLACE INTO {$pixelpost_db_prefix}ratings (parent_id, rating, ip) VALUES ('$image_id', '{$_GET['rating']}', '{$_SERVER['REMOTE_ADDR']}')";
  mysql_query( $query );
}

// Get the current rating and number of votes for this image
$query = "SELECT COUNT(id), ROUND( AVG(rating), 2 ), ROUND( AVG(rating), 0 ) FROM {$pixelpost_db_prefix}ratings WHERE parent_id='$image_id'";
$result = mysql_query( $query ) or die( mysql_error() );
if( $row = mysql_fetch_array( $result ) ) {
	$votes = $row[0];
	$rating = $row[1];
	$stars = $row[2];
	if( $votes == 1 ) {
		$votes = "1 vote";
	} else {
		$votes = "$votes votes";
	}
} else {
	$votes = 0;
	$rating = 'Unrated';
	$stars = 0;
	$votes = 'no votes';
}

// Preload image
$ratings_html = "<script type=\"text/javascript\" language=\"JavaScript\">image1 = new Image();image1.src = 'img/star_over.gif';function replace(img_name,img_src){document[img_name].src=img_src;}</script>";
$ratings_html .= "<table width='60'  border='0' cellspacing='0' cellpadding='0' class='ratings'><tr>\n";
for( $i=1; $i<6; $i++ ) {
  if( $i <= $stars ) {
    $star_image = "img/star_on.gif";
  } else {
    $star_image = "img/star_off.gif";
  }
  $mouse_in = "";
  $mouse_out = "";
  for( $j=1; $j<=$i; $j++ ) {
      $mouse_in .= "replace( 'star$j', 'img/star_over.gif');";
      $mouse_out .= "replace( 'star$j', '";
      if( $j <= $stars ) {
        $mouse_out .= "img/star_on.gif');";
      } else {
        $mouse_out .= "img/star_off.gif');";
      }
  }
  if( isset( $use_overlib ) ) {
    $mouse_in .= "return overlib('Currently rated $rating with $votes', CAPTION, 'Rate This Image $i/5',TEXTCOLOR, '#000000',CAPCOLOR, '#000000', BGCOLOR, '#cc2420', FGCOLOR, '#FFFFFF', LEFT, ABOVE );";
    $mouse_out .= "return nd();";
    $ratings_html .= "<td><a href='?showimage=$image_id&amp;rating=$i' rel='nofollow' onmouseout=\"$mouse_out\" onmouseover=\"$mouse_in\"><img src='$star_image' alt='$rating' name='star$i' width='12' height='12' border='0'/></a></td>\n";
  } else {
    $ratings_html .= "<td><a href='?showimage=$image_id&amp;rating=$i' title='Rate this photo $i/5 ($votes)' rel='nofollow' onmouseout=\"$mouse_out\" onmouseover=\"$mouse_in\"><img src='$star_image' alt='$rating' name='star$i' width='12' height='12' border='0'/></a></td>\n";
  }
}
$ratings_html .= "</tr></table>\n";

$tpl = str_replace("<IMAGE_RATING_STARS>",$ratings_html,$tpl);
$tpl = str_replace("<IMAGE_RATING>",$rating,$tpl);
$tpl = str_replace("<IMAGE_VOTES>",$votes,$tpl);
}

?>
