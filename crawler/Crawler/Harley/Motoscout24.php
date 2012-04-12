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
		
		$browser->get('http://www.motoscout24.de/Public/Search.aspx');
		$browser->setField('framework1:Search:CtlMakeModel:ddlMake', '40');
		$browser->setField('framework1:Search:DdAge:ddlAge','1');
		$page = $browser->clickSubmitByName('framework1:Search:BtnSearch');
		
		if( !preg_match('/Suchergebnis/', $page ) )
		{
				echo "Fehler bei ".get_class($this)."\n";
echo $page;
				return array();
		}
//$browser->click('reporting bugs');
//$page = $browser->click('PHP 5 bugs only');
/*preg_match('/status=Open.*?by=Any.*?(\d+)<\/a>/', $page, $matches);
print $matches[1];*/
//echo $page;
//		$page = $this->getTestPage();
		$dom = new DOMDocument();
		if( !@$dom->loadHTML($page) )
			exit("ERROR");
		
		//echo $page; 
		$xpath = new DOMXPath($dom);
		$todayDivs = $xpath->query("//*/span[@id='listOutput']/*/div[@class='list-item']");
		foreach( $todayDivs as $todayDiv )
		{
			$srcPath = $todayDiv->getNodePath();

			$pricePath = $srcPath . "/*/div[@class='infoCar']/a";
			if( ($priceItems = $xpath->query($pricePath) ) )
			{
				$url = 'http://www.motoscout24.de';
				$url .= (string)$priceItems->item(0)->getAttribute('href');
			}	
			
			$pricePath = $srcPath . "/*/div[@class='infoCar']/a/div[@id='clicableBar']/div/span[1]";
			if( ($priceItems = $xpath->query($pricePath) ) )
				$price = trim((string)$priceItems->item(0)->nodeValue);
			
			$titlePath = $srcPath . "/*/div[@class='infoCar']/*/div[@class='headcar']/a";
			if( ($titleItems = $xpath->query($titlePath) ) )
				$title = trim((string)$titleItems->item(0)->nodeValue);
			
			$detailsPath = $srcPath . "/*/div[@class='infoCar']/*/div[@class='txt-car']/span";
			if( ($detailsItems = $xpath->query($detailsPath) ) )
				$details = trim((string)$detailsItems->item(0)->nodeValue);

			$time = mktime();
			$items[] = new Harley_ResultItem( $title, $url, $price, $time, $details );
		}
		return $items;
	}
}

