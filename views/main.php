<div> 
    <div class="row">
        <div class="col-md-7">
			<div class="row">
				<h2>News & Updates</h2>
				<hr>
				<h4>Welcome to EverWinter MU</h4>
				<p class="lead">The server launch is scheduled to March 12, 2016 7:00 p.m. (GMT+1)</p>
				Feel free to create your EverWinter MU account now. If you have any questions or want to find out more about the server, please reach out to us on our <a href="http://forum.everwintermu.com" title="Message Board">message board</a>. See you soon!
				<p class="text-right"><small>2016-03-02 22:19</small></p>
			</div>
			<div class="row">
				<h4>Upcoming Game Events</h4>
				<div id="eventList">
					<table class="table table-bordered">
						<tr>
							<th>Name</th>
							<th>Time</th>
						</tr>
						<?php
						$server = new server();
						$events = $server->prepareEvents($server->getUpcomingEvents());
						foreach ($events as $event) {
							echo '<tr><td>' . $event['event_name'] . '</td><td>' . $event['event_time'] . '</td></tr>';
						}
						?>
					</table>
				</div>
				<br>
			</div>
        </div>
        <div class="col-md-4 col-md-offset-1">
            <div class="row">
                <div class="server-status">
                    <div class="server-online">
                        <p class="lead">Server Active</p>
                        <?php 
                        $serverStatus = $common->checkIfGameServerIsOnline('active');
                        echo '<span class="display-inline-block right-margin">Status: ' . ('online' === $serverStatus ? '<span class="server-online-color">' . $serverStatus . '</span>' : '<span class="server-offline-color">' . $serverStatus . '</span>') . '</span>' . ('online' === $serverStatus ? 'Connected Players: ' . $common->getServerOnlineCount('active') : '');
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="server-status">
                    <div class="server-offline">
                        <p class="lead">Server Passive</p>
                        <?php 
                        $serverStatus = $common->checkIfGameServerIsOnline('passive');
                        echo '<span class="display-inline-block right-margin">Status: ' . ('online' === $serverStatus ? '<span class="server-online-color">' . $serverStatus . '</span>' : '<span class="server-offline-color">' . $serverStatus . '</span>') . '</span>' . ('online' === $serverStatus ? 'Connected Players: ' . $common->getServerOnlineCount('passive') : '');
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <a href="?module=account&action=wcoins" title="Get WCoins"><img src="views/img/get-wcoins.png" class="img-responsive margin-center" alt="Get WCoins"></a>
                <hr>
            </div>
            <div class="row">
                <h4>Top 5 Players</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr><th>#</th><th>Nick</th><th>Class</th><th>Reset</th><th>Level</th></tr>
                        <?php 
                            $character = new character();
                            $character->hideRankingCharacterDetails = true;
                            $shortRanking = server::getTop5CharacterRanking(); 
                            if (!empty($shortRanking)) {
                                foreach ($shortRanking as $key => $characterDetails) {
                                    echo '<tr>
                                            <td>' . ($key + 1) . '</td>
                                            <td>' . $characterDetails['Name'] . '</td>
                                            <td>' . character::getCharacterClassName($characterDetails[character::$characterClassSystemName]) . '</td>
                                            <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterResetSystemName]) . '</td>
                                            <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterLevelSystemName]) . '</td>
                                        </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5">No characters found</td></tr>';
                            }
                        ?>
                    </table>
                </div>
            </div>
            <div class="row">
                <h4>Vote For Us</h4>
                <hr>
                <a href="https://mu.mmotop.ru/servers/24601"; target="_blank"><img src="http://img.mmotop.ru/mmo_24601.png"; width="88" height="53" border="0" id="mmotopratingimg" alt="Рейтинг серверов mmotop"></a>
                <script type="text/javascript">document.write("<script src='http://js.mmotop.ru/rating_code.js?"; + Math.round((((new Date()).getUTCDate() + (new Date()).getMonth() * 30) / 7)) + "_" + (new Date()).getFullYear() + "' type='text/javascript'><\/script>");</script>
                <a href="http://www.xtremetop100.com/in.php?site=1132359020" title="Mu Online Server">
                <img src="http://www.xtremeTop100.com/votenew.jpg" border="0" alt="Mu Online Server"></a>
                <a href="http://www.gtop100.com/topsites/Mu-Online/sitedetails/EverWinter-NEW-x27-SERVER-ON-12-MARCH-90096?vote=1" title="Mu Online Private Server" target="_blank">
                <img src="http://www.gtop100.com/images/votebutton.jpg" border="0" alt="Mu Online Private Server"></a> 
                <a href="https://topg.org/mu-private-servers/in-427882" target="_blank"><img src="https://topg.org/topg.gif" width="88" height="53" border="0" alt="mu private servers"></a>
            </div>
        </div>
    </div>
</div>