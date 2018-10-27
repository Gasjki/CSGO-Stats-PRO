<?php
include "core.php";
head();
?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12 text-center">
                <?php
                if (isset($_POST['send'])) {
                    $name    = $_POST['name'];
                    $email   = $_POST['email'];
                    $ip      = $_SERVER['REMOTE_ADDR'];
                    $message = htmlspecialchars($_POST['message']);

                    if (strlen($message) < 15 || strlen($message) > 500) {
                        error("Your message contains " . strlen($message) . " characters. Your message needs to have between 15 and 500 chars!");
                    } else {
                        $subject  = 'Contact - ' . $site['title'];
                        $amessage = '
							<span style="text-align: center;"
								<h2><strong>' . $name . '</strong> (<strong>' . $ip . '</strong>) has sent you a message:</h2><br /><br />
								<i>' . $message . ' </i>
								<br /><br />
							</span>
						';
                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers  .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                        $headers  .= 'To: ' . $site['email'] . ' <' . $site['email'] . '>' . "\r\n";
                        $headers  .= 'From: ' . $email . ' <' . $email . '>' . "\r\n";
                        mail($site['email'], $subject, $amessage, $headers);
                        ok("You message has been sent ! We will contact you as fast as we can, usually ~24 hrs.");
                    }
                }
                ?>
                <h4><i>Easily view and share your CS:GO stats</i></h4><br/>
                <form role="form" action="" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control input-lg" name="username"
                               placeholder="Paste a link to a Steam Profile, SteamID64 or customURL"
                               required
                        />
                        <span class="input-group-btn">
							<button type="submit"
                                    name="search"
                                    class="btn btn-success btn-lg">
                                <i class='fa fa-search'></i>
                            </button>
						</span>
                    </div>
                </form>
                <?php
                if (isset($_POST['search'])) {
                    $username = htmlspecialchars($_POST['username']);
                    $steamid = null;
                    if (strlen($username) < 3) {
                        error("This input is too small !");
                    } else {
                        if (ctype_digit($username)) {
                            $steamid = $username;
                        } else {
                            $test = format($username);
                            if ($test) {
                                $cauta   = 'https://steamcommunity.com/';
                                $cautare = strpos($username, $cauta);
                                if ($cautare !== false) {
                                    @$xml = simplexml_load_file($username . "?xml=1");
                                } else {
                                    @$xml = simplexml_load_file("https://" . $username . "?xml=1");
                                }
                                if ($xml->steamID64) {
                                    $steamid = $xml->steamID64;
                                } else {
                                    echo "<br /><div class='alert alert-danger text-center'><i class='fa fa-warning'></i> Player not found !</div>";
                                    footer();
                                    exit();
                                }
                            } else {
                                $string = "http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=" . $steamauth['apikey'] . "&vanityurl=" . $username . "";
                                $ch     = curl_init();
                                // Disable SSL verification
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                // Will return the response, if false it print the response
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                // Set the url
                                curl_setopt($ch, CURLOPT_URL, $string);
                                // Execute
                                $result = curl_exec($ch);
                                // Closing
                                curl_close($ch);
                                $getSteamID = json_decode($result, true);
                                if (isset($getSteamID['response']['steamid'])) {
                                    $steamid = $getSteamID['response']['steamid'];
                                }
                            }
                        }
                        if ($steamid) {
                            echo "<br /><div class='alert alert-success text-center'><i class='fa fa-refresh fa-spin'></i> You will be redirected soon ! </div>";
                            echo '<meta http-equiv="refresh" content="1;url=player/' . $steamid . '">';
                        } else {
                            echo "<br /><div class='alert alert-danger text-center'><i class='fa fa-warning'></i> Player not found !</div>";
                        }
                    }
                }
                ?>
            </div>
            <hr/>
            <div class="col-md-12 text-center">
                <div class="col-md-4">
                    <h2><i class='fa fa-area-chart fa-2x'></i> All statistics</h2>
                    All CS:GO statistics are available here.
                </div>
                <div class="col-md-4">
                    <h2><i class='fa fa-fighter-jet fa-2x'></i> Fast response</h2>
                    Statistics are shown really fast, but also you need a good internet speed.
                </div>
                <div class="col-md-4">
                    <h2><i class='fa fa-lock fa-2x'></i> Security</h2>
                    All is secure. We don't use database for anything.<br/> We will never ask for your Steam
                    password.
                </div>
            </div>
        </div>
    </div>

<?php
footer();