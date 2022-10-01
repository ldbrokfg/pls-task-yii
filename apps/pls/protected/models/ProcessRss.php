<?php

/**
 * @class      ProcessRss
 *
 * This is the data structure for login form data. It is used by the 'login' action of 'SiteController'.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */
class ProcessRss extends CModel {

	/** @var Feed $_feed */
	private $_feed;

	/**
	 * Declares the validation rules.
	 *
	 * @return array[]
	 */
	public function attributeNames() {
		return [
			'feed'
		];
	}
	
	public function setFeed(Feed $feed) {
		$this->_feed = $feed;
	}
	
	public function getFeed() {
		return $this->_feed;
	}
	
	/**
	 * Return an array of items
	 * @param  int
	 * @return array[]
	 */
	public function getLatestItems(int $amount=1) {
		$return = [];
		
		if (!empty($this->_feed)) {
			foreach ($this->_feed->item as $item) {
				$rssItem = new RssItem();
				$rssItem->loadItem($item);
				$return[] = $rssItem;
			}
			
			usort($return, function($a, $b){
				if ((int)$a->timestamp < (int)$b->timestamp) {
					return 1;
				}
				elseif ((int)$a->timestamp > (int)$b->timestamp){
					return -1;
				}
				else {
					return 0;
				}
			});
			
			if ($amount>0 && $amount<count($return)) {
				array_splice($return, $amount);
			}
		}
		
		return $return;
	}
}