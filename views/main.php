<div> 
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <h2>News & Updates</h2>
				<hr>
				<h4>Let's try it again.</h4>
				<p>Dear Players, we, administrator, unfortunately have made several mistakes with the server configuration. The majority of them were insignificant but we managed to adjust them in just a few minutes so it means you probably did not even notice them. Nonetheless, everything should have been perfect because we are not new at this. Every mistake that we made caused players to leave the server. Probably the biggest one was at the very beginning which was about disabling the possibility to use the multi client and the Elf Buffer at level 30.</p> 

<p>After some time we managed to convince you, new players, that you can trust us because we are trying to make the server the best we possibility can. The number of active players was getting higher to reach 100 online on a very difficult server. We though that everything is going well. The server was online for 8 days without any outages. We managed to avoid DDoS attacks and get rid of cheaters from the server. We did not want to brag about it because it was our job.</p> 

<p>However, right now the only thing that we can do is to apologise. We have made one mistake which resulted in the issue with our database and we lost some data from it. The backup that we could make is corrupted and we are unable to recover anything from it. We have found the issue, fixed it and now everything is fine.</p> 

<p>We believe that the settings on our server were unique enough that you enjoyed the time you spent with us. We hope that you have found what you were looking for. Restarting the game from the backup could result in loosing many players. Therefore we decided to wipe the database and start fresh. The settings will stay the same.</p> 

<p>All players who bought wCoins will have them back (all of them) on the next edition of the server.</p> 

<p>The truth is that it is up to you whether the server will continue. Will you give us the second chance? Think about this and decide. If you still would like to stay with us, please spread the word that we are going come back. We believe that with the fresh start we can get even more players to make the game even more entertaining. We will come back better and stronger. We know this!</p>

<p>Everyone is invited to join the server which will launch again on <strong>Friday, April 1st, 2016</strong>. You will be able to create your new account on March 29th. </p>

<p>All players who bought wCoins, please contact us at <strong>admins@everwintermu.com</strong> to let us know how many wCoins you had and on which account they should be added. You will need only the transaction ID and your new login name.</p> 
                <p class="text-right"><small>2016-03-21 23:30</small></p>
                <small><em><a href="?module=news" title="News Archive">Click here to read the news archive</a></em></small>
            </div>
            <div class="row">
			    <hr>
                <h4>Upcoming Game Events</h4>
                <div id="eventList">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Time (GMT+1)</th>
                        </tr>
                        <?php
                        $server = new server();
                        $events = $server->prepareEvents($server->getUpcomingEvents());
                        foreach ($events as $event) {
                            echo '<tr><td>' . $event['event_name'] . '</td><td>' . $event['event_time'] . ' (left: ' . $event['event_in'] . ')</td></tr>';
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
            <h5 class="text-center">Players active recently: <?=server::getActiveAccountsRecentlyCount()?></h5>
			<hr>
            </div>
			<div class="row">
                <a href="?module=account&action=wcoins" title="Get WCoins"><img src="views/img/get-wcoins.png" class="img-responsive margin-center" alt="Get WCoins"></a>
				<hr>
            </div>
			<div class="row">
			    <?php
				$playerOfTheDay = $server->getPlayerOfTheDay();
				if (!empty($playerOfTheDay)) {
			        echo '<h3>Player of the Day: ' . $playerOfTheDay . '</h3><h5><small>Well done! It is based on the <a href="?module=score_ranking" title="Score Ranking">Score Ranking</a></small></h5>';
				}
				?>
				<hr>
			</div>
			<div class="row">
			    <?php
				$castleOwnerGuildName = db::getCastleOwnerGuildName();
				if (!empty($castleOwnerGuildName)) {
					echo '<h3>Castle Owner Guild: ' . $castleOwnerGuildName . '</h3><h5><small>Congratulations! Castle Siege happens every Saturday</small></h5>';
				}
				?>
				<hr>
			</div>
            <div class="row">
                <h4>Top 5 Players <small>Reset and Level</small></h4>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <tr><th>#</th><th>Nick</th><th>Class</th><th>Reset</th><th>Level</th></tr>
                        <?php 
                            $character = new character();
                            $character->hideRankingCharacterDetails = true;
                            $shortRanking = server::getCharacterRanking(5); 
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