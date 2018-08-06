<?php
/**
 * Example code for Shoprenter App
 * DO NOT USE IN PRODUCTION
 *
 *  This is the app's redirectURi
 *
 */

$shopname = $_GET['shopname'];
$code = $_GET['code'];
$timestamp = $_GET['timestamp'];
$hmac = $_GET['hmac'];
$clientId = '4f48c5c2b698028c7e9f164c';
$clientSecret = '13afd849ec2781ca3fb2dd1a';
$appId = 12;


/**
 * TODO validate the request sent by ShopRenter
 */

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
    CURLOPT_POST => 1,
    CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
    CURLOPT_SSL_VERIFYHOST => false,     // Disabled SSL Cert checks
    CURLOPT_POSTFIELDS => "client_id=".$clientId."&client_secret=".$clientSecret."&code=".$code."&timestamp=".$timestamp."&hmac=".$hmac
);

// Send requeest for API credentials
$ch      = curl_init( 'https://'.$shopname.'.shoprenter.hu/admin/oauth/access_credential' );
curl_setopt_array( $ch, $options );
$content = curl_exec( $ch );
$err     = curl_errno( $ch );
$errmsg  = curl_error( $ch );
$header  = curl_getinfo( $ch );
curl_close( $ch );

// Store credentials for this shop
file_put_contents($shopname.'auth.txt',$content);

// redirect to the app's interface 
header("Location: https://".$shopname.".shoprenter.hu/admin/app/".$appId );