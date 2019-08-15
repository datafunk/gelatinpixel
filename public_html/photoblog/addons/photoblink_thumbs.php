<?php

$addon_name = "Photoblink Thumbnails";
$addon_description = "Displays small size thumbs with Overlib behavior showing the fullsize thumbs when hovering over them. <br/> This will add two new tags:
<p>&lt;PB_THUMBS_ROW&gt; Photblink thumbnail row</p>
<p>&lt;PB_THUMBS_ROW_REV&gt; Photblink thumbnail row in reverse order</p>
";
$addon_version = "1.0";

$html_for_link = "o";

	$tz = $cfgrow['timezone'];
	$pbdatetime = gmdate("Y-m-d H:i:s",time()+(3600 * $tz)); // current date+time
	$pbcdate = $pbdatetime;				// for future posting, current date+time
	
$showprefix = "index.php?showimage=";

if ($_GET['view']!='addons')
{
  // Get Current Image.
	if($_GET['showimage'] == "")
	{
		$row = sql_array("SELECT * FROM ".$pixelpost_db_prefix."pixelpost WHERE datetime<='$pbcdate' order by datetime DESC limit 0,1");
	}
	else
	{
		$row = sql_array("SELECT * FROM ".$pixelpost_db_prefix."pixelpost WHERE (id='".$_GET['showimage']."') AND datetime<='$pbcdate'");
	}

	if(!$row['image'])
	{
		echo "Coming Soon! Nothing to show. No image to show here!";
		exit;
	}

	$image_name         = $row['image'];
	$image_title        = pullout($row['headline']);
	$image_id           = $row['id'];	
	$image_datetime     = $row['datetime'];
	
// check that gd is here before this
$aheadnumb = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > '$image_datetime') and (datetime<='$pbcdate')");
$aheadnumb = $aheadnumb['count'];
$behindnumb = sql_array("SELECT count(*) as count FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime < '$image_datetime') and (datetime<='$pbcdate')");
$behindnumb = $behindnumb['count'];
$aheadlimit = round(($cfgrow['thumbnumber']-1)/2);
$behindlimit = round(($cfgrow['thumbnumber']-1)/2);

if($aheadnumb <= $aheadlimit)
{
	$behindlimit = ($cfgrow['thumbnumber']-1)-$aheadnumb;
	$aheadlimit = $aheadnumb;
}

if($behindnumb <= $behindlimit)
{
	$aheadlimit = ($cfgrow['thumbnumber']-1)-$behindnumb;
	$behindlimit = $behindnumb;
}

$totalthumbcounter = 1;
$ahead_thumbs_pb = "";
$ahead_thumbs_pb_reverse  ="";
$thumbs_ahead = mysql_query("SELECT id,headline,image FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > '$image_datetime') and (datetime<='$pbcdate') order by datetime asc limit 0,$aheadlimit");

while(list($id,$headline,$image) = mysql_fetch_row($thumbs_ahead))
{
	$headline = clean($headline);
	$headline = htmlspecialchars($headline,ENT_QUOTES);
	list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image_name");
	$ahead_thumbs_pb .= " <a href='$showprefix$id' onmouseover=\"return overlib('&lt;img src=\'thumbnails/thumb_$image\' title=\'".$headline."\' /&gt;', CAPTION, '$headline', WIDTH, '".$cfgrow['thumbwidth']."',CAPCOLOR, '#000000',TEXTCOLOR, '#000000' ,BGCOLOR, '#cc2420', FGCOLOR, '#000000', RIGHT);\" onmouseout=\"return nd();\" ><img src='thumbnails/thumb_$image'  height='28px' alt='tiny thumbs'/> </a>";
	$ahead_thumbs_pb_reverse = "<a href='$showprefix$id' onmouseover=\"return overlib('&lt;img src=\'thumbnails/thumb_$image\' title=\'".$headline."\' /&gt;', CAPTION, '$headline', WIDTH, '".$cfgrow['thumbwidth']."',CAPCOLOR, '#000000',TEXTCOLOR, '#000000' ,BGCOLOR, '#cc2420', FGCOLOR, '#000000', RIGHT);\" onmouseout=\"return nd();\" ><img src='thumbnails/thumb_$image'  height='28px' alt='tiny thumbs'/> </a> ".$ahead_thumbs_pb_reverse ;

	$totalthumbcounter++;
}

$behind_thumbs_pb = "";
$behind_thumbs_pb_reverse ="";
$thumbs_behind = mysql_query("SELECT id,headline,image FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime < '$image_datetime') and (datetime<='$pbcdate') order by datetime desc limit 0,$behindlimit");

while(list($id,$headline,$image) = mysql_fetch_row($thumbs_behind))
{
	$headline = clean($headline);
	$headline = htmlspecialchars($headline,ENT_QUOTES);
	list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image_name");
	$behind_thumbs_pb_reverse .= "<a href='$showprefix$id' onmouseover=\"return overlib('&lt;img src=\'thumbnails/thumb_$image\' title=\'".$headline."\' /&gt;', CAPTION, '$headline', WIDTH, '".$cfgrow['thumbwidth']."'CAPCOLOR, '#000000',TEXTCOLOR, '#000000' ,BGCOLOR, '#cc2420', FGCOLOR, '#000000', RIGHT);\" onmouseout=\"return nd();\" ><img src='thumbnails/thumb_$image'  height='28px' alt='tiny thumbs'/> </a> ";
	$behind_thumbs_pb = " <a href='$showprefix$id' onmouseover=\"return overlib('&lt;img src=\'thumbnails/thumb_$image\' title=\'".$headline."\' /&gt;', CAPTION, '$headline', WIDTH, '".$cfgrow['thumbwidth']."',CAPCOLOR, '#000000',TEXTCOLOR, '#000000' ,BGCOLOR, '#cc2420', FGCOLOR, '#000000', RIGHT);\" onmouseout=\"return nd();\" ><img src='thumbnails/thumb_$image' height='28px' alt='tiny thumbs'/> </a>".$behind_thumbs_pb ;

	$totalthumbcounter++;
}


	
list($local_width,$local_height,$type,$attr) = getimagesize("thumbnails/thumb_$image_name");
	$image_title = clean($image_title);
	$image_title = htmlspecialchars($image_title,ENT_QUOTES);
$curret_thumb_pb = "<a href='$showprefix$image_id' onmouseover=\"return overlib('&lt;img src=\'thumbnails/thumb_$image_name\' title=\'".$image_title."\' / &gt;',  CAPTION, '$image_title', WIDTH, '".$cfgrow['thumbwidth']."',CAPCOLOR, '#000000',TEXTCOLOR, '#000000' ,BGCOLOR, '#cc2420', FGCOLOR, '#000000', RIGHT);\" onmouseout=\"return nd();\" >[<img src='thumbnails/thumb_$image_name'  height='28px' alt='tiny thumbs'/>]</a>";
$thumbnail_photoblink_row = "$behind_thumbs_pb ".$curret_thumb_pb." $ahead_thumbs_pb";
$thumbnail_photoblink_row_reverse = "$ahead_thumbs_pb_reverse<a href='$showprefix$image_id'>".$curret_thumb_pb."</a> $behind_thumbs_pb_reverse";
$tpl = ereg_replace("<PB_THUMBS_ROW>",$thumbnail_photoblink_row,$tpl);
$tpl = ereg_replace("<PB_THUMBS_ROW_REV>",$thumbnail_photoblink_row_reverse,$tpl);
}
?>