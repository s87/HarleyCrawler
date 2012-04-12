<?php

/**
 * Class for a result item. Use it for Harley_Store objects
 *
 * @author holgerg
 *
 */
class Harley_ResultItem
{
		public function __construct( $title, $url, $price, $date, $details=null )
		{
				$this->title=(string)$title;
				$this->price = (string)$price;
				$this->details = (string)$details;
				$this->date = (string)$date;
				$this->url = (string)$url;
		}
		
		public function getId()
		{
			return $this->url;
		}
		
		public function getTitle()
		{
				return $this->title;
		}
		
		public function getPrice()
		{
			return $this->price;
		}
		
		public function getUrl()
		{
			return $this->url;
		}
		
		public function getDetails()
		{
			return $this->details;
		}
		
		public function getTimestamp()
		{
			return mktime();
		}
}
