<?php
/*
Script Name: No Refer Spam
Version: 1.00
Hack URI: http://frenchfragfactory.net/ozh/archives/2005/02/05/no-refer-spam/
Description: Send refer spammers back to their own sites
Author: Ozh
Author URI: http://planetOzh.com
*/

$spams = array (
".com.cn",
"3d.net",
"4u.com",
"4u.net",
"6q.org",
"7h.com",
"9k.com",
"adipex",
"advicer",
"agama.cn",
"ambien",
"ambien",
"andrewsaluk.com",
"baccarrat",
"bigsitecity",
"bizhat",
"bllogspot",
"booker",
"carisoprodol",
"casino",
"celebrex",
"chat-nett.com",
"chatroom",
"chinamoulds",
"cialis",
"cool-extreme.com",
"coresat.com",
"crescentarian.net",
"cwas",
"cyclen",
"cyclobenzaprine",
"czcn",
"czpcsj",
"day-trading",
"discreetordering",
"doobu.com",
"drugstore.info",
"dutyfree",
"duty-free",
"exitq.com","cxa.de",
"findmore.org",
"findwebhostingnow.com",
"fioricet",
"flowersdeliveredquick.com",
"freakycheats.com",
"freemovie",
"freenet-shopping",
"fuck-fest",
"future-2000",
"-gambling",
"glwb.info",
"guide.info",
"holdem",
"hydrocodone",
"incest",
"iqwide",
"isacommie.com",
"italiancharms",
"izhuqiu",
"jewelrycity",
"jvl.com",
"kloony",
"lemonrider",
"levitra",
"likejazz",
"macinstruct",
"macvillage.net",
"meridia",
"musicbox1.com",
"newru",
"nutzu",
"offshore",
"-online",
"paris-hilton",
"paxil",
"pervertedtaboo.com",
"pharmacy.info",
"phentemine",
"phentermine",
"plasticmachinery", 
"platinum-celebs",
"poker",
"poker-chip",
"poze",
"prescription",
"propecia",
"psxtreme.com",
"ronnieazza",
"roody.com",
"-sex",
"sina.com",
"slot-machine",
"smsportali.net",
"soma",
"spb.ru",
"sphosting.com",
"sysrem03.com",
"taboo",
"teen",
"terashells.com",
"tigerspice",
"trackerom.com",
"tramadol",
"trim-spa",
"ultram",
"valium",
"viagra",
"vicodin",
"xanax",
);

 
$reft = $_SERVER["HTTP_REFERER"];

if ($reft) {
   foreach ($spams as $site) {
      $pattern = "/$site/i";
      if (preg_match ($pattern, $reft)) {
         header("Location: $reft"); exit();
      }
   }
}
?>