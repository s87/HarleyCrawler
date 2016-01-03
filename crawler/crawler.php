<?php

ini_set('include_path', dirname(__FILE__).':'.ini_get('include_path'));

require_once('autoloader.php');
include_once('Harley/StoreRemote.php');
include_once('Crawler/Harley/All.php');

//$crawler = new Crawler_Harley_All();
// $crawler = new Crawler_Harley_Quoka();
$crawler = new Crawler_Harley_KleinanzeigenEbayDe();
$result = $crawler->getResult();
var_dump($result);

exit("Exit in crawler.php");
//$store = new Harley_StoreRemote('http://example.org/harley/index.php');
// Traffic saver
$store = new Harley_StoreLocal('/tmp/HarleyCrawlerSeen.txt');
$filtered = $store->getNewItems( $result );
echo "Online ".count($result)." / Neue ".count($filtered)."\n";
if( count($filtered) > 0 )
{
	$store->addItems( $filtered );
}
