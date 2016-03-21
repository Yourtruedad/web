<div> 
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <h2>News & Updates</h2>
				<hr>
				<h4>The First Castle Siege</h4>
				<p>The very first CS on our server has been completed. The <strong>TiTaN</strong> guild lead by <strong>RasAlGhul</strong> this time defeated the <strong>True</strong> guild lead by <strong>The0ne</strong>. Congratulations to TiTaNs!. We hope that the next week the siege will be even more exciting.</p>
				<p>Secondly, we would also like to let you know that two first players managed to reach the maximum level and reset their characters - <strong>RasAlGhul</strong> and <strong>KlauS</strong> - well done guys!</p>
				<p>Lastly, more and more players join EverWinter MU. We are really happy that it is so. Everyone is welcome.</p> 
				<p class="text-right"><small>2016-03-20 20:33</small></p>
				<hr>
				<h4>The /offtrade command</h4>
				<p>A quick update - if you want to you the /offtrade command, you will need to go to Noria on the Passive subserver.</p>
                <p class="text-right"><small>2016-03-19 15:43</small></p>
				<hr>
                <h4>New Score Ranking</h4>
                <p>Dear Players, we have introduced a new character ranking on our website. It is the <strong>Score Ranking</strong> which you can see if you <a href="?module=score_ranking">click here</a>. The new ranking will show who is the best player because there are several elements of the game that it measures (not just level and reset). In order to be the best, <u>you need to do events, win duels, increase your Gens rank and also gather money</u>.</p>
				<p>The Score Ranking refreshes every 24h. Check now who is the best so far and try to be number 1. If you have any questions regarding how this works, you can reach out to us on our message board.</p>
                <p class="text-right"><small>2016-03-18 18:06</small></p>
                <hr>
                <h4>Message Board Update</h4>
                <p>Just a quick update from the administration. We have changed the privacy settings on our forum. It means that now you will need to create your account and log in to browse the content. We have also added a new Shoutbox to let you communicate with us easily (<a href="http://forum.everwintermu.com/index.php?p=/discussion/95/new-shoutbox-on-our-forum#latest" target="_blank">read more</a>).</p>
                <p class="text-right"><small>2016-03-16 12:48</small></p>
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