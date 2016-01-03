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
		$browser = new SimpleBrowser();
		//$page = $browser->get('http://www.ebay-kleinanzeigen.de/s-motorraeder-roller/motorrad/anzeige:angebote/c305+motorraeder_roller.marke_s:harley+motorraeder_roller.type_s:motorrad');
		$page = $this->getTestPage();

		$dom = new DOMDocument();
		if( !@$dom->loadHTML($page) )
			exit("ERROR");

		$xpath = new DOMXPath($dom);
		$rows = $xpath->query("//*/article[@class='aditem']");
		foreach( $rows as $row )
		{
/*
<article class="aditem">
		<section class="aditem-image">
				<div  data-href="/s-anzeige/harley-davidson-fat-bob-fxdf/404599124-305-2678" data-imgsrc="http://i.ebayimg.com/00/s/NzY4WDEwMjQ=/z/G~0AAOSwUdlWgmz1/$_9.JPG" data-imgtitle="Harley Davidson Fat Bob FXDF Niedersachsen - Ilsede Vorschau" class="imagebox srpimagebox"></div>
										</section>
		<section class="aditem-main">
				<h2 class="text-module-begin"><a  href="/s-anzeige/harley-davidson-fat-bob-fxdf/404599124-305-2678">Harley Davidson Fat Bob FXDF</a></h2>
				<p>Verkaufe Fat Bob im gepflegten Zustand.
Farbe Vivid Black.
Absolut nicht verbastelt. Für den...</p>


				<p class="text-module-end">
				<span class="simpletag tag-small">2009</span>
				</p>

				</section>
		<section class="aditem-details">
				<strong>10.000 €</strong>
						<br>
				31241<br>
				Ilsede<br>
				</section>
		<section class="aditem-addon">
				<a class="lnk-action position-right-bottom" href="/s-motorraeder-roller/motorrad/anzeige:angebote/topAds/c305+motorraeder_roller.marke_s:harley+motorraeder_roller.type_s:motorrad">Alle Top-Anzeigen</a></section>
</article>
*/
			$url = '';
			$title = '';
			$price = '';
			$details = '';

			$titleLink = $xpath->query("section[@class='aditem-main']/h2[@class='text-module-begin']/a", $row);
			if( $titleLink->item(0) )
			{
				$url = $titleLink->item(0)->getAttribute('href');
				$title = $titleLink->item(0)->nodeValue;
			}

			$priceItem = $xpath->query("section[contains(@class,'aditem-details')]/strong", $row);
			$price = trim($priceItem->item(0)->nodeValue);

			$mainSection = $xpath->query("section[@class='aditem-main']", $row);
			$details = trim( $mainSection->item(0)->nodeValue );

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
