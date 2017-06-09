<?php

/*================================================================
// Thumb regenerator addon
// Requires Pixelpost version 1.4 or newer
//
// $Rev: 58 $
// $LastChangedBy: $
// $LastChangedDate: 2007-11-11 15:50:16 +0100 (N, 11 lis 2007) $
//
// Written by: Piotr "GeoS" Galas
// @:			piotr@piotrgalas.com
// WWW:		http://piotrgalas.com/
//
// Pixelpost www: http://www.pixelpost.org/
//
// License: http://www.gnu.org/copyleft/gpl.html
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

if($admin_panel == 1)
{
	$th_reg_paypal_link = 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=piotr%2egalas%2bpaypal%40gmail%2ecom&item_name=Donation%20for%20Piotr%20Galas%20%2d%20Pixelpost%20Developer&no_shipping=1&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8';
	if( isset($_POST['regeneratethumbs']) && (isset($_POST['start_id']) || isset($_POST['end_id']) ) )
	{
		require_once("../includes/functions.php");
	
		if(isset($_POST['start_id']) && isset($_POST['end_id']))
		{
			if($_POST['start_id'] > $_POST['end_id'])
			{
				$temp = $_POST['end_id'];
				$_POST['end_id'] = $_POST['start_id'];
				$_POST['start_id'] = $temp;
			}
	
			$where = " WHERE id BETWEEN " . $_POST['start_id'] . " AND " . $_POST['end_id'];
		}
		else
		{
			if(isset($_POST['end_id'])) $_POST['start_id'] = $_POST['end_id'];
			$where = " WHERE id = " . $_POST['start_id'];
		}
	
	
		$query = "SELECT image FROM ".$pixelpost_db_prefix."pixelpost" . $where;
	
		$count = 0;
		$fname = mysql_query($query);
		while($row = mysql_fetch_array($fname))
		{
			createthumbnail($row['image']);
			$count++;
		}
	
		$message = "<hr><b>Re-generated $count thumbnails.</b>";
	}
	
	
	$query = "SELECT id FROM ".$pixelpost_db_prefix."pixelpost ORDER BY id DESC";
	$data = mysql_query($query);
	
	$option = "		<option value=''></option>\n";
	
	while($row = mysql_fetch_row($data))
	{
		$option .= "		<option value='" . $row[0] . "'>" . $row[0] . "</option>\n";
	}
	
	$addon_name = "Pixelpost Thumbs Re-generator";
	$addon_description = "Re-generate selected thumbnails<br />
	This addon re-generate thumbnails from selected range.<br />
	Remember that to many selected images can return script timeout as the result.<p />
	
			<form method='post' action='index.php?view=addons' accept-charset='UTF-8'>
			<input type='hidden' name='regeneratethumbs' value='1'>
			First #: <select name='start_id'>
	$option
			</select><p />
			Last #: <select name='end_id'>
	$option
			</select><p/>
			<input type='submit' value='Re-generate thumbnails'>
		</form> $message";
	$addon_description .= '<br/><br/>
<b style="font-size:12px;color:#8F006B">Author:</b><br/>
<b>@: <a href="mailto:geos@pixelpost.org">Piotr "GeoS" Galas</a><br/>
WWW: <a href="http://techblog.piotrgalas.com/pixelpost-addons/#threg" target="_blank">http://techblog.piotrgalas.com/</a></b><p/><a href="'.$th_reg_paypal_link.'" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" alt="Płać przez system PayPal — jest szybki, bezpłatny i bezpieczny!"></a>';
	$addon_version = "\$Rev: 58 $";
}
?>