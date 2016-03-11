<?php

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
}

?>