<?php

ini_set('include_path', dirname(__FILE__).':'.ini_get('include_path'));

require_once('autoloader.php');

$crawler = new Crawler_Harley_KleinanzeigenEbayDe();
$result = $crawler->getResult();
echo "Gefundene-Online: ".count($result)."\n";
if( count($result) < 0 )
	exit;

foreach( $result as $item )
	echo $item->getTitle()."\n";

/*$store = new Harley_StoreLocal('seen.txt');
$filtered = $store->getNewItems( $result ); 
echo "New ".count($filtered)."\n";
$store->addItems( $filtered );
*/
