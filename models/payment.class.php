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
}