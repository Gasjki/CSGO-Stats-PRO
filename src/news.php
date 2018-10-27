<?php 
include "core.php";
head();

// Let's get last 5 news
$url = "http://api.steampowered.com/ISteamNews/GetNewsForApp/v0001/?format=json&appid=730&count=5";
//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
// Will dump a beauty json :3
$newss = json_decode($result);
foreach($newss->appnews->newsitems->newsitem as $news){
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<i class="glyphicon glyphicon-tags" aria-hidden="true"></i>&nbsp;&nbsp;<?= $news->title; ?>
	</div>
	<div class="panel-body">
		<table class='table table-responsive'>
			<tr>
                <td>
                    <i class="glyphicon glyphicon-user" aria-hidden="true"></i> <strong><a href="<?= $news->url;?>"><?= $news->feedlabel ;?></a></strong> | <i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Date: <strong><?= date("d-m-Y H:i:s", $news->date) ;?></strong>
                </td>
            </tr>
			<tr>
                <td><?= $news->contents;?></td>
            </tr>
		</table>
	</div>
</div>
<?php
}
?>
<br /><br /><br />
<?php
footer();
?>