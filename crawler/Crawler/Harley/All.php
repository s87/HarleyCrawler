<?php

require_once('Quoka.php');
require_once('Motoscout24.php');
require_once('MotorradOnlineDe.php');

class Crawler_Harley_All
{
	protected $crawlers = array(
		'Crawler_Harley_Quoka',
		'Crawler_Harley_Motoscout24',
		'Crawler_Harley_KleinanzeigenEbayDe',
		'Crawler_Harley_MotorradOnlineDe',
		'Crawler_Harley_Motorradmarkt' );

	protected $result = array();
	
	public function getResult()
	{
		$this->result = array();
		foreach( $this->crawlers as $crawlerClass )
		{
			$mod = new $crawlerClass();
			$this->result = array_merge( $this->result, $mod->getResult() );
		}
		return $this->result;
	}
}
