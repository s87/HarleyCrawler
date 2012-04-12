<?php

require_once('simpletest/browser.php');
require_once('Harley/ResultItem.php');

class Crawler_Harley_MotorradOnlineDe
{

	public function getTestPage()
	{
		return file_get_contents('testpages/motorradonline.de.html');
	}
	
	public function getResult()
	{
		$items = array();
		$browser = &new SimpleBrowser();
		$page = $browser->get('http://markt.motorradonline.de/gebrauchte-motorraeder-17-HARLEY');
		//$page = $this->getTestPage();

		$dom = new DOMDocument();
		if( !@$dom->loadHTML($page) )
			exit("ERROR");
		
		//echo $page; 
		$xpath = new DOMXPath($dom);
		$tableRows = $xpath->query("//*/table[@id='gebrauchte_suchergebnis']/tr");
		
		foreach( $tableRows as $tr )
		{
			$srcPath = $tr->getNodePath();
						
			$linkPath = $srcPath."/td[1]/a";
			$linkItems = $xpath->query($linkPath);
			if( !$linkItems )
				continue;
				
			$link = $linkItems->item(0);
			if( !$link || !$link->attributes)
				continue;

			$url = 'http://markt.motorradonline.de/';
			$url .= (string)$link->getAttribute('href');

			$title = (string)$linkItems->item(0)->getAttribute('title');

			$pricePath = $srcPath."/td[4]";
			$priceItems = $xpath->query( $pricePath );
			$price = trim((string)$priceItems->item(0)->nodeValue);

			$details = "";
			foreach( array(2,3,5,6) as $i )
			{
				$colPath = $srcPath."/td[".$i."]";
				$colItems = $xpath->query( $colPath );
				if( !$colItems->item(0) )
					continue;
				$val = $colItems->item(0)->nodeValue;
				$details .= trim((string)$val).', ';
			}
			$details = rtrim($details,', ');
			
			$time = mktime();

			$items[] = new Harley_ResultItem( $title, $url, $price, $time, $details );			 
		}

		return $items;
	}
}


/*$crawler = new Crawler_Harley_MotorradOnlineDe();
$result = $crawler->getResult();
echo "Gefundene: ".count($result)."\n";
*/
