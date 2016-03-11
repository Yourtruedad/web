<?php

class payment 
{
    public function getPaymentWallWidget($username, $email, $transactionId) {
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
			return $widget->getUrl();
		}
		return '';
    }
}