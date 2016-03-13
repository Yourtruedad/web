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

    $content = print_r($_POST, true).print_r($_GET, true);
    $common->writeToFile('pingbacktest.txt', print_r($_POST, true).print_r($_GET, true));
    $cacheDb = new cacheDb();
    $cacheDb->savePaypalIpn($_POST['custom'], $_POST['option_selection1']);
/*
if (isset($_GET['vendor'] && 'paypal' === $_GET['vendor'])) {
    $common->writeToFile('pingbacktest.txt', print_r($_POST, true).print_r($_GET, true));
	if (!empty($_POST)) {
		$token = $common->secureStringVariable($_POST['custom']);
		if (!empty($token)) {
			if (true === cacheDb::getCacheDbConnectionStatus()) {
			    $cacheDb = new cacheDb();
			    $transactionDetails = $cacheDb->getPaypalTransactionDetailsByToken($token);
				$product = $common->secureStringVariable($_POST['option_selection1']);
				if (in_array($product, payment::$paypalWcoinPackages)) {
					$wcoinAmount = explode(' ', $product);
					$db = new db();
					if (true === $db->checkIfAccountHasWcoinRecord($transactionDetails['username'])) {
						//update add points
						$db->addWcoinsForAccount($transactionDetails['username'], $wcoinAmount[0]);
					} else {
						//create record
						$db->addAccountWcoinRecord($transactionDetails['username'], $wcoinAmount[0]);
					}
					$cacheDb->completePaypalTransaction($token, $product));
				}
			}
		}
	}
}
*/

?>