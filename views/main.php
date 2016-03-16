<div> 
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <h2>News & Updates</h2>
                <hr>
                <h4>Message Board Update</h4>
                <p>Just a quick update from the administration. We have changed the privacy settings on our forum. It means that now you will need to create your account and log in to browse the content. We have also added a new Shoutbox to let you communicate with us easily (<a href="http://forum.everwintermu.com/index.php?p=/discussion/95/new-shoutbox-on-our-forum#latest" target="_blank">read more</a>).</p>
                <p class="text-right"><small>2016-03-16 12:48</small></p>
                <hr>
                <h4>Further Changes</h4>
                <p>We hope that you like the fact that the <strong>MuBot</strong> is now available for <strong>free</strong> on the Passive sub-server. We think it was a good change and now the Passive sub-server is more popular. However, we have made a few more changes in the configuration of the server:</p>
                <ul>
                    <li>We have <strong>increased</strong> the selling price of jewels in in-game shops. The new prices are now as follows: Bless - 690 000, Soul - 460 000, Creation - 770 000, Life - 1 150 000.</li>
                    <li>We have also found a minor bug in the client which could have caused a connection issue for some players. Luckily it has been fixed quickly and now everything should be fine. No need to download any patch manually. Our launcher will do this for you. More about this on our <a href="http://forum.everwintermu.com/index.php?p=/discussion/92/language-and-reconnect-system-news#latest">message board</a>.</li>
                </ul>
                <p>We are also working on some new features for our website. They will be available soon.</p>
                <p class="text-right"><small>2016-03-15 19:47</small></p>
                <hr>
                <h4>Reset Price And Exp. Rate Updates</h4>
                <p>Dear Players, we would like to let you know that we have introduced a few changes:</p>
                <ul>
                    <li>The <strong>exp. rate</strong> while playing in party has been slightly <strong>increased</strong>.</li>
                    <li>The new price of the <strong>character reset</strong> is now 30,000,000 Zen.</li>
                    <li>We have also slightly lowered the HP of Snakes.</li>
                </ul>
                <p>If you have any suggestions, do not hesitate to contact us.</p>
                <p class="text-right"><small>2016-03-14 19:36</small></p>
                <hr>
                <h4>Two Days After The Launch</h4>
                <p>The server has started successfully for all two days ago (March 12th, 2016). We are really happy that so many of you joined us. We are still adjusting some things in the configuration of the server and <strong>we have a few updates for you</strong>.</p>
                <p>We have changed the price of the <strong>MuBot</strong> on the <strong>Passive</strong> server. <strong>Now it is totally free</strong> so your income for the time you played AFK will be much more significant.</p>
                <p>We would also like to let you know the you can find in the game an item called <strong>Box of Tickets</strong>. If you find it and use it, you will get a free invitation to one of the in-game events. What is more, if you find i.e. a DS (Devil Square) invitation, it will work on all DS levels (DS1 to DS). So the invitation works on all event levels.</p>
                <p>Lastly, we have also added a new feature on our website that you can see below. It’s the Event Calendar and it allows you to check really quickly when is the next i.e. BC. I hope you like it.</p>
                <p>Please also remember that you can contact us if you have any issues on our <a href="http://forum.everwintermu.com" target="_blank">message board</a>.</p>
                <p class="text-right"><small>2016-03-14 10:51</small></p>
                <hr>
            </div>
            <div class="row">
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