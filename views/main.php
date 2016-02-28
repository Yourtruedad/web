<div> 
    <div class="row">
        <div class="col-md-8">
        	<h2>News & Updates</h2>
        	<hr>
            <h4>Bootstrap starter template</h4>
            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
            Some additional text here. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
            <p class="text-right"><small>2016-02-26 18:39</small></p>

            <h4>Bootstrap starter template</h4>
            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
            Some additional text here. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.
            <p class="text-right"><small>2016-02-26 17:39</small></p>
        </div>
        <div class="col-md-4">
            <?php
            /*if (true === $common->checkifServerIsOnline()) {
                $onlineCount = $common->getServerOnlineCount('global');
                echo 'Server online, players online: ' . $onlineCount;
            } else {
                //echo 'Server offline';
            }*/
            ?>
            <div class="server-status">
                <div class="server-online">
                    <p class="lead">Server Active</p>
                    <?php 
                    $serverStatus = $common->checkIfGameServerIsOnline('active');
                    echo '<span class="display-inline-block right-margin">Status: ' . ('online' === $serverStatus ? '<span class="server-online-color">' . $serverStatus . '</span>' : '<span class="server-offline-color">' . $serverStatus . '</span>') . '</span>' . ('online' === $serverStatus ? 'Connected Players: ' . $common->getServerOnlineCount('active') : '');
                    ?>
                </div>
            </div>
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
    </div>
</div>