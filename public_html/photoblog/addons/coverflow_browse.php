<?php
$addon_name = "Coverflow Browse";
$addon_version = "1.0";
$addon_description = "This addon will display coverflow layout";


if (preg_match("<LARGEBROWSE>", $tpl)) {
	if ($_GET[category]){
		$query = "select * from ".$pixelpost_db_prefix."pixelpost, ".$pixelpost_db_prefix."catassoc where (datetime<='$cdate') and ".$pixelpost_db_prefix."pixelpost.id=image_id and cat_id=" . $_GET['category'] . " order by ".$pixelpost_db_prefix."pixelpost.id DESC";
		$results= mysql_query($query);
	while ($row=mysql_fetch_array($results)) {
		$images .= "<img src='reflect.php?img=thumbnails/thumb_$row[image]' alt='$row[headline]' longdesc='index.php?showimage=$row[id]'/>\n";
	}
	} else if ($_GET[tag]){
		$query = "select * from ".$pixelpost_db_prefix."pixelpost, ".$pixelpost_db_prefix."tags where (datetime<='$cdate') and (tag = '$_GET[tag]' or alt_tag='$_GET[tag]') and (id=img_id) order by id DESC";
		$results= mysql_query($query);
	while ($row=mysql_fetch_array($results)) {
		$images .= "<img src='reflect.php?img=thumbnails/thumb_$row[image]' alt='$row[headline]' longdesc='index.php?showimage=$row[id]'/>\n";
		}
	} else {
		$query = "select * from ".$pixelpost_db_prefix."pixelpost where (datetime<='$cdate') order by id DESC";
		$results= mysql_query($query);
	while ($row=mysql_fetch_array($results)) {
		$images .= "<img src='reflect.php?img=thumbnails/thumb_$row[image]' alt='$row[headline]' longdesc='index.php?showimage=$row[id]'/>\n";
	}
	}
	
	
	$tpl = str_replace("<LARGEBROWSE>",$images,$tpl);
}



?>