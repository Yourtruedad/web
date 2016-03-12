<?php
/*
if ((!empty ($_SERVER['HTTP_X_REAL_IP']) && in_array($_SERVER['HTTP_X_REAL_IP'], payment::$paymentwallIps)) || $accountId = 'pablo') {
	$payment = new payment();
    $pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
    if ($pingback->validate()) {
        $virtualCurrency = $pingback->getVirtualCurrencyAmount();
        if ($pingback->isDeliverable()) {
        // deliver the virtual currency
        } else if ($pingback->isCancelable()) {
        // withdraw the virual currency
        } 
        echo 'OK'; 
    } else {
        echo $pingback->getErrorSummary();
    }
}*/
if (isset($_GET['vendor'] && 'paypal' === $_GET['vendor'])) {
    $common->writeToFile('pingbacktest.txt', print_r($_POST, true).print_r($_GET, true));
	if (!empty($_POST)) {
		if (!empty($_POST['custom'])) {
			if (true === cacheDb::getCacheDbConnectionStatus()) {
			    $cacheDb = new cacheDb();
			    $transactionDetails = $cacheDb->getPaypalTransactionDetailsByToken($_POST['custom']);
				if (in_array($_POST['option_selection1'], payment::$paypalWcoinPackages)) {
					
				}
			}
		}
	}
}

?>