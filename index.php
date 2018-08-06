<?php
/**
 * Example code for Shoprenter App
 * DO NOT USE IN PRODUCTION
 */

$shopname = $_GET['shopname'];
$code = $_GET['code'];
$timestamp = $_GET['timestamp'];
$hmac = $_GET['hmac'];
$clientId = '4f48c5c2b698028c7e9f164c';
$clientSecret = '13afd849ec2781ca3fb2dd1a';
$redirectUri = 'http://exmple.com/auth.php';


/**
 * TODO validate the request sent by ShopRenter
 */
if (!$hmac){
    // validation failed
    echo 'Hello! Iam an app for listing orders. I only work within ShopRenter';
}else{

    if (!is_file($shopname.'auth.txt')){
        // API credentials are missing something bad happend we must re-request them.

        $location = 'https://'.$shopname.'.shoprenter.hu/admin/oauth/authorize?client_id='.$clientId.'&redirect_uri='.$redirectUri;
        header('Location: '.$location);
        exit;

    }else{

        // Everything fine list the orders from this store

        $cred = json_decode(file_get_contents($shopname.'auth.txt'));

        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            CURLOPT_SSL_VERIFYHOST => false,     // Disabled SSL Cert checks
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $cred->username .':'.$cred->password,
            CURLOPT_HTTPHEADER => array("Accept: application/json")
        );


        $ch      = curl_init( 'http://'.$shopname.'.api.shoprenter.hu/orders' );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );
        $orders = json_decode($content);

        echo '<h1 style="color: white">Last Orders</h1>';

        foreach ($orders->items as $item) {
            $ch      = curl_init( $item->href );
            curl_setopt_array( $ch, $options );
            $content = curl_exec( $ch );
            curl_close( $ch );
            $order = json_decode($content);

            echo '<div style="color: white">Name:'.$order->firstname.' '.$order->lastname.' Total:'.$order->total.'</div><br>';
        }
    }
}
