<div class="starter-template"> 
    <?php
    if (true === $common->checkifGameServerIsOnline()) {
        $onlineCount = 0;
        if (true === USE_MYSQL_CACHE) {
            $cacheDb = new cacheDb();
            if (true === $cacheDb->checkIfServerInformationIsCurrent($cacheDb->onlineCountServerInformationName)) {
                $serverInformation = $cacheDb->getCurrentServerInformation($cacheDb->onlineCountServerInformationName);
                if (!empty($serverInformation)) {
                    $onlineCount = $serverInformation['value'];
                } else {
                    $onlineCount = db::getOnlineAccountsCount();
                }
            } else {
                $onlineCount = db::getOnlineAccountsCount();
                $cacheDb->saveCurrentServerInformation($cacheDb->onlineCountServerInformationName, cacheDb::CACHE_PLAYER_ONLINE_COUNT_TIME, $onlineCount);
            }
        } else {
            $onlineCount = db::getOnlineAccountsCount();
        }
        echo 'Server online, players online: ' . $onlineCount;
    } else {
    	echo 'Server offline';
    }
    ?>
    <h1>Bootstrap starter template</h1>
    <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
    <p></p>
</div>