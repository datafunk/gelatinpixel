<?php

/*

Pixelpost version 1.4.3

Pixelpost www: http://www.pixelpost.org/

Version 1.4.3:
Development Team:
Ramin Mehran, Connie Mueller-Goedecke, Robert Prouse, Will Duncan, Joseph Spurling, GeoS
Version 1.1 to Version 1.3: Linus <http://www.shapestyle.se>

Contact: thecrew@pixelpost.org
Copyright © 2005 Pixelpost.org <http://www.pixelpost.org>


License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

*/

require("../includes/pixelpost.php");
require('../includes/create_tables.php');

?>
<html>
<head>
<style type="text/css">
body {
    font-family:Verdana, helvetica, sans-serif;
    font-size:12px;
    color:#333;
    }
b {
    color:#008000;
    font-weight:bold;
    }
h1 {
    font-family:Helvetica, verdana, sans-serif;
    letter-spacing:-1px;
    font-size:24px;
    color:#000;
    }
</style>
</head>
<body>
<b>Welcome to Pixelpost 1.4.1 upgrade from version 1.4BETA!</b>
<h1>STEP 1: Update database</h1>
This will add two new columns to pixelpost config table.<p />&nbsp;&nbsp;

<?php


/* start up mysql */
mysql_connect($pixelpost_db_host, $pixelpost_db_user, $pixelpost_db_pass) || die("Error: ". mysql_error());
mysql_select_db($pixelpost_db_pixelpost) || die("Error: ". mysql_error());
//    Checks whether specified field exists in current or specified table.
$fieldname = "catgluestart";

$table = $pixelpost_db_prefix ."config";
$fieldexists = 0;
$t = 0;
$attention_call = "";
if ($table != "") {
    if ($table_name != "") $current_table = $table;
    $result_id = mysql_list_fields( $pixelpost_db_pixelpost, $table );

    for ($t = 0; $t < mysql_num_fields($result_id); $t++) {
        if (strtolower( $fieldname) ==  strtolower(mysql_field_name($result_id, $t))) {
            $fieldexists = 1;
            break;
        }
    }
}
// if the field does not exit: Create it!
if ($fieldexists==0) {
	// Customizable category links
	mysql_query("
	ALTER TABLE `{$pixelpost_db_prefix}config` ADD `catgluestart` varchar(5) DEFAULT '[' NOT NULL
	") or die("Error: ". mysql_error());
	mysql_query("
	ALTER TABLE `{$pixelpost_db_prefix}config` ADD `catglueend` varchar(5) DEFAULT ']' NOT NULL
	") or die("Error: ". mysql_error());
	echo "Added customizable category links support...<p />";
}

// check if the version is 1.4 change it to v1.4.1
$installed_version = Get_Pixelpost_Version( $pixelpost_db_prefix );
if ($installed_version == 1.4)
 UpgradeTo141( $pixelpost_db_prefix )
?>
<h1>Step 2: Done.</h1>
Next thing you want to do is <a href="index.php"> login to the admin interface</a> and get started posting.<p />&nbsp;&nbsp;
</body></html>