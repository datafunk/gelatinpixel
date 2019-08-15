<?php

/*

Pixelpost version 1.3
Stats Addon version 0.1

Pixelpost www: http://www.pixelpunk.se/software/
Contact: pixelpost@pixelpunk.se
Copyright (c) 2004 shapestyle graphic design + code<http://www.shapestyle.se>
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

$addon_name = "Pixelpost Stats";
$addon_description = "<a href='index.php?view=addons&amp;statsaddon=expand'>Miscellaneous stats from your blog.</a>";
$addon_version = "0.1";
if($_GET['statsaddon'] == "expand") {
    $stats_query = mysql_query("select datetime from ".$pixelpost_db_prefix."pixelpost order by datetime asc limit 0,1");
    $statsrow = mysql_fetch_array($stats_query);
    $firstdate = substr($statsrow['datetime'],0,10);
    $firstpost = $statsrow['datetime'];
    $countall = mysql_query("select count(*) as count from ".$pixelpost_db_prefix."visitors");
    $countall = mysql_fetch_array($countall);
    $countall = $countall['count'];
    $photonumb = mysql_query("select count(*) as count from ".$pixelpost_db_prefix."pixelpost");
    $photonumb = mysql_fetch_array($photonumb);
    $pixelpost_photonumb = $photonumb['count'];
    
    $comments = mysql_query("select count(*) as count from ".$pixelpost_db_prefix."comments");
    $comments = mysql_fetch_array($comments);
    $comments = $comments['count'];
    
    $comment_average = round(($comments/$pixelpost_photonumb),2);
    // number of days online
    $daysonline = (floor((time() - strtotime($firstpost))/86400));
    // average visitors per day
    $average_per_day = round(($countall/$daysonline));
    // average photos per day
    $photos_per_day = round(($pixelpost_photonumb/$daysonline),2);
    
    $addon_description .= "
    <hr />
    <table style='width:500px;font-size:12px;'><tr><td width='170'>
    Photoblog started</td><td width='240'><b>$firstdate</b></td></tr><tr><td>
    Number of days online</td><td><b>$daysonline</b> days since first post</td></tr><tr><td>
    Visitors</td><td><b>$countall</b></td></tr><tr><td>
    Average visitors per day</td><td><b>$average_per_day</b> visitors per day</td></tr><tr><td>
    Photos Posted</td><td><b>$pixelpost_photonumb</b> photos posted</td></tr><tr><td>
    Average photos per day</td><td><b>$photos_per_day</b> photos posted on average each day</td></tr><tr><td>
    Comments total</td><td><b>$comments</b> comments</td></tr><tr><td>
    Comments per photo</td><td><b>$comment_average</b> comments on average for each photo</td></tr></table>
    ";  
    }
?>