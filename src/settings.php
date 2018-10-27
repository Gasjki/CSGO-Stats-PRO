<?php
$steamauth['apikey']      = ""; // Your Steam WebAPI-Key found at http://steamcommunity.com/dev/apikey
$steamauth['domainname']  = "http://localhost/"; // The main URL of your website displayed in the login page . PUT slash at the end
$steamauth['buttonstyle'] = "small"; // Style of the login button [small | large_no | large]
$steamauth['logoutpage']  = "index.php"; // Page to redirect to after a successfull logout - NO slash at the beginning!
$steamauth['loginpage']   = "index.php"; // Page to redirect to after a successfull login - NO slash at the beginning!

// Site information
$site['title']              = "CS:GO Stats PRO v2.1";
$site['logo']               = "images/logo.png";
$site['favicon']            = "images/favicon.png";
$site['owner']              = "EVOScripts";
$site['email']              = "evoscripts@domain.tld";
$site['keywords']           = "csgo stats, csgo player stats, csgo codecanyon, script csgo stats";
$site['facebook']           = ""; //  Leave it empty if you don't want to show facebook
$site['twitter']            = ""; //  Leave it empty if you don't want to show twitter
$site['vk']                 = ""; //  Leave it empty if you don't want to show vk
$site['mysite']             = "http://localhost"; //  Leave it empty if you don't want to show your site
$site['mysteamprofile']     = "http://steamcommunity.com/#";
$site['copyright']          = true; // Show copyright?
$site['copyrightmessage']   = "Visit http://localhost/"; // if $site['copyright'] it's zero, this will be ignored !
$site['messageCoordinates'] = 180; // if $site['copyright'] it's zero, this will be ignored ! With this you can set your message horizontal coordinate

// System stuff
if (empty($steamauth['apikey'])) {
    die("<div style='display: block; width: 100%; background-color: red; text-align: center;'>SteamAuth:<br>Please supply an API-Key!</div>");
}

if (empty($steamauth['domainname'])) {
    $steamauth['domainname'] = "http://localhost/";
}

if ($steamauth['buttonstyle'] != "small" and $steamauth['buttonstyle'] != "large") {
    $steamauth['buttonstyle'] = "large_no";
}