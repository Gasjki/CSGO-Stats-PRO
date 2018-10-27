<?php
include("core.php");

/**
 * Create rank image by given rank.
 *
 * @param $rank
 *
 * @return string
 */
function createRankImage($rank)
{
    switch (true) {
        case ($rank >= 1 && $rank <= 18):
            return "images/ranks/{$rank}.png";
            break;
        default:
            return 'images/ranks/0.png';
            break;
    }
}

/**
 * Create XP rank image by given xp rank.
 *
 * @param $xpRank
 *
 * @return string
 */
function createXPRankImage($xpRank)
{
    switch (true) {
        case ($xpRank >= 1 && $xpRank <= 40):
            return "images/xpranks/{$xpRank}.png";
            break;
        default:
            return 'images/xpranks/1.png';
            break;
    }
}

/**
 * Create background image and return it.
 *
 * @param $background
 *
 * @return resource
 */
function createBackgroundImage($background)
{
    switch ($background) {
        case 'bg1':
        case 'bg2':
        case 'bg3':
        case 'bg4':
        case 'bg5':
        case 'bg6':
        case 'bg7':
        case 'bg8':
        case 'bg9':
        case 'bg10':
        case 'bg11':
        case 'bg12':
        case 'bg13':
        case 'bg14':
        case 'bg15':
        case 'bg16':
            return imagecreatefrompng("images/banners/{$background}.png");
            break;
        default:
            return imagecreatefrompng('images/banners/bg1.png');
            break;
    }
}

/**
 * Validate given color.
 *
 * @param $string
 * @param $default
 *
 * @return string
 */
function validateRegexColor($string, $default)
{
    if (!preg_match('/^[abcdefABCDEF0-9]{6}$/', $string)) {
        return $default;
    }

    return $string;
}

// Get variables.
$colorTitle    = !isset($_GET['color_title']) ? 'FFC200' : validateRegexColor(substr($_GET['color_title'], 0, 6), 'FFC200');
$colorInfo     = !isset($_GET['color_info']) ? 'FFFFFF' : validateRegexColor(substr($_GET['color_info'], 0, 6), 'FFFFFF');
$colorUsername = !isset($_GET['color_username']) ? 'FFFFFF' : validateRegexColor(substr($_GET['color_username'], 0, 6), 'FFFFFF');
$background    = !isset($_GET['bg']) ? 'bg1' : trim($_GET['bg']);
$rank          = !isset($_GET['rank']) ? 0 : (int) trim($_GET['rank']);
$xpRank        = !isset($_GET['xp_rank']) ? 1 : (int) trim($_GET['xp_rank']);
$steamId64     = !isset($_GET['id']) ? 0 : $_GET['id'];

// Get account information.
$user = getAccountBySteamId($steamId64);
$game = [];

if ($user) {
    if ($user['privacy'] === 3 && $user['hasCSGO'] === true) {
        $game = getGameData($user['steam_id']);
    }
}

// Images.
$rankImage   = imagecreatefrompng(createRankImage($rank));
$xpRankImage = imagecreatefrompng(createXPRankImage($xpRank));
$im          = createBackgroundImage($background);

if (array_key_exists('last_match_favweapon_id', $game)) {
    $lastMatchFavoriteWeapon = getWeaponNameById($game['last_match_favweapon_id']);
    $weaponImage             = imagecreatefrompng("images/weapons/hud/{$lastMatchFavoriteWeapon}.png");
}

// Colors.
$colorGreen           = imagecolorallocate($im, 0, 183, 21);
$colorRed             = imagecolorallocate($im, 255, 0, 0);
$colorTitle           = imagecolorallocate($im, hexdec(substr($colorTitle, 0, 2)), hexdec(substr($colorTitle, 2, 2)), hexdec(substr($colorTitle, 4, 2)));
$colorInfo            = imagecolorallocate($im, hexdec(substr($colorInfo, 0, 2)), hexdec(substr($colorInfo, 2, 2)), hexdec(substr($colorInfo, 4, 2)));
$colorUsername        = imagecolorallocate($im, hexdec(substr($colorUsername, 0, 2)), hexdec(substr($colorUsername, 2, 2)), hexdec(substr($colorUsername, 4, 2)));
$colorOnline          = imagecolorallocate($im, hexdec('24'), hexdec('FF'), hexdec('00'));
$colorOffline         = imagecolorallocate($im, hexdec('FF'), hexdec('00'), hexdec('00'));
$colorBlack           = imagecolorallocate($im, hexdec('00'), hexdec('00'), hexdec('00'));
$colorBlue            = imagecolorallocate($im, hexdec('90'), hexdec('C3'), hexdec('D4'));
$colorBackgroundGreen = imagecolorallocate($im, 0, 160, 0);

// Format user avatar and username (if user found).
$width  = 0;
$height = 0;
if ($user) {
    $newAvatarWidth  = 90;
    $newAvatarHeight = 90;

    if (array_key_exists('avatar', $user)) {
        list($width, $height) = getimagesize($user['avatar']);
        $source = imagecreatefromjpeg($user['avatar']);
    }

    // Username length.
    $user['username'] = strlen($user['username']) > 21 ? substr($user['username'], 0, 21) . '...' : $user['username'];
}

// Set another variables.
$game['kd_ratio']  = @round($game['total_kills'] / $game['total_deaths'], 2);
$game['win_ratio'] = @round(($game['total_wins'] / $game['total_rounds_played']) * 100, 2);

// Set final variables.
$username                = array_key_exists('username', $user) ? $user['username'] : "This user doesn't exist!";
$lastMatchFavoriteWeapon = isset($lastMatchFavoriteWeapon) ? $lastMatchFavoriteWeapon : 'Knife';
$hoursPlayed             = array_key_exists('hoursPlayed', $user) ? $user['hoursPlayed'] : 'X.X';
$privacy                 = array_key_exists('privacy', $user) ? $user['privacy'] : 1;
$hasCSGO                 = array_key_exists('hasCSGO', $user) ? $user['hasCSGO'] : false;
$customStatusMessage     = array_key_exists('custom_message', $user) ? $user['custom_message'] : 'Unknown';

// Format status message.
$lastOnlineMessage = 'Last Online';
$search            = strpos($customStatusMessage, $lastOnlineMessage);
$inCSGO            = 'In-Game<br/>Counter-Strike: Global Offensive';
$searchGame        = strpos($customStatusMessage, $inCSGO);
$inGame            = 'In-Game<br/>';
$searchInGame      = strpos($customStatusMessage, $inGame);

// Create banner.
if ($privacy !== 3) {
    imagettftext($im, 12, 0, 100, 50, $colorRed, 'fonts/ArchivoNarrow.ttf', 'This account is private!');
} elseif (!$hasCSGO) {
    imagettftext($im, 8, 0, 45, 50, $colorRed, 'fonts/ArchivoNarrow.ttf', "This account doesn't have Counter-Strike: Global-Offensive!");
} else {
    if ($customStatusMessage == "Online") {
        imagettftext($im, 9, 90, 347, 63, $colorBackgroundGreen, 'fonts/ArchivoNarrow.ttf', "Online");
    } elseif ($search !== false) {
        imagettftext($im, 9, 90, 347, 63, $colorRed, 'fonts/ArchivoNarrow.ttf', "Offline");
    } elseif ($searchGame !== false) {
        imagettftext($im, 9, 90, 347, 83, $colorBackgroundGreen, 'fonts/ArchivoNarrow.ttf', "Playing CS:GO");
    } elseif ($searchInGame !== false) {
        imagettftext($im, 8, 90, 347, 86, $colorBlue, 'fonts/ArchivoNarrow.ttf', "IN OTHER GAME");
    }

    imagecopyresized($im, $source, 0, 0, 0, 0, $newAvatarWidth, $newAvatarHeight, $width, $height); // Avatar
    imagettftext($im, 9, 0, 175, 20, $colorUsername, 'fonts/ArchivoNarrow.ttf', $username); // Username
    imagecopy($im, $rankImage, 96, 5, 0, 0, imagesx($rankImage), imagesy($rankImage)); // Competitive rank
    imagecopy($im, $xpRankImage, 149, 4, 0, 0, imagesx($xpRankImage), imagesy($xpRankImage)); // XP rank
    imagecopy($im, $weaponImage, 107, 30, 0, 0, imagesx($weaponImage), imagesy($weaponImage)); // Weapon image
    imagettftext($im, 9, 0, 117, 70, $colorTitle, 'fonts/ArchivoNarrow.ttf', $lastMatchFavoriteWeapon); // Weapon name
    imagettftext($im, 11, 0, 175, 52, $colorInfo, 'fonts/ArchivoNarrow.ttf', $game['kd_ratio']); // KDR
    imagettftext($im, 9, 0, 177, 70, $colorTitle, 'fonts/ArchivoNarrow.ttf', "K/D"); // KDR title
    imagettftext($im, 11, 0, 220, 52, $colorInfo, 'fonts/ArchivoNarrow.ttf', $game['win_ratio'] . "%"); // Win %
    imagettftext($im, 9, 0, 222, 70, $colorTitle, 'fonts/ArchivoNarrow.ttf', "WIN %"); // Win % title
    imagettftext($im, 11, 0, 280, 52, $colorInfo, 'fonts/ArchivoNarrow.ttf', $hoursPlayed . "h");
    imagettftext($im, 9, 0, 282, 70, $colorTitle, 'fonts/ArchivoNarrow.ttf', "TIME");
}

// Show copyright.
if ($site['copyright']) {
    imagettftext($im, 7, 0, $site['messageCoordinates'], 87, $colorInfo, 'fonts/ArchivoNarrow.ttf', $site['copyrightmessage']);
}

header('Content-type: image/png');
imagepng($im);
imagedestroy($im);