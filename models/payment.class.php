<?php

class payment 
{
    public function getPaymentWallWidget() {
        Paymentwall_Config::getInstance()->set(array(
            'api_type' => Paymentwall_Config::API_VC,
            'public_key' => 't_4108e4214e854ec742196e1af48094',
            'private_key' => 't_cbcf72a060331d2174ede5f81ae177'
        ));

        $widget = new Paymentwall_Widget(
            'user40012', 
            'p4',
            array(), 
            array('email' => 'user@hostname.com', 'any_custom_parameter' => 'value')
        );
        return $widget->getHtmlCode();
    }
}