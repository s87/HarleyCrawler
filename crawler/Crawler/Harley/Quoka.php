<?php

require_once('simpletest/browser.php');

class Crawler_Harley_Quoka
{

	public function getTestPage()
	{
		return file_get_contents('testpages/quoka.html');
	}
	
	public function getResult()
	{
		$items = array();
		$browser = &new SimpleBrowser();
		$page = $browser->get('http://www.quoka.de/motorraeder-nach-marken/harley-davidson/');
		//$page = $this->getTestPage();

		$dom = new DOMDocument();
		if( !@$dom->loadHTML($page) )
			exit("ERROR");
		
		//echo $page; 
		$xpath = new DOMXPath($dom);
		$todayDivs = $xpath->query("//*/div[@id='ResultListData']/div[@class='layoutsmall']/div/table/tr/td[@class='date']/div[@class='today']");
		foreach( $todayDivs as $todayDiv )
		{
			$time = '00:00';
			if( preg_match('/heute.*([\d]{1,2}\:[\d]{1,2})/i', $todayDiv->nodeValue, $matches) )
				$time = $matches[1];

			$tr = $todayDiv->parentNode->parentNode;				
			$srcPath = $tr->getNodePath();
	
			$url = '';
			$linkPath = $srcPath."/td[@class='detail']/a[@class='adheadline']";
			if( ( $linkItems = $xpath->query($linkPath) ) )
				$url = 'http://www.quoka.de/'.ltrim($linkItems->item(0)->getAttribute('href'),'/');
			
			$title = '';
			$titlePath = $srcPath."/td[@class='detail']/a[@class='adheadline']/div[@class='headline']";
			//echo $titlePath."\n";
			if( ( $titleItems = $xpath->query( $titlePath ) ) )
			{
				$title = (string)$titleItems->item(0)->nodeValue;
			}
			
			$price = '0.00';
			$pricePath = $srcPath."/td[@class='price']";
			if( ( $priceItems = $xpath->query( $pricePath ) ) )
			{
				$price = trim($priceItems->item(0)->nodeValue);
			}

			$details = '';
			$detailPath = $srcPath."/td[@class='detail']/span";
			if( ($detailItems = $xpath->query($detailPath) ) )
				$details = trim((string)$detailItems->item(0)->nodeValue);
			
			$items[] = new Harley_ResultItem( $title, $url, $price, $time, $details );
			 
		}

		return $items;
	}
}

/*$crawler = new HarleyCrawler_Quoka();
$result = $crawler->getResult();
echo "Gefundene: ".count($result)."\n";
*/
