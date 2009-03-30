<?php
    define( 'PAYPAL_TARGET', 'SANDBOX' );
    
    define( 'PAYPAL_API_VERSION', '52.0' );
    
    define( 'PAYPAL_SANDBOX_USER', "dionyz_1238332329_biz_api1.kamibu.com" );
    define( 'PAYPAL_SANDBOX_PWD', "1238332335" );
    define( 'PAYPAY_SANDBOX_SIGNATURE', "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AbVmegtai8HI2fHEzaU4hIaTLyEr" );
    
    define( 'PAYPAL_LIVE_USER', "dionyziz_api1.deviantart.com" );
    define( 'PAYPAL_LIVE_PWD', "E8ZZRB9M4GAVS7T8" );
    define( 'PAYPAY_LIVE_SIGNATURE', "AFcWxV21C7fd0v3bYYYRCpSSRl31ApfTPV8zLYH-drOM3QDzEES8FfM6" );
    
    define( 'PAYPAL_SANDBOX_URL', 'https://api-3t.sandbox.paypal.com/nvp' );
    define( 'PAYPAL_LIVE_URL', 'https://api-3t.paypal.com/nvp' );
    
    function PayPal_SetExpressCheckout( $amt, $returnurl, $cancelurl ) {
        $paymentaction = 'Sale';
        $method = 'SetExpressCheckout';
        
        PayPal_Perform( $user, $pwd, $signature, $version, $method, array( 'PaymentAction: ' . $paymentaction ) );
    }
    
    function PayPal_GetExpressCheckoutDetails() {
    }
    
    function PayPal_DoExpressCheckoutPayment() {
    }
    
    function PayPal_Perform( $method, $args ) {
        w_assert( is_string( $method ) );
        w_assert( !empty( $method ) );
        
        $user = PAYPAL_SANDBOX_USER;
        $pwd = PAYPAL_SANDBOX_PWD;
        $signature = PAYPAL_SANDBOX_SIGNATURE;
        
        $version = PAYPAL_API_VERSION;
        
        $curl = curl_init();
        $server = PAYPAL_SANDBOX_URL;
        $header = array();
        $data = array(
            'USER' => $user,
            'PWD' => $pwd,
            'SIGNATURE' => $signature,
            'VERSION' => $version,
            'METHOD' => $method
        );
        
        curl_setopt( $curl, CURLOPT_URL, $server );
        curl_setopt( $curl, CURLOPT_USERAGENT, "Zino/21290 <ads@zino.gr>" );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    }
?>
