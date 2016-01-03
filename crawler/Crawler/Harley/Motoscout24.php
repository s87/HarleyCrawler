<?php

require_once('simpletest/browser.php');
require_once('Harley/ResultItem.php');
require_once('Harley/StoreRemote.php');

class Crawler_Harley_Motoscout24
{

	public function getTestPage()
	{
		return file_get_contents('testpages/motoscout24.html');
	}

	public function getResult()
	{
		$items = array();
		$browser = &new SimpleBrowser();
//http://www.autoscout24.de/Moto/ListGN.aspx?atype=B&mmvmk0=50040&mmvco=1&make=50040&pricefrom=500&cy=D&ustate=N%2CU&fromhome=1&results=80&desc=1&sort=age&page=1
//http://ww4.autoscout24.de/?atype=B&make=50040&mmvmk0=50040&mmvco=1&fregto=1993&cy=D&powerscale=KW&zipc=D&ustate=N,U&sort=age&desc=1&results=20&page=1&event=sort&dtr=s

		$url = 'http://ww4.autoscout24.de/?atype=B&make=50040&mmvmk0=50040&mmvco=1&fregto=1993&cy=D&powerscale=KW&zipc=D&ustate=N,U&sort=age&desc=1&results=20&page=1&event=sort&dtr=s';
		$page = $browser->get($url);
		//$page = $this->getTestPage();
		if( !preg_match('/Suchergebnis/', $page) )
		{
				echo "Fehler bei ".get_class($this)."\n";
				return array();
		}
		// extract the js articlesarry from page
		preg_match('/.*var articlesFromServer = (.*)/', $page, $matches);
		$jsArray = str_replace(' || [];', '',$matches[1]);
		$result = json_decode($jsArray);
		//echo $jsArray;
		foreach( $result as $item )
		{
			$title = $item->md.' Bj. '.$item->fr;
			$price = $item->pp;
			$time = mktime();
			$itemId = $item->ei; // like 282282813
			$url = 'http://ww3.autoscout24.de/classified/'.$itemId.'?asrc=st|as';
			$details = $item->ct.', '.$item->zp;
			$items[] = new Harley_ResultItem( $title, $url, $price, $time, $details );
		}
		return $items;

	}
}
