<?php

require_once('simpletest/browser.php');

class Crawler_Harley_KleinanzeigenEbayDe
{

	public function getTestPage()
	{
		return file_get_contents('testpages/kleinanzeigen.ebay.de.html');
	}
	
	public function getResult()
	{
		$items = array();
		$browser = &new SimpleBrowser();
		$page = $browser->get('http://kleinanzeigen.ebay.de/anzeigen/s-motorraeder-teile/harley/k0c222');
		//$page = $this->getTestPage();
		$dom = new DOMDocument();
		if( !@$dom->loadHTML($page) )
			exit("ERROR");
		
		//echo $page; 
		$xpath = new DOMXPath($dom);
		$rows = $xpath->query("//*/table[@id='srchrslt-adtable']/tr[@class='c-tr-adtble ']");
		foreach( $rows as $row )
		{
			$srcPath = $row->getNodePath();
			$url = '';
			$title = '';
			$price = '';
			$details = '';
			$linkItems = $xpath->query( $srcPath."/td/h2/a[@class='c-a-adtble-dscr ad-detail']");
			if( $linkItems && $linkItems->item(0) )
			{
				$url = 'http://kleinanzeigen.ebay.de'. $linkItems->item(0)->getAttribute('href');
				$title = (string)$linkItems->item(0)->nodeValue;
			}
			
			$piceItems = $xpath->query( $srcPath."/td[contains(@class, 'c-td-adtble-price')]");
			if( $piceItems && $piceItems->item(0) )
			{
				$price = (string)$piceItems->item(0)->nodeValue;
				$price = trim( strip_tags($price) );
			}

			$descItems = $xpath->query( $srcPath."/td[contains(@class,'c-td-adtble-dscr')]/p");
			if( $descItems && $descItems->item(0) )
			{
				$details = (string)$descItems->item(0)->nodeValue;
				$details = strip_tags($details);
			}

			$time = mktime();
			$items[] = new Harley_ResultItem( $title, $url, $price, $time, $details );
		}
		return $items;
	}
}

/*$crawler = new Crawler_Harley_KleinanzeigenEbayDe();
$result = $crawler->getResult();
echo "Gefundene: ".count($result)."\n";
*/
