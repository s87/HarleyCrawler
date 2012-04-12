<?php

/**
 * Class to save traffic. Stores url in local file and returns 
 * new items through method getNewItems()
 *
 * @author holgerg
 *
 */
class Harley_StoreLocal
{
	protected $storeFile = '';
	/**
	 * Constructor
	 * 
	 * @param string $storeFile path to file like "store.txt"
	 */
	public function __construct( $storeFile )
	{
		$this->storeFile = $storeFile;
		if( !is_file($this->storeFile) )
			file_put_contents($this->storeFile, '');
	}

	/**
	 * Add items permanent to this store
	 *
	 * @param array $result with Harley_ResultItem objects
	 */
	public function addItems( $result )
	{
		$fh = fopen($this->storeFile, 'a+');
		foreach( $result as $item )
			fwrite($fh, $item->getUrl()."\n");
		fclose($fh);
		return $this;
	}
	
	/**
	 * Give an array with Harley_ResultItems - it will return the unseen items
	 *
	 * @param array $result
	 */
	public function getNewItems( $result )
	{
		$seenUrls = array();
		$fh = fopen( $this->storeFile, "r" );
		while( $line = fgets($fh))
		{
			$line = trim($line);
			$seenUrls[] = $line;
		}
		fclose($fh);
		$newItems = array();
		foreach( $result as $item )
		{
			if( in_array( $item->getUrl(), $seenUrls ) === false )
				$newItems[] = $item;
		}
		return $newItems;		
	}
}