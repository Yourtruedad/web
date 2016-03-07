<div> 
    <div class="row">
        <div class="col-md-8">
        	<h2>News & Updates</h2>
        	<hr>
            <h4>Welcome to EverWinter MU</h4>
            <p class="lead">The server launch is scheduled to March 12, 2016 7:00 p.m.</p>
            Feel free to create your EverWinter MU account now. If you have any questions or want to find out more about the server, please reach out to us on our <a href="http://forum.everwintermu.com" title="Message Board">message board</a>. See you soon!
            <p class="text-right"><small>2016-03-02 22:19</small></p>

        </div>
        <div class="col-md-4">
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
                <h4>Top 5 Players</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr><th>#</th><th>Nick</th><th>Class</th><th>Reset</th><th>Level</th></tr>
                        <?php 
                            $character = new character();
                            $character->hideRankingCharacterDetails = true;
                            $shortRanking = server::getTop5CharacterRanking(); 
                            foreach ($shortRanking as $key => $characterDetails) {
                                echo '<tr>
                                        <td>' . ($key + 1) . '</td>
                                        <td>' . $characterDetails['Name'] . '</td>
                                        <td>' . character::getCharacterClassName($characterDetails[character::$characterClassSystemName]) . '</td>
                                        <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterResetSystemName]) . '</td>
                                        <td>' . $character->hideRankingCharacterDetail($characterDetails[character::$characterLevelSystemName]) . '</td>
                                    </tr>';
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>