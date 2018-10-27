<?php
error_reporting(E_ALL);
ob_start();
@session_start();
include "settings.php";
include "steamauth.php";

// Text boxes

function error($msg)
{
    echo '		<br /><div class="alert alert-danger">
                    <button class="close" data-dismiss="alert">X</button>
                    <p>' . $msg . '</p>
                </div>';
}

function warning($msg)
{
    echo '		<br /><div class="alert alert-warning">
                    <button class="close" data-dismiss="alert">X</button>
                    <p>' . $msg . '</p>
                </div>';
}

function ok($msg)
{
    echo '		<br /><div class="alert alert-success">
                    <button class="close" data-dismiss="alert">X</button>
                    <p>' . $msg . '</p>
                </div> ';
}

function info($msg)
{
    echo '		<br /><div class="alert alert-info">
                <button class="close" data-dismiss="alert">X</button>
                <p>' . $msg . '</p>
                </div>';
}

function format($id)
{
    $string = 'https://steamcommunity.com';

    return strpos($id, $string) !== false;
}

function replace_country($code)
{
    if ($code == 'AF') $country = 'Afghanistan';
    if ($code == 'AX') $country = 'Aland Islands';
    if ($code == 'AL') $country = 'Albania';
    if ($code == 'DZ') $country = 'Algeria';
    if ($code == 'AS') $country = 'American Samoa';
    if ($code == 'AD') $country = 'Andorra';
    if ($code == 'AO') $country = 'Angola';
    if ($code == 'AI') $country = 'Anguilla';
    if ($code == 'AQ') $country = 'Antarctica';
    if ($code == 'AG') $country = 'Antigua and Barbuda';
    if ($code == 'AR') $country = 'Argentina';
    if ($code == 'AM') $country = 'Armenia';
    if ($code == 'AW') $country = 'Aruba';
    if ($code == 'AU') $country = 'Australia';
    if ($code == 'AT') $country = 'Austria';
    if ($code == 'AZ') $country = 'Azerbaijan';
    if ($code == 'BS') $country = 'Bahamas the';
    if ($code == 'BH') $country = 'Bahrain';
    if ($code == 'BD') $country = 'Bangladesh';
    if ($code == 'BB') $country = 'Barbados';
    if ($code == 'BY') $country = 'Belarus';
    if ($code == 'BE') $country = 'Belgium';
    if ($code == 'BZ') $country = 'Belize';
    if ($code == 'BJ') $country = 'Benin';
    if ($code == 'BM') $country = 'Bermuda';
    if ($code == 'BT') $country = 'Bhutan';
    if ($code == 'BO') $country = 'Bolivia';
    if ($code == 'BA') $country = 'Bosnia and Herzegovina';
    if ($code == 'BW') $country = 'Botswana';
    if ($code == 'BV') $country = 'Bouvet Island (Bouvetoya)';
    if ($code == 'BR') $country = 'Brazil';
    if ($code == 'IO') $country = 'British Indian Ocean Territory (Chagos Archipelago)';
    if ($code == 'VG') $country = 'British Virgin Islands';
    if ($code == 'BN') $country = 'Brunei Darussalam';
    if ($code == 'BG') $country = 'Bulgaria';
    if ($code == 'BF') $country = 'Burkina Faso';
    if ($code == 'BI') $country = 'Burundi';
    if ($code == 'KH') $country = 'Cambodia';
    if ($code == 'CM') $country = 'Cameroon';
    if ($code == 'CA') $country = 'Canada';
    if ($code == 'CV') $country = 'Cape Verde';
    if ($code == 'KY') $country = 'Cayman Islands';
    if ($code == 'CF') $country = 'Central African Republic';
    if ($code == 'TD') $country = 'Chad';
    if ($code == 'CL') $country = 'Chile';
    if ($code == 'CN') $country = 'China';
    if ($code == 'CX') $country = 'Christmas Island';
    if ($code == 'CC') $country = 'Cocos (Keeling) Islands';
    if ($code == 'CO') $country = 'Colombia';
    if ($code == 'KM') $country = 'Comoros the';
    if ($code == 'CD') $country = 'Congo';
    if ($code == 'CG') $country = 'Congo the';
    if ($code == 'CK') $country = 'Cook Islands';
    if ($code == 'CR') $country = 'Costa Rica';
    if ($code == 'CI') $country = 'Cote d\'Ivoire';
    if ($code == 'HR') $country = 'Croatia';
    if ($code == 'CU') $country = 'Cuba';
    if ($code == 'CY') $country = 'Cyprus';
    if ($code == 'CZ') $country = 'Czech Republic';
    if ($code == 'DK') $country = 'Denmark';
    if ($code == 'DJ') $country = 'Djibouti';
    if ($code == 'DM') $country = 'Dominica';
    if ($code == 'DO') $country = 'Dominican Republic';
    if ($code == 'EC') $country = 'Ecuador';
    if ($code == 'EG') $country = 'Egypt';
    if ($code == 'SV') $country = 'El Salvador';
    if ($code == 'GQ') $country = 'Equatorial Guinea';
    if ($code == 'ER') $country = 'Eritrea';
    if ($code == 'EE') $country = 'Estonia';
    if ($code == 'ET') $country = 'Ethiopia';
    if ($code == 'FO') $country = 'Faroe Islands';
    if ($code == 'FK') $country = 'Falkland Islands (Malvinas)';
    if ($code == 'FJ') $country = 'Fiji the Fiji Islands';
    if ($code == 'FI') $country = 'Finland';
    if ($code == 'FR') $country = 'France, French Republic';
    if ($code == 'GF') $country = 'French Guiana';
    if ($code == 'PF') $country = 'French Polynesia';
    if ($code == 'TF') $country = 'French Southern Territories';
    if ($code == 'GA') $country = 'Gabon';
    if ($code == 'GM') $country = 'Gambia the';
    if ($code == 'GE') $country = 'Georgia';
    if ($code == 'DE') $country = 'Germany';
    if ($code == 'GH') $country = 'Ghana';
    if ($code == 'GI') $country = 'Gibraltar';
    if ($code == 'GR') $country = 'Greece';
    if ($code == 'GL') $country = 'Greenland';
    if ($code == 'GD') $country = 'Grenada';
    if ($code == 'GP') $country = 'Guadeloupe';
    if ($code == 'GU') $country = 'Guam';
    if ($code == 'GT') $country = 'Guatemala';
    if ($code == 'GG') $country = 'Guernsey';
    if ($code == 'GN') $country = 'Guinea';
    if ($code == 'GW') $country = 'Guinea-Bissau';
    if ($code == 'GY') $country = 'Guyana';
    if ($code == 'HT') $country = 'Haiti';
    if ($code == 'HM') $country = 'Heard Island and McDonald Islands';
    if ($code == 'VA') $country = 'Holy See (Vatican City State)';
    if ($code == 'HN') $country = 'Honduras';
    if ($code == 'HK') $country = 'Hong Kong';
    if ($code == 'HU') $country = 'Hungary';
    if ($code == 'IS') $country = 'Iceland';
    if ($code == 'IN') $country = 'India';
    if ($code == 'ID') $country = 'Indonesia';
    if ($code == 'IR') $country = 'Iran';
    if ($code == 'IQ') $country = 'Iraq';
    if ($code == 'IE') $country = 'Ireland';
    if ($code == 'IM') $country = 'Isle of Man';
    if ($code == 'IL') $country = 'Israel';
    if ($code == 'IT') $country = 'Italy';
    if ($code == 'JM') $country = 'Jamaica';
    if ($code == 'JP') $country = 'Japan';
    if ($code == 'JE') $country = 'Jersey';
    if ($code == 'JO') $country = 'Jordan';
    if ($code == 'KZ') $country = 'Kazakhstan';
    if ($code == 'KE') $country = 'Kenya';
    if ($code == 'KI') $country = 'Kiribati';
    if ($code == 'KP') $country = 'Korea';
    if ($code == 'KR') $country = 'Korea';
    if ($code == 'KW') $country = 'Kuwait';
    if ($code == 'KG') $country = 'Kyrgyz Republic';
    if ($code == 'LA') $country = 'Lao';
    if ($code == 'LV') $country = 'Latvia';
    if ($code == 'LB') $country = 'Lebanon';
    if ($code == 'LS') $country = 'Lesotho';
    if ($code == 'LR') $country = 'Liberia';
    if ($code == 'LY') $country = 'Libyan Arab Jamahiriya';
    if ($code == 'LI') $country = 'Liechtenstein';
    if ($code == 'LT') $country = 'Lithuania';
    if ($code == 'LU') $country = 'Luxembourg';
    if ($code == 'MO') $country = 'Macao';
    if ($code == 'MK') $country = 'Macedonia';
    if ($code == 'MG') $country = 'Madagascar';
    if ($code == 'MW') $country = 'Malawi';
    if ($code == 'MY') $country = 'Malaysia';
    if ($code == 'MV') $country = 'Maldives';
    if ($code == 'ML') $country = 'Mali';
    if ($code == 'MT') $country = 'Malta';
    if ($code == 'MH') $country = 'Marshall Islands';
    if ($code == 'MQ') $country = 'Martinique';
    if ($code == 'MR') $country = 'Mauritania';
    if ($code == 'MU') $country = 'Mauritius';
    if ($code == 'YT') $country = 'Mayotte';
    if ($code == 'MX') $country = 'Mexico';
    if ($code == 'FM') $country = 'Micronesia';
    if ($code == 'MD') $country = 'Moldova';
    if ($code == 'MC') $country = 'Monaco';
    if ($code == 'MN') $country = 'Mongolia';
    if ($code == 'ME') $country = 'Montenegro';
    if ($code == 'MS') $country = 'Montserrat';
    if ($code == 'MA') $country = 'Morocco';
    if ($code == 'MZ') $country = 'Mozambique';
    if ($code == 'MM') $country = 'Myanmar';
    if ($code == 'NA') $country = 'Namibia';
    if ($code == 'NR') $country = 'Nauru';
    if ($code == 'NP') $country = 'Nepal';
    if ($code == 'AN') $country = 'Netherlands Antilles';
    if ($code == 'NL') $country = 'Netherlands the';
    if ($code == 'NC') $country = 'New Caledonia';
    if ($code == 'NZ') $country = 'New Zealand';
    if ($code == 'NI') $country = 'Nicaragua';
    if ($code == 'NE') $country = 'Niger';
    if ($code == 'NG') $country = 'Nigeria';
    if ($code == 'NU') $country = 'Niue';
    if ($code == 'NF') $country = 'Norfolk Island';
    if ($code == 'MP') $country = 'Northern Mariana Islands';
    if ($code == 'NO') $country = 'Norway';
    if ($code == 'OM') $country = 'Oman';
    if ($code == 'PK') $country = 'Pakistan';
    if ($code == 'PW') $country = 'Palau';
    if ($code == 'PS') $country = 'Palestinian Territory';
    if ($code == 'PA') $country = 'Panama';
    if ($code == 'PG') $country = 'Papua New Guinea';
    if ($code == 'PY') $country = 'Paraguay';
    if ($code == 'PE') $country = 'Peru';
    if ($code == 'PH') $country = 'Philippines';
    if ($code == 'PN') $country = 'Pitcairn Islands';
    if ($code == 'PL') $country = 'Poland';
    if ($code == 'PT') $country = 'Portugal, Portuguese Republic';
    if ($code == 'PR') $country = 'Puerto Rico';
    if ($code == 'QA') $country = 'Qatar';
    if ($code == 'RE') $country = 'Reunion';
    if ($code == 'RO') $country = 'Romania';
    if ($code == 'RU') $country = 'Russian Federation';
    if ($code == 'RW') $country = 'Rwanda';
    if ($code == 'BL') $country = 'Saint Barthelemy';
    if ($code == 'SH') $country = 'Saint Helena';
    if ($code == 'KN') $country = 'Saint Kitts and Nevis';
    if ($code == 'LC') $country = 'Saint Lucia';
    if ($code == 'MF') $country = 'Saint Martin';
    if ($code == 'PM') $country = 'Saint Pierre and Miquelon';
    if ($code == 'VC') $country = 'Saint Vincent and the Grenadines';
    if ($code == 'WS') $country = 'Samoa';
    if ($code == 'SM') $country = 'San Marino';
    if ($code == 'ST') $country = 'Sao Tome and Principe';
    if ($code == 'SA') $country = 'Saudi Arabia';
    if ($code == 'SN') $country = 'Senegal';
    if ($code == 'RS') $country = 'Serbia';
    if ($code == 'SC') $country = 'Seychelles';
    if ($code == 'SL') $country = 'Sierra Leone';
    if ($code == 'SG') $country = 'Singapore';
    if ($code == 'SK') $country = 'Slovakia (Slovak Republic)';
    if ($code == 'SI') $country = 'Slovenia';
    if ($code == 'SB') $country = 'Solomon Islands';
    if ($code == 'SO') $country = 'Somalia, Somali Republic';
    if ($code == 'ZA') $country = 'South Africa';
    if ($code == 'GS') $country = 'South Georgia and the South Sandwich Islands';
    if ($code == 'ES') $country = 'Spain';
    if ($code == 'LK') $country = 'Sri Lanka';
    if ($code == 'SD') $country = 'Sudan';
    if ($code == 'SR') $country = 'Suriname';
    if ($code == 'SJ') $country = 'Svalbard & Jan Mayen Islands';
    if ($code == 'SZ') $country = 'Swaziland';
    if ($code == 'SE') $country = 'Sweden';
    if ($code == 'CH') $country = 'Switzerland, Swiss Confederation';
    if ($code == 'SY') $country = 'Syrian Arab Republic';
    if ($code == 'TW') $country = 'Taiwan';
    if ($code == 'TJ') $country = 'Tajikistan';
    if ($code == 'TZ') $country = 'Tanzania';
    if ($code == 'TH') $country = 'Thailand';
    if ($code == 'TL') $country = 'Timor-Leste';
    if ($code == 'TG') $country = 'Togo';
    if ($code == 'TK') $country = 'Tokelau';
    if ($code == 'TO') $country = 'Tonga';
    if ($code == 'TT') $country = 'Trinidad and Tobago';
    if ($code == 'TN') $country = 'Tunisia';
    if ($code == 'TR') $country = 'Turkey';
    if ($code == 'TM') $country = 'Turkmenistan';
    if ($code == 'TC') $country = 'Turks and Caicos Islands';
    if ($code == 'TV') $country = 'Tuvalu';
    if ($code == 'UG') $country = 'Uganda';
    if ($code == 'UA') $country = 'Ukraine';
    if ($code == 'AE') $country = 'United Arab Emirates';
    if ($code == 'GB') $country = 'United Kingdom';
    if ($code == 'US') $country = 'United States of America';
    if ($code == 'UM') $country = 'United States Minor Outlying Islands';
    if ($code == 'VI') $country = 'United States Virgin Islands';
    if ($code == 'UY') $country = 'Uruguay, Eastern Republic of';
    if ($code == 'UZ') $country = 'Uzbekistan';
    if ($code == 'VU') $country = 'Vanuatu';
    if ($code == 'VE') $country = 'Venezuela';
    if ($code == 'VN') $country = 'Vietnam';
    if ($code == 'WF') $country = 'Wallis and Futuna';
    if ($code == 'EH') $country = 'Western Sahara';
    if ($code == 'YE') $country = 'Yemen';
    if ($code == 'ZM') $country = 'Zambia';
    if ($code == 'ZW') $country = 'Zimbabwe';

    return $country;
}

/**
 * Get account information.
 *
 * @param $steamId
 *
 * @return array
 */
function getAccountBySteamId($steamId)
{
    global $steamauth;

    if (ctype_digit($steamId) && strlen($steamId) === 17) {
        $user = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $steamauth['apikey'] . "&steamids=" . $steamId;

        //  Initiate curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $user);
        $result = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($result, true);

        if (isset($user['response']['players'][0]['steamid'])) {
            $data = [
                'steam_id'              => $user['response']['players'][0]['steamid'],
                'custom_url'            => $user['response']['players'][0]['profileurl'],
                'username'              => $user['response']['players'][0]['personaname'],
                'avatar'                => $user['response']['players'][0]['avatarfull'],
                'privacy'               => (int) $user['response']['players'][0]['communityvisibilitystate'],
                'account_created'       => $user['response']['players'][0]['timecreated'],
                'location_country'      => isset($user['response']['players'][0]['loccountrycode']) ? $user['response']['players'][0]['loccountrycode'] : null,
                'hasCSGO'               => false,
                'hoursPlayed'           => 0,
                'hoursPlayedLast2Weeks' => 0
            ];

            if ($data['privacy'] === 3) {
                $games = simplexml_load_file($data['custom_url'] . "games?tab=all&xml=1");
                foreach ($games->games->game as $game) {
                    if ($game->appID == "730") {
                        $data['hasCSGO']     = true;
                        $data['hoursPlayed'] = $game->hoursOnRecord;

                        if (isset($game->hoursLast2Weeks)) {
                            $data['hoursPlayedLast2Weeks'] = $game->hoursLast2Weeks;
                        }
                    }
                }
            }

            $xmlUserProfile         = simplexml_load_file($data['custom_url'] . '?xml=1');
            $data['custom_message'] = $xmlUserProfile->stateMessage;
            $data['status']         = $xmlUserProfile->onlineState;
            $data['location']       = $xmlUserProfile->location;
            $data['vac']            = $xmlUserProfile->vacBanned;
            $data['trade']          = $xmlUserProfile->tradeBanState;
            $data['limited']        = $xmlUserProfile->isLimitedAccount;

            // Format played time.
            if (is_object($data['hoursPlayed'])) {
                $data['hoursPlayed'] = (string) $data['hoursPlayed'];
                $data['hoursPlayed'] = str_replace(',', '', $data['hoursPlayed']);
            }

            if (is_object($data['hoursPlayedLast2Weeks'])) {
                $data['hoursPlayedLast2Weeks'] = (string) $data['hoursPlayedLast2Weeks'];
                $data['hoursPlayedLast2Weeks'] = str_replace(',', '', $data['hoursPlayedLast2Weeks']);
            }

            return $data;
        }
    }

    return [];
}

/**
 * Get game details for specific user.
 *
 * @param $steamId
 *
 * @return array
 */
function getGameData($steamId)
{
    global $steamauth;

    $game = "http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=" . $steamauth['apikey'] . "&steamid=" . $steamId;

    //  Initiate curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $game);
    $result = curl_exec($ch);
    curl_close($ch);
    $game = json_decode($result, true);

    // Set default values for needed variables.
    $data = [
        'total_kills'         => 0,
        'total_deaths'        => 0,
        'last_match_weapon'   => 0,
        'total_rounds_played' => 0,
        'total_wins'          => 0
    ];

    foreach ($game['playerstats']['stats'] as $key => $row) {
        $data[$row['name']] = $row['value'];
    }

    return $data;
}

/**
 * @param $variable
 * @param $default
 *
 * @return mixed
 */
function doDefault($variable, $default)
{
    if (isset($variable)) {
        return $variable;
    }

    return $default;
}

/**
 * Get VAC bans for specific user.
 *
 * @param $steamId
 *
 * @return array
 */
function getVacBans($steamId)
{
    global $steamauth;

    $vac = "http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=" . $steamauth['apikey'] . "&steamids=" . $steamId;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $vac);
    $result_vac = curl_exec($ch);
    curl_close($ch);
    $vacs = json_decode($result_vac, true);

    $data = [
        'number_of_vacs'      => 0,
        'days_since_last_ban' => 0
    ];

    $VACBanned = $vacs['players'][0]['VACBanned'];
    if ($VACBanned == true) {
        $data = [
            'number_of_vacs'      => $vacs['players'][0]['NumberOfVACBans'],
            'days_since_last_ban' => $vacs['players'][0]['DaysSinceLastBan']
        ];
    }

    return $data;
}

/**
 * Return weapon name by given weapon id.
 *
 * @param $weaponId
 *
 * @return string
 */
function getWeaponNameById($weaponId)
{
    $weapons = [
        1  => 'Deagle',
        2  => 'Dual Ber.',
        3  => 'Five-Seven',
        4  => 'Glock-18',
        7  => 'AK-47',
        8  => 'AUG',
        9  => 'AWP',
        10 => 'Famas',
        11 => 'G3SG1',
        13 => 'Galil AR',
        14 => 'M249',
        16 => 'M4A4/M4A1-S',
        17 => 'MAC-10',
        19 => 'P90',
        24 => 'UMP-45',
        25 => 'XM1014',
        26 => 'PP-Bizon',
        27 => 'MAG-7',
        28 => 'Negev',
        29 => 'Sawed-Off',
        30 => 'Tec-9',
        31 => 'Zeus-x27',
        32 => 'P2000/USP-S',
        33 => 'MP7',
        34 => 'MP9',
        35 => 'Nova',
        36 => 'P250',
        38 => 'Scar-20',
        39 => 'SG553',
        40 => 'SSG08',
        42 => 'Knife',
        43 => 'Flashbang',
        44 => 'HE Grenade',
        45 => 'Smoke Grenade',
        46 => 'Molotov',
        47 => 'Decoy',
        48 => 'Incendiary',
        49 => 'C4',
        59 => 'Knife',
        60 => 'M4A4/M4A1-S',
        61 => 'P2000/USP-S',
        63 => 'CZ75'
    ];

    $weaponId = (int) $weaponId;
    if (array_key_exists($weaponId, $weapons)) {
        return $weapons[$weaponId];
    }

    return 'Knife';
}

// Header
function head()
{
include "settings.php";
?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $site['title']; ?></title>
    <base href="<?= $steamauth['domainname']; ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="<?= $site['owner']; ?>"/>
    <meta name="keywords" content="<?= $site['keywords']; ?>"/>
    <meta name="author" content="<?= $site['owner']; ?>"/>
    <meta name="web_author" content="<?= $site['owner']; ?>">
    <meta name="copyright" content="<?= $site['owner']; ?>"/>
    <meta name="owner" content="<?= $site['owner']; ?>">
    <meta name="robots" content="index, follow, all"/>
    <meta name="rating" content="general"/>
    <meta name="revisit-after" content="1 days"/>
    <meta name="revision" content="1 days">
    <meta name="refresh" content="900"/>
    <meta name="coverage" content="worldwide">
    <meta name="googlebot" content="index, follow, archive"/>
    <link rel="icon" href="<?= $site['favicon']; ?>" type="image/png">
    <link rel="shortcut icon" href="<?= $site['favicon']; ?>" type="image/png"/>
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <script src="js/excanvas.js"></script>
    <![endif]-->
    <!--[if lt IE 7]>
    <script src="js/IE7.js"></script>
    <![endif]-->
    <!--[if lt IE 8]>
    <script src="js/IE8.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="js/IE9.js"></script>
    <![endif]-->
</head>

<body>
<img src="images/image_navbar.jpg" class="img-responsive"/>
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse text-center" id="navbar-main">
            <ul class="nav navbar-nav navbar-left">
                <li <?= (basename($_SERVER['SCRIPT_NAME']) == 'index.html') ? 'class="active"' : ''; ?>>
                    <a href="<?= $steamauth['domainname']; ?>">
                        <h5>
                            <i class='fa fa-home'></i>&nbsp;<strong>Home</strong>
                        </h5>
                    </a>
                </li>
                <li <?= (basename($_SERVER['SCRIPT_NAME']) == 'news.html') ? 'class="active"' : ''; ?>>
                    <a href="<?= $steamauth['domainname'] . "news"; ?>">
                        <h5>
                            <i class='fa fa-newspaper-o'></i>&nbsp;<strong>News</strong>
                        </h5>
                    </a>
                </li>
                <li>
                    <a data-toggle="modal" data-target="#faq">
                        <h5>
                            <i class='fa fa-info-circle'></i>&nbsp;<strong>FAQ</strong>
                        </h5>
                    </a>
                </li>
                <li>
                    <a data-toggle="modal" data-target="#contact">
                        <h5>
                            <i class='fa fa-envelope'></i>&nbsp;<strong>Contact</strong>
                        </h5>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (!empty($site['facebook'])) {
                    echo '<li><a href="' . $site['facebook'] . '" style="text-decoration:none;"><h5><i class="fa fa-facebook-official"></i> Facebook</h5></a></li>';
                }

                if (!empty($site['twitter'])) {
                    echo '<li><a href="' . $site['twitter'] . '" style="text-decoration:none;"><h5><i class="fa fa-twitter"></i> Twitter</h5></a></li>';
                }

                if (!empty($site['vk'])) {
                    echo '<li><a href="' . $site['vk'] . '" style="text-decoration:none;"><h5><i class="fa fa-vk"></i> VK</h5></a></li>';
                }

                if (!isset($_SESSION['steamid'])) {
                    echo '<li><br /> ' . steamlogin() . '</li>';
                } else {
                    include "userInfo.php";
                    echo '
							<li class="animated dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               <h5 style="color:#fff;">Hi, <b>' . $steamprofile['personaname'] . '</b> !&nbsp;&nbsp;<img src="' . $steamprofile['avatarfull'] . '" width="20px" height="20px" /><b class="caret"></b></h5>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="player/' . $_SESSION['steamid'] . '"><i class="fa fa-user"></i> My Profile</a></li>
								<li><a href="mygames"><i class="fa fa-gamepad"></i> Games List</a></li>
								<li class="divider"></li>
								<li><a href="logout"><i class="fa fa-power-off"></i> Log Out</a></li>
                            </ul>
                        </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <?php
    }

    // Footer
    function footer()
    {
    include "settings.php";
    ?>
    <!-- Modal -->
    <div id="faq" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class='fa fa-question-circle'></i> Frequently Asked Question</h4>
                </div>
                <div class="modal-body">
                    <p style="color:green">Q: How can i see my Games List ?</p>
                    <p>A: Simple ! All you have to do is to login with Steam. !:)</p>
                    <p style="color:green">Q: Why are there no stats for Mirage, Cache, Overpass or Operation maps?</p>
                    <p>A: Stats for Mirage and other newer maps are not there because Valve ceased updating Web API
                        (where the site gets the stats) after releasing Militia.</p>
                    <p style="color:green">Q: Where are the stats for CZ-75, USP-S and M4A1-S? </p>
                    <p>A: Stats are actually counted for weapon slots, not individual models. So stats for USP-S/P2000
                        are counted together. Same goes for M4A1-S/M4A4, and CZ-75/Five-SeveN or Tec-9.</p>
                    <p style="color:green">Q: How often are the stats updated?</p>
                    <p>A: Steam updates stats at the end of every round. After receiving stats from Steam, CS:GO Stats
                        caches them for 5 minutes. Stats in image are cached instantly.</p>
                    <p style="color:green">Q: I can't view my profile even if SteamID is correct and I have CS:GO.
                        Why? </p>
                    <p>A: On April 11 Steam changed their privacy settings making every user's profile private by
                        default. To view your stats you must set your game data to public by going to your
                        <b>Steam Profile --> Edit Profile --> Privacy Settings --> Set: "My profile: Public" and "Game
                            details: public"</b>
                        Please note: Steam can take a while to process your privacy changes, so it might not show stats
                        for hours</p>
                    <p style="color:green">Q: I want to report a bug !</p>
                    <p>A: Message me on <a href="<?= $site['mysteamprofile']; ?>"
                                           style="text-decoration:none;">Steam</a>,
                        or <a href="mailto:<?= $site['email']; ?>" style="text-decoration:none;">email</a> me.
                    </p>
                    <p>
                        <small>Source: csgo-stats.com</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">&times; Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="contact" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class='fa fa-envelope'></i> Contact</h4>
                </div>
                <div class="modal-body">
                    <form role="form" action="index.php" method="POST">
                        <p>
                            <label>
                                <i class='fa fa-user'></i> <strong><span style="color:red">*</span>Your
                                    Name</strong></label>
                            <input type="text" name="name" class="form-control" placeholder="ex: John Doe" required/>
                        </p>
                        <p>
                            <label><i class='fa fa-envelope'></i> <strong><span style="color:red">*</span>Your
                                    E-mail</strong></label>
                            <input type="text" name="email" class="form-control" placeholder="ex: email@yahoo.com"
                                   required/>
                        </p>
                        <p>
                            <label><i class='fa fa-pencil'></i> <strong><span style="color:red">*</span>Your
                                    Message</strong></label>
                            <textarea class="form-control" name="message"
                                      placeholder="Tell me how can i help you !:)"></textarea>
                        </p>
                        <br/>
                        <button type="submit" class="btn btn-success btn-sm" name="send"><i
                                    class='fa fa-paper-plane'></i> Send
                        </button>
                        <button type="reset" class="btn btn-danger btn-sm"><i class='fa fa-history'></i> Reset</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">&times; Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h5><i class='fa fa-info-circle'></i> Powered by <u>Steam</u>. This site is not affiliated with Valve.</h5>
    </div>
</div>
<!-- JS Bootstrap-->
<script src="js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>
<?php
}
?>
