<?php
include "core.php";
head();

if (!isset($_SESSION['steamid'])) {
    echo '<meta http-equiv="refresh" content="0;url='.$steamauth['domainname']. 'index.php">';
    exit;
}
?>
    <div class="panel panel-default">
        <table class="table table-responsive table-hover table-striped table-bordered" width="100%">
            <thead>
            <tr>
                <th class="text-center">
                    <i class='fa fa-asterisk'></i> <strong>AppID</strong>
                </th>
                <th>
                    <i class='fa fa-gamepad'></i> <strong>Game</strong>
                </th>
                <th class="text-center">
                    <i class='fa fa-clock-o'></i> <strong>Hours Played</strong>
                </th>
                <th class="text-center">
                    <i class='fa fa-cogs'></i> <strong>Actions</strong>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            require("userInfo.php");
            if ($steamprofile['communityvisibilitystate'] != 3) {
                ?>
                <tr>
                    <td colspan="4">Your profile is private , so we can't read information about games !</td>
                </tr>
                <?php
            } else {
                ?>
                <?php
                $xml = @simplexml_load_file($steamprofile['profileurl'] . "games?tab=all&xml=1");
                foreach ($xml->games->game as $game) {
                    $ok = 0;
                    if ($game->hoursOnRecord != 0) {
                        $ok = $game->hoursOnRecord;
                    }
                    echo "
							<tr class='text-center'>
								<td><br /><a href='" . $game->storeLink . "' style='text-decoration:none;'>" . $game->appID . "</a></td>
								<td class='text-left'><strong><img src='" . $game->logo . "'/>&nbsp;&nbsp;&nbsp;<i>" . $game->name . "</i></strong></td>
								<td><br /><p>" . $ok . " hrs</p></td>
								<td><br /><a href='steam://install/" . $game->appID . "' style='text-decoration:none;'><span class='label label-info'><i class='fa fa-upload'></i> Install</span></a> | <a href='steam://run/" . $game->appID . "' style='text-decoration:none;'><span class='label label-success'><i class='fa fa-power-off'></i> Play</span></a></td>
							</tr>
						";
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <br/><br/><br/>
<?php
footer();