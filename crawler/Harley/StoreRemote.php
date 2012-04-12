<?php

class Harley_StoreRemote
{
	protected $url = null;
	public function __construct($url)
	{
		$this->url = $url;
	}

	public function itemExists( $item )
	{
	
		return false;
	}
	
	public function addItems( $items )
	{
		if( count($items) < 1 )
			return true;

		$fields = array(
			'Module' => 'AddHarleys',
			'secret' => 'YourSecurityCodeHere'
		);
		$x = 0;
		foreach( $items as $harley )
		{
			$fields['harley['.$x.'][title]'] = urlencode($harley->getTitle());
			$fields['harley['.$x.'][url]'] = urlencode($harley->getUrl());
			$fields['harley['.$x.'][price]'] = urlencode($harley->getPrice());
			$fields['harley['.$x.'][timestamp]'] = urlencode($harley->getTimestamp());
			$fields['harley['.$x.'][details]'] = urlencode($harley->getDetails());
			$x++;
		}
		
		$fieldsString = '';
		foreach( $fields as $key=>$val )
			$fieldsString .= $key.'='.$val.'&';
		$fieldsString = rtrim($fieldsString,'&');
		
		$fields = rtrim($fieldsString,"&");
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$this->url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fieldsString);
		$result = curl_exec($ch);
		curl_close($ch);
	}
}
