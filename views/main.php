<div> 
    <div class="row">
        <div class="col-md-7">
		    <div class="row">
			    <!--<div class="alert alert-info"><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>We are online now!</strong></div>-->
			</div>
            <div class="row">
                <h2>News & Updates</h2>
				<hr>
				<h4>The End</h4>
				<p>Dear Players,

there were some connectivity issues today on our server. Let me explain what happened. There was an attack on IGCN and because of that their server files leaked to the Internet. They wanted to find a person responsible for that and guess what happened. They said it was all because of me (maniek6). The licence for our server files has been banned. Our IGCN account has been disabled. They also sent me an email in which they say the incident was reported to the police. Supposedly it was Rebekka who has done this and I helped him. According to the email the incident was reported to the Police in Ireland and Poland and they threatened me that I will have to compensate for all of this and we will meet in court. I HAD NOTHING TO DO WITH THIS. It sounds like a terrible joke unfortunately it isn’t. The majority of players left because they were not able to log in today.</p>

<p>As a result the server cannot be online any longer and we cannot cooperate with IGCN too. Our server files and the licence have been unlocked which only confirms that I am innocent. However, we cannot be sure that it won’t happen again. There is no certainty that one day server will be offline again because WizzY (IGCN team member) will try to ban us because of something we did not do. We have to say that it is not worth trusting IGCN. Let me add that Wizzy gave us 3 free days for our licence as a way to compensate for the ban of everything. What a great way to compensate for the loss of players, trust in IGCN and trust of you. </p>

<p>To sum up, thank you very much for 33 days during which we had a lot of fun. However, it has all come to an end way to soon. We are really sorry but this is all we can say. We are not to blame here. We though that these server files would be one of the best things of our server but they turned out to to prove our undoing.</p>
				<p class="text-right"><small>2016-05-03 23:12</small></p>
				
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
			<div class="row text-center">
			    <?php
				$serverOnlineDuration = server::getServerOnlineDuration();
				if (is_numeric($serverOnlineDuration)) {
					$serverOnlineFor = 'more than ' . $serverOnlineDuration . ' days';
				} else {
					$serverOnlineFor = $serverOnlineDuration;
				}
				if (!empty($serverOnlineFor)) {
					echo '<span class="label label-info ">Server online for ' . $serverOnlineFor . '</span>';
				}
				?>
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
                    echo '<h3>Player of the Day: ' . $playerOfTheDay . '</h3><h5><small>Well done! It is based on the <a href="?module=score_ranking" title="Score Ranking">Score Ranking</a></small></h5><hr>';
                }
                ?>
            </div>
            <div class="row">
                <?php
                $castleOwnerGuildName = db::getCastleOwnerGuildName();
                if (!empty($castleOwnerGuildName)) {
					$guildDetails = (new db())->getGuildDetails($castleOwnerGuildName);
                    echo '<h3>Castle Owner Guild: ' . $castleOwnerGuildName . '</h3><h5><small>Congratulations! Castle Siege happens every Saturday</small></h5><hr>';
                }
                ?>
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