<?php
include "core.php";
head();

// Get SteamID 64.
$steamId64 = !isset($_GET['id']) ? 0 : $_GET['id'];

if (!ctype_digit($steamId64) || strlen($steamId64) !== 17) {
    echo '<meta http-equiv="refresh" content="0;url=' . $steamauth['domainname'] . 'index.php">';
    exit;
}

// Set default variables.
$user = getAccountBySteamId($steamId64);
$game = [];
$vacs = [];

$lastMatchKDR = 0;
$kdr          = 0;

if ($user) {
    if ($user['privacy'] === 3 && $user['hasCSGO'] === true) {
        $game = getGameData($user['steam_id']);
        $vacs = getVacBans($user['steam_id']);
    }
}

// Set last match favorite weapon.
$lastMatchFavoriteWeapon = array_key_exists('last_match_favweapon_id', $game) ? getWeaponNameById($game['last_match_favweapon_id']) : 'Knife';

// VAC
$vacStatus     = array_key_exists('vac', $user) && (int) $user['vac'] === 0 ? '<i class="fa fa-check" style="color:green;"></i> <strong>In good standing</strong>' : '<i class="fa fa-ban" style="color:red;"></i> <strong>Banned</strong>';
$tradeStatus   = array_key_exists('trade', $user) && $user['trade'] == 'None' ? '<i class="fa fa-check" style="color:green;"></i> <strong>In good standing</strong>' : '<i class="fa fa-ban" style="color:red;"></i> <strong>Banned</strong>';
$limitedStatus = array_key_exists('limited', $user) && (int) $user['limited'] === 0 ? '<i class="fa fa-check" style="color:green;"></i> <strong>In good standing</strong>' : '<i class="fa fa-ban" style="color:red;"></i> <strong>Banned</strong>';

if ($game) {
    // KD ratio
    $lastMatchKDR = @round($game['last_match_kills'] / $game['last_match_deaths'], 2);
    $kdr          = @round($game['total_kills'] / $game['total_deaths'], 2);
}

//SteamID32
$z = (($steamId64 - 76561197960265728) / 2);
$z = substr($z, 0, 8);
$y = $steamId64 % 2;
$x = 1;
if (array_key_exists('privacy', $user)) {
    $x = $user['privacy'] === 3 ? 0 : 1;
}

// Weapons
$weapons = [
    ['name' => "knife", 'kills' => @$game['total_kills_knife'], 'hits' => 0, 'shots' => 0, 'accuracy' => 0],
    ['name' => "hegrenade", 'kills' => @$game['total_kills_hegrenade'], 'hits' => 0, 'shots' => 0, 'accuracy' => 0],
    ['name' => "glock-18", 'kills' => @$game['total_kills_glock'], 'hits' => @$game['total_hits_glock'], 'shots' => @$game['total_shots_glock'], 'accuracy' => @($game['total_hits_glock'] / $game['total_shots_glock'])],
    ['name' => "deagle", 'kills' => @$game['total_kills_deagle'], 'hits' => @$game['total_hits_deagle'], 'shots' => @$game['total_shots_deagle'], 'accuracy' => @($game['total_hits_deagle'] / $game['total_shots_deagle'])],
    ['name' => "five-seven", 'kills' => @$game['total_kills_fiveseven'], 'hits' => @$game['total_hits_fiveseven'], 'shots' => @$game['total_shots_fiveseven'], 'accuracy' => @($game['total_hits_fiveseven'] / $game['total_shots_fiveseven'])],
    ['name' => "xm1014", 'kills' => @$game['total_kills_xm1014'], 'hits' => @$game['total_hits_xm1014'], 'shots' => @$game['total_shots_xm1014'], 'accuracy' => @($game['total_hits_xm1014'] / $game['total_shots_xm1014'])],
    ['name' => "mac-10", 'kills' => @$game['total_kills_mac10'], 'hits' => @$game['total_hits_mac10'], 'shots' => @$game['total_shots_mac10'], 'accuracy' => @($game['total_hits_mac10'] / $game['total_shots_mac10'])],
    ['name' => "ump-45", 'kills' => @$game['total_kills_ump45'], 'hits' => @$game['total_hits_ump45'], 'shots' => @$game['total_shots_ump45'], 'accuracy' => @($game['total_hits_ump45'] / $game['total_shots_ump45'])],
    ['name' => "p90", 'kills' => @$game['total_kills_p90'], 'hits' => @$game['total_hits_p90'], 'shots' => @$game['total_shots_p90'], 'accuracy' => @($game['total_hits_p90'] / $game['total_shots_p90'])],
    ['name' => "awp", 'kills' => @$game['total_kills_awp'], 'hits' => @$game['total_hits_awp'], 'shots' => @$game['total_shots_awp'], 'accuracy' => @($game['total_hits_awp'] / $game['total_shots_awp'])],
    ['name' => "ak-47", 'kills' => @$game['total_kills_ak47'], 'hits' => @$game['total_hits_ak47'], 'shots' => @$game['total_shots_ak47'], 'accuracy' => @($game['total_hits_ak47'] / $game['total_shots_ak47'])],
    ['name' => "aug", 'kills' => @$game['total_kills_aug'], 'hits' => @$game['total_hits_aug'], 'shots' => @$game['total_shots_aug'], 'accuracy' => @($game['total_hits_aug'] / $game['total_shots_aug'])],
    ['name' => "famas", 'kills' => @$game['total_kills_famas'], 'hits' => @$game['total_hits_famas'], 'shots' => @$game['total_shots_famas'], 'accuracy' => @($game['total_hits_famas'] / $game['total_shots_famas'])],
    ['name' => "g3sg1", 'kills' => @$game['total_kills_g3sg1'], 'hits' => @$game['total_hits_g3sg1'], 'shots' => @$game['total_shots_g3sg1'], 'accuracy' => @($game['total_hits_g3sg1'] / $game['total_shots_g3sg1'])],
    ['name' => "m249", 'kills' => @$game['total_kills_m249'], 'hits' => @$game['total_hits_m249'], 'shots' => @$game['total_shots_m249'], 'accuracy' => @($game['total_hits_m249'] / $game['total_shots_m249'])],
    ['name' => "usp-s & p2000", 'kills' => @$game['total_kills_hkp2000'], 'hits' => @$game['total_hits_hkp2000'], 'shots' => @$game['total_shots_hkp2000'], 'accuracy' => @($game['total_hits_hkp2000'] / $game['total_shots_hkp2000'])],
    ['name' => "p250", 'kills' => @$game['total_kills_p250'], 'hits' => @$game['total_hits_p250'], 'shots' => @$game['total_shots_p250'], 'accuracy' => @($game['total_hits_p250'] / $game['total_shots_p250'])],
    ['name' => "dual berettas", 'kills' => @$game['total_kills_elite'], 'hits' => @$game['total_hits_elite'], 'shots' => @$game['total_shots_elite'], 'accuracy' => @($game['total_hits_elite'] / $game['total_shots_elite'])],
    ['name' => "tec-9", 'kills' => @$game['total_kills_tec9'], 'hits' => @$game['total_hits_tec9'], 'shots' => @$game['total_shots_tec9'], 'accuracy' => @($game['total_hits_tec9'] / $game['total_shots_tec9'])],
    ['name' => "taser", 'kills' => @$game['total_kills_taser'], 'hits' => @$game['total_hits_taser'], 'shots' => @$game['total_shots_taser'], 'accuracy' => @($game['total_hits_taser'] / $game['total_shots_taser'])],
    ['name' => "mp7", 'kills' => @$game['total_kills_mp7'], 'hits' => @$game['total_hits_mp7'], 'shots' => @$game['total_shots_mp7'], 'accuracy' => @($game['total_hits_mp7'] / $game['total_shots_mp7'])],
    ['name' => "mp9", 'kills' => @$game['total_kills_mp9'], 'hits' => @$game['total_hits_mp9'], 'shots' => @$game['total_shots_mp9'], 'accuracy' => @($game['total_hits_mp9'] / $game['total_shots_mag7'])],
    ['name' => "mag-7", 'kills' => @$game['total_kills_mag7'], 'hits' => @$game['total_hits_mag7'], 'shots' => @$game['total_shots_mag7'], 'accuracy' => @($game['total_hits_mag7'] / $game['total_shots_glock'])],
    ['name' => "nova", 'kills' => @$game['total_kills_nova'], 'hits' => @$game['total_hits_nova'], 'shots' => @$game['total_shots_nova'], 'accuracy' => @($game['total_hits_nova'] / $game['total_shots_nova'])],
    ['name' => "sawedoff", 'kills' => @$game['total_kills_sawedoff'], 'hits' => @$game['total_hits_sawedoff'], 'shots' => @$game['total_shots_sawedoff'], 'accuracy' => @($game['total_hits_sawedoff'] / $game['total_shots_sawedoff'])],
    ['name' => "bizon", 'kills' => @$game['total_kills_bizon'], 'hits' => @$game['total_hits_bizon'], 'shots' => @$game['total_shots_bizon'], 'accuracy' => @($game['total_hits_bizon'] / $game['total_shots_bizon'])],
    ['name' => "negev", 'kills' => @$game['total_kills_negev'], 'hits' => @$game['total_hits_negev'], 'shots' => @$game['total_shots_negev'], 'accuracy' => @($game['total_hits_negev'] / $game['total_shots_negev'])],
    ['name' => "galil ar", 'kills' => @$game['total_kills_galilar'], 'hits' => @$game['total_hits_galilar'], 'shots' => @$game['total_shots_galilar'], 'accuracy' => @($game['total_hits_galilar'] / $game['total_shots_galilar'])],
    ['name' => "m4a1 & m4a4", 'kills' => @$game['total_kills_m4a1'], 'hits' => @$game['total_hits_m4a1'], 'shots' => @$game['total_shots_m4a1'], 'accuracy' => @($game['total_hits_m4a1'] / $game['total_shots_m4a1'])],
    ['name' => "ssg08", 'kills' => @$game['total_kills_ssg08'], 'hits' => @$game['total_hits_ssg08'], 'shots' => @$game['total_shots_ssg08'], 'accuracy' => @($game['total_hits_ssg08'] / $game['total_shots_ssg08'])],
    ['name' => "sg556", 'kills' => @$game['total_kills_sg556'], 'hits' => @$game['total_hits_sg556'], 'shots' => @$game['total_shots_sg556'], 'accuracy' => @($game['total_hits_sg556'] / $game['total_shots_sg556'])],
    ['name' => "scar-20", 'kills' => @$game['total_kills_scar20'], 'hits' => @$game['total_hits_scar20'], 'shots' => @$game['total_shots_scar20'], 'accuracy' => @($game['total_hits_scar20'] / $game['total_shots_scar20'])],
    ['name' => "molotov", 'kills' => @$game['total_kills_molotov'], 'hits' => 0, 'shots' => 0, 'accuracy' => 0],
    ['name' => "decoy", 'kills' => @$game['total_kills_decoy'], 'hits' => 0, 'shots' => 0, 'accuracy' => 0]
];

// Maps
$maps = [
    ['name' => "de_dust", 'rounds' => @$game['total_rounds_map_de_dust'], 'wins' => @$game['total_wins_map_de_dust'], 'winratio' => @($game['total_wins_map_de_dust'] / $game['total_rounds_map_de_dust'])],
    ['name' => "de_dust2", 'rounds' => @$game['total_rounds_map_de_dust2'], 'wins' => @$game['total_wins_map_de_dust2'], 'winratio' => @($game['total_wins_map_de_dust2'] / $game['total_rounds_map_de_dust2'])],
    ['name' => "de_inferno", 'rounds' => @$game['total_rounds_map_de_inferno'], 'wins' => @$game['total_wins_map_de_inferno'], 'winratio' => @($game['total_wins_map_de_inferno'] / $game['total_rounds_map_de_inferno'])],
    ['name' => "de_nuke", 'rounds' => @$game['total_rounds_map_de_nuke'], 'wins' => @$game['total_wins_map_de_nuke'], 'winratio' => @($game['total_wins_map_de_nuke'] / $game['total_rounds_map_de_nuke'])],
    ['name' => "de_train", 'rounds' => @$game['total_rounds_map_de_train'], 'wins' => @$game['total_wins_map_de_train'], 'winratio' => @($game['total_wins_map_de_train'] / $game['total_rounds_map_de_train'])],
    ['name' => "de_sugarcane", 'rounds' => @$game['total_rounds_map_de_sugarcane'], 'wins' => @$game['total_wins_map_de_sugarcane'], 'winratio' => @($game['total_wins_map_de_sugarcane'] / $game['total_rounds_map_de_sugarcane'])],
    ['name' => "ar_baggage", 'rounds' => @$game['total_rounds_map_ar_baggage'], 'wins' => @$game['total_wins_map_ar_baggage'], 'winratio' => @($game['total_wins_map_ar_baggage'] / $game['total_rounds_map_ar_baggage'])],
    ['name' => "cs_italy", 'rounds' => @$game['total_rounds_map_cs_italy'], 'wins' => @$game['total_wins_map_cs_italy'], 'winratio' => @($game['total_wins_map_cs_italy'] / $game['total_rounds_map_cs_italy'])],
    ['name' => "de_bank", 'rounds' => @$game['total_rounds_map_de_bank'], 'wins' => @$game['total_wins_map_de_bank'], 'winratio' => @($game['total_wins_map_de_bank'] / $game['total_rounds_map_de_bank'])],
    ['name' => "cs_assault", 'rounds' => @$game['total_rounds_map_cs_assault'], 'wins' => @$game['total_wins_map_cs_assault'], 'winratio' => @($game['total_wins_map_cs_assault'] / $game['total_rounds_map_cs_assault'])],
    ['name' => "de_lake", 'rounds' => @$game['total_rounds_map_de_lake'], 'wins' => @$game['total_wins_map_de_lake'], 'winratio' => @($game['total_wins_map_de_lake'] / $game['total_rounds_map_de_lake'])],
    ['name' => "cs_office", 'rounds' => @$game['total_rounds_map_cs_office'], 'wins' => @$game['total_wins_map_cs_office'], 'winratio' => @($game['total_wins_map_cs_office'] / $game['total_rounds_map_cs_office'])],
    ['name' => "ar_monastery", 'rounds' => @$game['total_rounds_map_ar_monastery'], 'wins' => @$game['total_wins_map_ar_monastery'], 'winratio' => @($game['total_wins_map_ar_monastery'] / $game['total_rounds_map_ar_monastery'])],
    ['name' => "de_aztec", 'rounds' => @$game['total_rounds_map_de_aztec'], 'wins' => @$game['total_wins_map_de_aztec'], 'winratio' => @($game['total_wins_map_de_aztec'] / $game['total_rounds_map_de_aztec'])],
    ['name' => "de_vertigo", 'rounds' => @$game['total_rounds_map_de_vertigo'], 'wins' => @$game['total_wins_map_de_vertigo'], 'winratio' => @($game['total_wins_map_de_vertigo'] / $game['total_rounds_map_de_vertigo'])],
    ['name' => "de_safehouse", 'rounds' => @$game['total_rounds_map_de_safehouse'], 'wins' => @$game['total_wins_map_de_safehouse'], 'winratio' => @($game['total_wins_map_de_safehouse'] / $game['total_rounds_map_de_safehouse'])],
    ['name' => "de_stmarc", 'rounds' => @$game['total_rounds_map_de_stmarc'], 'wins' => @$game['total_wins_map_de_stmarc'], 'winratio' => @($game['total_wins_map_de_stmarc'] / $game['total_rounds_map_de_stmarc'])],
    ['name' => "ar_shoots", 'rounds' => @$game['total_rounds_map_ar_shoots'], 'wins' => @$game['total_wins_map_ar_shoots'], 'winratio' => @($game['total_wins_map_ar_shoots'] / $game['total_rounds_map_ar_shoots'])],
    ['name' => "de_cbble", 'rounds' => @$game['total_rounds_map_de_cbble'], 'wins' => @$game['total_wins_map_de_cbble'], 'winratio' => @($game['total_wins_map_de_cbble'] / $game['total_rounds_map_de_cbble'])],
    ['name' => "de_shorttrain", 'rounds' => @$game['total_rounds_map_de_shorttrain'], 'wins' => @$game['total_wins_map_de_shorttrain'], 'winratio' => @($game['total_wins_map_de_shorttrain'] / $game['total_rounds_map_de_shorttrain'])]
];

if (!empty($user) && !empty($game)) {
    ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-4 text-center" align="center">
                <img src="<?= $user['avatar']; ?>"
                     data-toggle="tooltip"
                     title="<?= $user['username']; ?>'s avatar"
                     width="130px"
                     height="130px"
                     class="img-responsive center-block"
                     align="center"/>
                <br/>
                <i class='fa fa-steam'></i>&nbsp;<a href="<?= $user['custom_url']; ?>" style="text-decoration:none;">Profile</a>
            </div>
            <div class="col-md-8">
                <table class="table table-responsive table-hover table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td><i class='fa fa-user'></i> <strong>Name:</strong></td>
                        <td><?= $user['username']; ?></td>
                        <td><i class='fa fa-info-circle'></i> <strong>VAC:</strong></td>
                        <td><?= $vacStatus; ?></td>
                    </tr>
                    <tr>
                        <td><i class='fa fa-steam'></i> <strong>SteamID64:</strong></td>
                        <td><?= $steamId64; ?></td>
                        <td><i class='fa fa-info-circle'></i> <strong>Trade:</strong></td>
                        <td><?= $tradeStatus; ?></td>
                    </tr>
                    <tr>
                        <td><i class='fa fa-steam'></i> <strong>SteamID32:</strong></td>
                        <td><?= "STEAM_" . $x . ":" . $y . ":" . $z; ?></td>
                        <td><i class='fa fa-info-circle'></i> <strong>Account:</strong></td>
                        <td><?= $limitedStatus; ?></td>
                    </tr>
                    <tr>
                        <td><i class='fa fa-history'></i> <strong>Profile created on:</strong></td>
                        <td><?= date("d M Y H:i", $user['account_created']); ?></td>
                        <td><i class='fa fa-flag'></i> <strong>Country:</strong></td>
                        <td>
                            <?php if (null !== $user['location_country']): ?>
                                <img src="images/flags/<?= $user['location_country']; ?>.png"/>&nbsp;<?= replace_country($user['location_country']); ?>
                            <?php else: ?>
                                - - -
                            <?php endif; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4" style="margin-top:5px;">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $steamauth['domainname'] . 'player/' . $steamId64; ?>"
                   class="btn btn-block btn-primary" target="_blank">Share on <i class='fa fa-facebook-official'></i> Facebook</a>
            </div>
            <div class="col-md-4" style="margin-top:5px;">
                <a href="https://twitter.com/intent/tweet?text=Check+your+CSGO+stats&url=<?= $steamauth['domainname'] . 'player/' . $steamId64; ?>&hashtags=CSGOSTATSPRO,EVOSCRIPTS,CODECANYON"
                   class="btn btn-block btn-info" target="_blank">Share on <i class='fa fa-twitter'></i> Twitter</a>
            </div>
            <div class="col-md-4" style="margin-top:5px;">
                <a href="https://www.vk.com/share.php?url=<?= $steamauth['domainname'] . 'player/' . $steamId64; ?>"
                   class="btn btn-block btn-primary" target="_blank">Share on <i class='fa fa-vk'></i> VKontakte</a>
            </div>
        </div>
    </div>
    <br/>
    <div class="col-md-12 text-center">
        <?php if (!empty($vacs) && $vacs['number_of_vacs'] > 0): ?>
            <div class="alert alert-danger">
                <h4><i class="fa fa-warning"></i> This user is <b>VAC Banned</b>!</h4>
                Number of VACs: <b><?= $vacs['number_of_vacs']; ?></b> | Days since last VAC Ban:
                <b><?= number_format($vacs['days_since_last_ban']); ?></b> days.
            </div>
        <?php endif; ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a data-toggle="tab" href="#general"><i
                                class='fa fa-area-chart'></i> <strong>General</strong></a></li>
                <li role="presentation"><a data-toggle="tab" href="#weapons"><i class='fa fa-bullseye'></i>
                        <strong>Weapons</strong></a>
                </li>
                <li role="presentation"><a data-toggle="tab" href="#maps"><i class='fa fa-map-marker'></i>
                        <strong>Maps</strong></a></li>
                <li role="presentation"><a data-toggle="tab" href="#achievements"><i class='fa fa-trophy'></i>
                        <strong>Achievements</strong></a>
                </li>
                <li role="presentation"><a data-toggle="tab" href="#signature"><i class='fa fa-image'></i> <strong>Signatures</strong></a>
                </li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div id="general" class="tab-pane fade in active">
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Last Match:</strong></td>
                                    <td><?= @doDefault($game['last_match_wins'], 0); ?> rounds WON</td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Kills:</strong></td>
                                    <td><?= @doDefault($game['last_match_kills'], 0); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Death:</strong></td>
                                    <td><?= @doDefault($game['last_match_deaths'], 0); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>K/D Ratio:</strong></td>
                                    <td><?= @$lastMatchKDR; ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Fav. Weapon:</strong></td>
                                    <td><?= $lastMatchFavoriteWeapon; ?>
                                        (<?= @doDefault($game['last_match_favweapon_kills'], 0); ?> kills)
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Damage:</strong></td>
                                    <td><?= @doDefault($game['last_match_damage'], 0); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Money Spent:</strong></td>
                                    <td><?= @doDefault($game['last_match_money_spent'], 0); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>MVPs:</strong></td>
                                    <td><?= @doDefault($game['last_match_mvps'], 0); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Revenges:</strong></td>
                                    <td><?= @doDefault($game['last_match_revenges'], 0); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Dominations:</strong></td>
                                    <td><?= @number_format(doDefault($game['last_match_dominations'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-chevron-circle-right'></i> <strong>Score:</strong></td>
                                    <td><?= @number_format(doDefault($game['last_match_contribution_score'], 0)); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Played Time:</strong></td>
                                    <td><?= @number_format($user['hoursPlayed']); ?> hrs</td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Played Last 2 Weeks:</strong>
                                    </td>
                                    <td><?= doDefault($user['hoursPlayedLast2Weeks'], 0); ?> hrs</td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Kills:</strong></td>
                                    <td>
                                        <span class="label label-success"><?= @number_format(doDefault($game['total_kills'], 0)); ?></span>
                                    </td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Deaths:</strong></td>
                                    <td>
                                        <span class="label label-danger"><?= @number_format(doDefault($game['total_deaths'], 0)); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>K/D Ratio:</strong></td>
                                    <td><?= doDefault($kdr, 0); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total MVPs:</strong></td>
                                    <td><?= @number_format(doDefault($game['total_mvps'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Planted Bombs:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_planted_bombs'], 0)); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Defused Bombs:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_defused_bombs'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Damage Done:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_damage_done'], 0)); ?> HP</td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Money Earned:</strong>
                                    </td>
                                    <td>$<?= @number_format(doDefault($game['total_money_earned'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Rescued
                                            Hostages:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_rescued_hostages'], 0)); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Broken Windows:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_broken_windows'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Dominations:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_dominations'], 0)); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Revenges:</strong></td>
                                    <td><?= @number_format(doDefault($game['total_revenges'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Shots Fired:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_shots_fired'], 0)); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Headshot Kills:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_kills_headshot'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Score:</strong></td>
                                    <td><?= @number_format(doDefault($game['total_contribution_score'], 0)); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Rounds Played:</strong>
                                    </td>
                                    <td><?= @number_format(doDefault($game['total_rounds_played'], 0)); ?></td>
                                </tr>
                                <tr>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Total Rounds Won:</strong></td>
                                    <td><?= @number_format(doDefault($game['total_wins'], 0)); ?></td>
                                    <td><i class='fa fa-angle-double-right'></i> <strong>Win Ratio:</strong></td>
                                    <td><?= @round((($game['total_wins'] / $game['total_rounds_played']) * 100), 2); ?>%
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="weapons">
                    <ul class="nav nav-pills">
                        <li role="presentation" class="active">
                            <a data-toggle="pill" href="#sortWeaponByKills">Kills</a>
                        </li>
                        <li role="presentation"><a data-toggle="pill" href="#sortWeaponByShots">Shots</a></li>
                        <li role="presentation"><a data-toggle="pill" href="#sortWeaponByHits">Hits</a></li>
                        <li role="presentation"><a data-toggle="pill" href="#sortWeaponByAccuracy">Accuracy %</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <br/>
                        <div id="sortWeaponByKills" class="tab-pane fade in active">
                            <div class="row">
                                <?php
                                foreach ($weapons as $key => $wep) {
                                    $name[$key]     = $wep['name'];
                                    $kills[$key]    = $wep['kills'];
                                    $hits[$key]     = $wep['hits'];
                                    $shots[$key]    = $wep['shots'];
                                    $accuracy[$key] = $wep['accuracy'];
                                }

                                array_multisort($kills, SORT_NUMERIC, SORT_DESC, $weapons);
                                $i = 0;
                                foreach ($weapons as $wep): ?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($wep['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/weapons/<?= $wep['name']; ?>.png"
                                                                 class="img-responsive center-block"
                                                                 height="180px"
                                                                 width="239px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-child"></i> <strong>Kills</strong></td>
                                                        <td><?= @number_format(round($wep['kills'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-minus"></i> <strong>Hits</strong></td>
                                                        <td><?= @number_format(round($wep['hits'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-sun-o"></i> <strong>Shots</strong></td>
                                                        <td><?= @number_format(round($wep['shots'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Accuracy %</strong>
                                                        </td>
                                                        <td>
                                                        <span class="label label-success"><?= @round(($wep['accuracy'] * 100), 1); ?>
                                                            %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                        <div id="sortWeaponByShots" class="tab-pane fade">
                            <div class="row">
                                <?php
                                foreach ($weapons as $key => $wep) {
                                    $name[$key]     = $wep['name'];
                                    $kills[$key]    = $wep['kills'];
                                    $hits[$key]     = $wep['hits'];
                                    $shots[$key]    = $wep['shots'];
                                    $accuracy[$key] = $wep['accuracy'];
                                }

                                array_multisort($shots, SORT_NUMERIC, SORT_DESC, $weapons);
                                $i = 0;
                                foreach ($weapons as $wep): ?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($wep['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/weapons/<?= $wep['name']; ?>.png"
                                                                 class="img-responsive center-block"
                                                                 height="180px"
                                                                 width="239px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-child"></i> <strong>Kills</strong></td>
                                                        <td><?= @number_format(round($wep['kills'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-minus"></i> <strong>Hits</strong></td>
                                                        <td><?= @number_format(round($wep['hits'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-sun-o"></i> <strong>Shots</strong></td>
                                                        <td><?= @number_format(round($wep['shots'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Accuracy %</strong>
                                                        </td>
                                                        <td>
                                                        <span class="label label-success"><?= @round(($wep['accuracy'] * 100), 1); ?>
                                                            %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                        <div id="sortWeaponByHits" class="tab-pane fade">
                            <div class="row">
                                <?php
                                foreach ($weapons as $key => $wep) {
                                    $name[$key]     = $wep['name'];
                                    $kills[$key]    = $wep['kills'];
                                    $hits[$key]     = $wep['hits'];
                                    $shots[$key]    = $wep['shots'];
                                    $accuracy[$key] = $wep['accuracy'];
                                }

                                array_multisort($hits, SORT_NUMERIC, SORT_DESC, $weapons);
                                $i = 0;
                                foreach ($weapons as $wep): ?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($wep['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/weapons/<?= $wep['name']; ?>.png"
                                                                 class="img-responsive center-block"
                                                                 height="180px"
                                                                 width="239px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-child"></i> <strong>Kills</strong></td>
                                                        <td><?= @number_format(round($wep['kills'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-minus"></i> <strong>Hits</strong></td>
                                                        <td><?= @number_format(round($wep['hits'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-sun-o"></i> <strong>Shots</strong></td>
                                                        <td><?= @number_format(round($wep['shots'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Accuracy %</strong>
                                                        </td>
                                                        <td>
                                                            <span class="label label-success"><?= @round(($wep['accuracy'] * 100), 1); ?>
                                                                %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                        <div id="sortWeaponByAccuracy" class="tab-pane fade">
                            <div class="row">
                                <?php
                                foreach ($weapons as $key => $wep) {
                                    $name[$key]     = $wep['name'];
                                    $kills[$key]    = $wep['kills'];
                                    $hits[$key]     = $wep['hits'];
                                    $shots[$key]    = $wep['shots'];
                                    $accuracy[$key] = $wep['accuracy'];
                                }

                                array_multisort($accuracy, SORT_NUMERIC, SORT_DESC, $weapons);
                                $i = 0;
                                foreach ($weapons as $wep): ?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($wep['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/weapons/<?= $wep['name']; ?>.png"
                                                                 class="img-responsive center-block"
                                                                 height="180px"
                                                                 width="239px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-child"></i> <strong>Kills</strong></td>
                                                        <td><?= @number_format(round($wep['kills'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-minus"></i> <strong>Hits</strong></td>
                                                        <td><?= @number_format(round($wep['hits'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-sun-o"></i> <strong>Shots</strong></td>
                                                        <td><?= @number_format(round($wep['shots'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Accuracy %</strong>
                                                        </td>
                                                        <td>
                                                        <span class="label label-success"><?= @round(($wep['accuracy'] * 100), 1); ?>
                                                            %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="maps">
                    <ul class="nav nav-pills">
                        <li role="presentation" class="active"><a data-toggle="pill" href="#sortByWin">Wins</a></li>
                        <li role="presentation"><a data-toggle="pill" href="#sortByRounds">Rounds</a></li>
                        <li role="presentation"><a data-toggle="pill" href="#sortByWinRatio">Win %</a></li>
                    </ul>
                    <div class="tab-content">
                        <br/>
                        <div id="sortByWin" class="tab-pane fade in active">
                            <div class="row">
                                <?php
                                foreach ($maps as $key => $row) {
                                    $name[$key]     = $row['name'];
                                    $rounds[$key]   = $row['rounds'];
                                    $wins[$key]     = $row['wins'];
                                    $winratio[$key] = $row['winratio'];
                                }
                                array_multisort($wins, SORT_NUMERIC, SORT_DESC, $maps);
                                $i = 0;
                                foreach ($maps as $map):?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($map['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/maps/<?= $map['name']; ?>.jpg"
                                                                 class="center-block"
                                                                 height="160px"
                                                                 width="280px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-trophy"></i> <strong>Wins</strong></td>
                                                        <td><?= @number_format(round($map['wins'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-gamepad"></i> <strong>Rounds</strong></td>
                                                        <td><?= @number_format(round($map['rounds'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Win %</strong>
                                                        </td>
                                                        <td>
                                                            <span class="label label-success"><?= @round(($map['winratio'] * 100), 1); ?>
                                                                %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                        <div id="sortByRounds" class="tab-pane fade">
                            <div class="row">
                                <?php
                                foreach ($maps as $key => $row) {
                                    $name[$key]     = $row['name'];
                                    $rounds[$key]   = $row['rounds'];
                                    $wins[$key]     = $row['wins'];
                                    $winratio[$key] = $row['winratio'];
                                }
                                array_multisort($rounds, SORT_NUMERIC, SORT_DESC, $maps);
                                $i = 0;
                                foreach ($maps as $map):?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($map['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/maps/<?= $map['name']; ?>.jpg"
                                                                 class="center-block"
                                                                 height="160px"
                                                                 width="280px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-trophy"></i> <strong>Wins</strong></td>
                                                        <td><?= @number_format(round($map['wins'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-gamepad"></i> <strong>Rounds</strong></td>
                                                        <td><?= @number_format(round($map['rounds'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Win %</strong>
                                                        </td>
                                                        <td>
                                                            <span class="label label-success"><?= @round(($map['winratio'] * 100), 1); ?>
                                                                %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                        <div id="sortByWinRatio" class="tab-pane fade">
                            <div class="row">
                                <?php
                                $i = 0;
                                foreach ($maps as $key => $row) {
                                    $name[$key]     = $row['name'];
                                    $rounds[$key]   = $row['rounds'];
                                    $wins[$key]     = $row['wins'];
                                    $winratio[$key] = $row['winratio'];
                                }
                                array_multisort($winratio, SORT_NUMERIC, SORT_DESC, $maps);
                                foreach ($maps as $map):?>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="text-center"><?= strtoupper($map['name']); ?></h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-responsive table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="images/maps/<?= $map['name']; ?>.jpg"
                                                                 class="center-block"
                                                                 height="160px"
                                                                 width="280px"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-trophy"></i> <strong>Wins</strong></td>
                                                        <td><?= @number_format(round($map['wins'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fa fa-gamepad"></i> <strong>Rounds</strong></td>
                                                        <td><?= @number_format(round($map['rounds'], 0)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="fa fa-area-chart"></i> <strong>Win %</strong>
                                                        </td>
                                                        <td>
                                                            <span class="label label-success"><?= @round(($map['winratio'] * 100), 1); ?>
                                                                %</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++;
                                    if ($i > 2) {
                                        echo '<div class="clearfix"></div>';
                                        $i = 0;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="achievements">
                    <div class="row">
                        <?php
                        $xml    = simplexml_load_file($user['custom_url'] . "stats/CSGO?xml=1");
                        $contor = 0;
                        $total  = 0;
                        foreach ($xml->achievements->achievement as $achivement) {
                            if ($achivement->unlockTimestamp) {
                                $name = $achivement->name;
                                $name = str_replace(" ", "", $name);
                                $name = str_replace("Pick'Em", "PickEm", $name);
                                $name = str_replace("'", "", $name);
                                $name = str_replace("/", "", $name);
                                $time = $achivement->unlockTimestamp;
                                $time = str_replace(" ", "", $time);
                                echo '
                                <div class="col-md-1 col-xs-4"  style="margin-bottom:20px;">
                                    <a data-toggle="modal" data-target="#' . $name . '" href="#' . $name . '">
                                        <img style="border-radius:3px;" src="' . $achivement->iconClosed . '" />
                                    </a>
                                </div>
                                <!-- Modal -->
                                <div id="' . $name . '" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                    <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><i class="fa fa-pencil"></i> ' . $achivement->name . '</h4>
                                            </div>
                                            <div class="modal-body">
                                                <center>
                                                    <img src="' . $achivement->iconClosed . '">
                                                    <br /><br />
                                                    <table class="table table-striped" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>' . $achivement->name . '</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td><i>' . $achivement->description . '</i></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Opened at: <strong>' . date("d M Y H:i", $time) . '</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </center>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">&times; Close</button>
                                             </div>
                                        </div>
                                    </div>
                                </div>';
                                $contor++;
                            }
                            $total++;
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary progress-bar-striped"
                                     role="progressbar"
                                     aria-valuenow="<?= round(($contor / $total) * 100); ?>"
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                     style="width: <?php echo round(($contor / $total) * 100); ?>%">
                                    <?= round(($contor / $total) * 100); ?>%
                                    (<?= $contor . "/" . $total; ?>)
                                </div>
                            </div>
                        </div>
                        <?php
                        $xml = simplexml_load_file($user['custom_url'] . "stats/CSGO?xml=1");
                        foreach ($xml->achievements->achievement as $achivement) {
                            if (!$achivement->unlockTimestamp) {
                                $name = $achivement->name;
                                $name = str_replace(" ", "", $name);
                                $name = str_replace("'", "", $name);
                                echo '
                                <div class="col-md-1 col-xs-4"  style="margin-bottom:20px;">
                                    <a data-toggle="modal" data-target="#' . $name . '" href="#' . $name . '">
                                        <img style="border-radius:3px;" src="' . $achivement->iconOpen . '"></img>
                                    </a>
                                </div>
                                <!-- Modal -->
                                <div id="' . $name . '" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                    <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><i class="fa fa-pencil"></i> ' . $achivement->name . '</h4>
                                            </div>
                                            <div class="modal-body">
                                                <center>
                                                    <img src="' . $achivement->iconOpen . '">
                                                    <br /><br />
                                                    <table class="table table-striped" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>' . $achivement->name . '</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td><i>' . $achivement->description . '</i></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </center>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">&times; Close</button>
                                             </div>
                                        </div>
                                    </div>
                                </div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="signature">
                    <script type="text/javascript">var url_site = "<?= $steamauth['domainname'];?>";</script>
                    <script type="text/javascript">
                        var bg = "bg1";
                        var rank = 1;
                        var xprank = 1;
                        var color_username = "FFFFFF";
                        var color_title = "FFC200";
                        var color_info = "FFFFFF";

                        function myBackground(name) {
                            document.getElementById("background").innerHTML = name;
                            bg = name;
                        };

                        function myRank(name) {
                            document.getElementById("rank").innerHTML = name;
                            rank = name;
                        };

                        function myXPRank(name) {
                            document.getElementById("xprank").innerHTML = name;
                            xprank = name;
                        };

                        function myColorUsername(name) {
                            document.getElementById("color_username").innerHTML = name;
                            color_username = name;
                        };

                        function myColorTitle(name) {
                            document.getElementById("color_title").innerHTML = name;
                            color_title = name;
                        };

                        function myColorInfo(name) {
                            document.getElementById("color_info").innerHTML = name;
                            color_info = name;
                        };
                    </script>
                    <script type="text/javascript" src="js/pick_color.js"></script>
                    <table class="table" width="100%">
                        <tr>
                            <td>
                                &nbsp;&bull; Banner (350x90px):<br/>
                                &nbsp;<img id="banner" src="">
                                <script>
                                    var steamid = '<?= $steamId64;?>';
                                    var bg = "bg1";
                                    var rank = 1;
                                    var xprank = 1;
                                    var color_username = "FFFFFF";
                                    var color_title = "FFC200";
                                    var color_info = "FFFFFF";

                                    function myBackground(name) {
                                        document.getElementById("background").innerHTML = name;
                                        bg = name;
                                        setTimeout('afimg()', 0);
                                    }

                                    function myRank(name) {
                                        document.getElementById("rank").innerHTML = name;
                                        rank = name;
                                        setTimeout('afimg()', 0);
                                    }

                                    function myXPRank(name) {
                                        document.getElementById("xprank").innerHTML = name;
                                        xprank = name;
                                        setTimeout('afimg()', 0);
                                    }

                                    function myColorUsername(name) {
                                        document.getElementById("color_username").innerHTML = name;
                                        color_username = name;
                                        setTimeout('afimg()', 0);
                                    }

                                    function myColorTitle(name) {
                                        document.getElementById("color_title").innerHTML = name;
                                        color_title = name;
                                        setTimeout('afimg()', 0);
                                    }

                                    function myColorInfo(name) {
                                        document.getElementById("color_info").innerHTML = name;
                                        color_info = name;
                                        setTimeout('afimg()', 0);
                                    }

                                    document.getElementById('banner').src = url_site + "signature/" + steamid + "_" + bg + "_" + rank + "_" + xprank + "_" + color_username + "_" + color_title + "_" + color_info + ".png";

                                    function afimg() {
                                        document.getElementById('banner').src = url_site + "signature/" + steamid + "_" + bg + "_" + rank + "_" + xprank + "_" + color_username + "_" + color_title + "_" + color_info + ".png";
                                    }
                                </script>
                            </td>
                        </tr>
                    </table>
                    <table class="table" width="100%">
                        <tr>
                            <td>&raquo; Background:</td>
                            <td>
                                <select name="background" onchange="myBackground(this.value);" class="form-control">
                                    <option value="bg1" selected="true">Background #1 - 350 x 90</option>
                                    <option value="bg2">Background #2 - 350 x 90</option>
                                    <option value="bg3">Background #3 - 350 x 90</option>
                                    <option value="bg4">Background #4 - 350 x 90</option>
                                    <option value="" disabled>Team Background Edition</option>
                                    <option value="bg5">Background #5 - HR</option>
                                    <option value="bg6">Background #6 - Titan</option>
                                    <option value="bg7">Background #7 - NIP</option>
                                    <option value="bg8">Background #8 - Fnatic</option>
                                    <option value="bg9">Background #9 - Flipsid3</option>
                                    <option value="bg10">Background #10 - Dignitas</option>
                                    <option value="bg11">Background #11 - Na'Vi</option>
                                    <option value="bg12">Background #12 - VirtusPro</option>
                                    <option value="bg13">Background #13 - Astralis</option>
                                    <option value="bg14">Background #14 - LG</option>
                                    <option value="bg15">Background #15 - Team LDLC</option>
                                    <option value="bg16">Background #16 - Penta</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&raquo; Competitive Rank:</td>
                            <td>
                                <select name="rank" onclick="myRank(this.value);" class="form-control">
                                    <option value="1" selected="true">Silver I</option>
                                    <option value="2">Silver II</option>
                                    <option value="3">Silver III</option>
                                    <option value="4">Silver IV</option>
                                    <option value="5">Silver Elite</option>
                                    <option value="6">Silver Master Elite</option>
                                    <option value="7">Gold Nova I</option>
                                    <option value="8">Gold Nova II</option>
                                    <option value="9">Gold Nova III</option>
                                    <option value="10">Gold Nova Elite</option>
                                    <option value="11">Master Guardian I</option>
                                    <option value="12">Master Guardian II</option>
                                    <option value="13">Master Guardian Elite</option>
                                    <option value="14">Distinguished Master Guardian</option>
                                    <option value="15">Legendary Eagle</option>
                                    <option value="16">Legendary Eagle Master</option>
                                    <option value="17">Suprem Master First Class</option>
                                    <option value="18">The Global Elite</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&raquo; XP Rank:</td>
                            <td>
                                <select name="xprank" onclick="myXPRank(this.value);" class="form-control">
                                    <option value="1" selected="true">Recruit Rank 1</option>
                                    <option value="2">Private Rank 2</option>
                                    <option value="3">Private Rank 3</option>
                                    <option value="4">Private Rank 4</option>
                                    <option value="5">Corporal Rank 5</option>
                                    <option value="6">Corporal Rank 6</option>
                                    <option value="7">Corporal Rank 7</option>
                                    <option value="8">Corporal Rank 8</option>
                                    <option value="9">Sergeant Rank 9</option>
                                    <option value="10">Sergeant Rank 10</option>
                                    <option value="11">Sergeant Rank 11</option>
                                    <option value="12">Sergeant Rank 12</option>
                                    <option value="13">Master Sergeant Rank 13</option>
                                    <option value="14">Master Sergeant Rank 14</option>
                                    <option value="15">Master Sergeant Rank 15</option>
                                    <option value="16">Master Sergeant Rank 16</option>
                                    <option value="17">Sergeant Major Rank 17</option>
                                    <option value="18">Sergeant Major Rank 18</option>
                                    <option value="19">Sergeant Major Rank 19</option>
                                    <option value="20">Sergeant Major Rank 20</option>
                                    <option value="21">Lieutenant Rank 21</option>
                                    <option value="22">Lieutenant Rank 22</option>
                                    <option value="23">Lieutenant Rank 23</option>
                                    <option value="24">Lieutenant Rank 24</option>
                                    <option value="25">Captain Rank 25</option>
                                    <option value="26">Captain Rank 26</option>
                                    <option value="27">Captain Rank 27</option>
                                    <option value="28">Captain Rank 28</option>
                                    <option value="29">Major Rank 29</option>
                                    <option value="30">Major Rank 30</option>
                                    <option value="31">Major Rank 31</option>
                                    <option value="32">Major Rank 32</option>
                                    <option value="33">Colonel Rank 33</option>
                                    <option value="34">Colonel Rank 34</option>
                                    <option value="35">Colonel Rank 35</option>
                                    <option value="36">Brigadier General Rank 36</option>
                                    <option value="37">Major General Rank 37</option>
                                    <option value="38">Lieutenant General Rank 38</option>
                                    <option value="39">General Rank 39</option>
                                    <option value="40">Global General Rank 40</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&raquo; Username Color:</td>
                            <td><input class="color"
                                       style="padding: 3px; width: 55px; font: 12px tahoma, sans-serif; font-weight: bold;"
                                       name="color_username" type="text" size="6" maxlength="6"
                                       onchange="myColorUsername(this.value); afimg();"/></td>
                        </tr>
                        <tr>
                            <td>&raquo; Title Color:</td>
                            <td><input class="color"
                                       style="padding: 3px; width: 55px; font: 12px tahoma, sans-serif; font-weight: bold;"
                                       name="color_title" type="text" size="6" maxlength="6"
                                       onchange="myColorTitle(this.value); afimg();"/></td>
                        </tr>
                        <tr>
                            <td>&raquo; Info Color:</td>
                            <td><input class="color"
                                       style="padding: 3px; width: 55px; font: 12px tahoma, sans-serif; font-weight: bold;"
                                       name="color_info" type="text" size="6" maxlength="6"
                                       onchange="myColorInfo(this.value); afimg();"/></td>
                        </tr>
                    </table>
                    <table class="table" width="100%">
                        <tr>
                            <td>
                                <div align="center">
                                    <pre id="cod_banner"
                                         style="font-size:11px;"
                                         class="form-control">[url=<?= $steamauth['domainname'] . 'player/'. $steamId64; ?>][img]<?= $steamauth['domainname'] . "signature/" . $steamId64; ?>_<span
                                                id="background">bg1</span>_<span id="rank">1</span>_<span
                                                id="xprank">1</span>_<span id="color_username">FFFFFF</span>_<span
                                                id="color_title">FFC200</span>_<span id="color_info">FFFFFF</span>.png[/img][/url]</pre>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br/><br/>
    <?php
} elseif ((!empty($user) && empty($game)) || empty($user)) {
    warning("This account doesn't have Counter-Strike:Global-Offensive or Game details are private! Please visit FAQ for more information!");
}

footer();