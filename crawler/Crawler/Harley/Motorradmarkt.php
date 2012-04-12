<?php

require_once('simpletest/browser.php');

class Crawler_Harley_Motorradmarkt
{

	public function getTestPage()
	{
		return file_get_contents('testpages/motorradmarkt.html');
	}
	
	public function getResult()
	{
		$items = array();
		$browser = &new SimpleBrowser();
		$page = $browser->get('http://www.motorradmarkt.de/suchen/search-bikes/f/ADS_ADS.CREATED/s/DESC/cc/DE/bike/34/model//state/0/type/0/zip//circuit/0/regmin/0/regmax/0/milmin/0/milmax/0/pmin/0/pmax/0/cubiccapacities/0/vat_reclaimable/false/account/0');
		//$page = $this->getTestPage();
		$dom = new DOMDocument();
		if( !@$dom->loadHTML($page) )
			exit("ERROR");
		
		//echo $page; 
		$xpath = new DOMXPath($dom);
		$rows = $xpath->query("//*/div[@id='content']/div[contains(@class, 'resultrow')]");
		foreach( $rows as $row )
		{
			$srcPath = $row->getNodePath();

			$url = '';
			$title = '';
			$linkItems = $xpath->query( $srcPath."/div[@class='itemtext']/*/a");
			if( $linkItems && $linkItems->item(0) )
			{
				$url = 'http://www.motorradmarkt.de'. $linkItems->item(0)->getAttribute('href');
				$title = (string)$linkItems->item(0)->nodeValue;
			}
			
			$price = '';
			$priceItems =  $xpath->query( $srcPath."/div[@class='itemprice']");
			$price = str_replace("\n",'',trim( $priceItems->item(0)->nodeValue ));

			$details = '';			
			$time = mktime();
			$items[] = new Harley_ResultItem( $title, $url, $price, $time, $details );
		}
		return $items;
	}
}

/*$crawler = new HarleyCrawler_Quoka();
$result = $crawler->getResult();
echo "Gefundene: ".count($result)."\n";
*/
