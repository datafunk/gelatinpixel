<?php

/*
Pixelpost version 1.3+
Anti-Spam addon version 2.0

Updated July 2005 by Connie Mueller-Goedecke
Copyright (c) 2005 Rob Prouse - http://www.shiftedexposure.com
License: http://www.gnu.org/copyleft/gpl.html

Pixelpost www: http://www.pixelpost.org/
Contact: thecrew@pixelpost.org


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
// Admin page
if( isset( $_GET['view'] ) && $_GET['view']=='addons' )
{
$addon_name = "Pixelpost Anti-Spam";
$addon_version = "2.0";

// Check to see if the ban table exists, if not, create it
$query = "SELECT id FROM {$pixelpost_db_prefix}ban LIMIT 1";
if( !mysql_query( $query ) ) {
  $query = "CREATE TABLE {$pixelpost_db_prefix}ban (
	  id INT(11) NOT NULL auto_increment,
	  banlist MEDIUMTEXT NOT NULL default '',
	  PRIMARY KEY  (id)
	)";
  mysql_query( $query );
  $query = "INSERT INTO {$pixelpost_db_prefix}ban VALUES ( '', 'tramadol\n-online\nadipex\nadvicer\nambien\nbllogspot\ncarisoprodol\ncasino\ncasinos\nbaccarrat\ncialis\ncwas\ncyclen\ncyclobenzaprine\nday-trading\ndiscreetordering\ndutyfree\nduty-free\nfioricet\nfreenet-shopping\nincest\nlevitra\nmacinstruct\nmeridia\nonline-gambling\npaxil\nphentermine\nplatinum-celebs\npoker-chip\npoze\nprescription\nsoma\nslot-machine\ntaboo\nteen\ntramadol\ntrim-spa\nultram\nviagra\nxanax\nbooker\nzolus\nchatroom\npoker\ncasino\ntexas\nholdem' )";
  mysql_query( $query );
}

// Update the ban list if the form is called
if( isset( $_POST['antispamupdate'] ) && isset( $_POST['banlist'] ) ) {
  $banlist = str_replace( "\r\n", "\n", $_POST['banlist'] );
  $banlist = str_replace( "\r", "\n", $banlist );
  if(version_compare(phpversion(),"4.3.0")=="-1") {
     $banlist = mysql_escape_string($banlist);
   } else {
     $banlist = mysql_real_escape_string($banlist);
   }
  $query = "UPDATE {$pixelpost_db_prefix}ban SET banlist='$banlist' LIMIT 1";
  mysql_query($query) or die( mysql_error() );
}

// Get the ban list
$query = "SELECT banlist FROM {$pixelpost_db_prefix}ban LIMIT 1";
$result = mysql_query($query) or die( mysql_error() );
if( $row = mysql_fetch_row($result) ) {
  $banlist = $row[0];
  $banlistarray = explode( "\n", $banlist );
} else {
  $banlist = '';
  $banlistarray = array();
}

if( isset( $_POST['antispamclean'] ) ) {
  $where = ' 0 OR';
  if( isset( $_POST['referers'] )) {
    foreach( $banlistarray as $banword ) {
      $banword = trim($banword);
      if( $banword == '' ) continue;
      $where .= " referer LIKE '%{$banword}%' OR";
    }
    // Delete evil referers
    $query = "DELETE FROM {$pixelpost_db_prefix}visitors WHERE $where 0";
    //echo $query;
    mysql_query( $query ) or die( mysql_error());
    $deleted_rows = mysql_affected_rows();
    $deleted_rows_str = "<font color='red'><b>$deleted_rows</b> referers were deleted from the visitors table</font><br /><br />";
  } else if ( isset( $_POST['comments'] )) {
    foreach( $banlistarray as $banword ) {
      $banword = trim($banword);
      if( $banword == '' ) continue;
      $where .= " name LIKE '%{$banword}%' OR url LIKE '%{$banword}%' OR message LIKE '<a href=%{$banword}%</a>' OR";
    }
    // Delete evil comments
    $query = "DELETE FROM {$pixelpost_db_prefix}comments WHERE $where 0";
    //echo $query;
    mysql_query( $query ) or die( mysql_error());
    $deleted_rows = mysql_affected_rows();
    $deleted_rows_str = "<font color='red'><b>$deleted_rows</b> comments were deleted</font><br /><br />";
  }
	else if ( isset( $_POST['emptyreflist'] )) {
	// empty the visitor-list, which contains all referrers, completely
	$query = "TRUNCATE {$pixelpost_db_prefix}visitors";
	mysql_query( $query ) or die( mysql_error());
  $deleted_rows_str = "<font color='red'><b>The visitor-list is emptied completely. </b></font><br /><br />";
	}

}

$addon_description = "<b>This AddOn will help you clean out your referers page and comments.</b><br \>
	<br />Creates a list of url's/keywords to be deleted from the referers table or the comments.
  <br />Add lists of banned words to the textbox below, one word per line.
	<br />Be careful with short words like sex which could form part of a valid url.<br />
	<br \>Choose <b>UPDATE BANLIST</b> to add these words the banlist.<br \>
	<br \>Choose <b>CLEAN REFERRERS</b> to delete any referer with that word in the url from the visitors table.
	<br \>Choose <b>CLEAN COMMENTS</b> to delete any comment with that word in the url from the comment table.<br />
  <form method='post' action='index.php?view=addons'>
  <input type='hidden' name='antispamclean' value='1' />
  <input type='submit' name='referers' value='Clean Referers' />
  <input type='submit' name='comments' value='Clean Comments' />
	<br \><br \><span style='font-weight:bold;color:red;'>Emergency Exit, when hit by SPAM:<br />Choose <b>EMPTY REFERRER-LIST</b> to empty the visitorlist, which contains the referrers, <b>completely.</b></span><br /><br \>
  <input type='submit' name='emptyreflist' value='Empty Referrer-List completely' style='background-color:red;color:black;' />
  </form>

  $deleted_rows_str

  <b>Banned words</b><br />
  <form method='post' action='index.php?view=addons'>
  <input type='hidden' name='antispamupdate' value='1'>
  <textarea name='banlist' value='50' style='width:300px;height:100px;'>$banlist</textarea><br \>
  <input type='submit' value='Update Banlist'>
  </form>";
}
?>
