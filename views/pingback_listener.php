<?php

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

?>