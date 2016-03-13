<?php

class payment 
{
    public static $paymentwallIps = [
        '174.36.92.186',
        '174.36.92.187',
        '174.36.92.192',
        '174.36.96.66',
        '174.37.14.28',
    ];
	
	public static $paypalWcoinPackages = [
	    '200 Wcoin',
		'500 Wcoin',
		'1000 Wcoin',
		'2000 Wcoin',
		'5000 Wcoin'
	];

    public function getPaymentWallWidget($type, $username, $email, $transactionId) {
        if (!empty($username) && !empty($email)) {
            Paymentwall_Config::getInstance()->set([
                'api_type' => Paymentwall_Config::API_VC,
                'public_key' => CONFIG_PAYMENTWALL_PUBLIC_KEY,
                'private_key' => CONFIG_PAYMENTWALL_PRIVATE_KEY
            ]);

            $widget = new Paymentwall_Widget(
                $username, 
                'p10',
                [], 
                [
                    'email' => $email, 
                    'trans_id' => $transactionId]
            );
            if ('link' === $type) {
                return $widget->getUrl();
            } elseif ('widget' === $type) {
                return $widget->getHtmlCode();
            }
        }
        return '';
    }
	
	public function getUniquePaypalTransactionToken() {
		$token = common::generateRandomString();
        $cacheDb = new cacheDb();
        $loopControl = false;
        $loopRepeat = 0;
        while ($loopControl === false && 50 > $loopRepeat) {
            if (!empty($token) && true === $cacheDb->checkIfPaypalTransactionTokenIsAvailable($token)) {
                $loopControl = true;
            } else {
                $token = common::generateRandomNumber(PERSONAL_CODE_LENGTH);
            }
            $loopRepeat++;
        }
        return $token;
	}
	
	public function completePaypalTransactions($username) {
		$db = new db();
		$cacheDb = new cacheDb();
		$paypalIpns = $cacheDb->getPaypalIpnsPerUser($username);
		foreach ($paypalIpns as $paypalIpn) {
			$wcoinAmount = explode(' ', $paypalIpn['product']);
			if (true === $db->checkIfAccountHasWcoinRecord($paypalIpn['username'])) {
				//update add points
				$db->addWcoinsForAccount($paypalIpn['username'], $wcoinAmount[0]);
			} else {
				//create record
				$db->addAccountWcoinRecord($paypalIpn['username'], $wcoinAmount[0]);
			}
			$cacheDb->completePaypalTransaction($paypalIpn['token'], $paypalIpn['product']);
		}
	}
}