<?php

/**
 * @class      GdFeedItem
 *
 * This is the data structure for an RSS Feed item.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */
class GdFeedItem extends CModel {

	/** @var array[] $_item */
	private $_item = [];

	/**
	 * Returns the list of attribute names.
	 *
	 * @return array[]
	 */
	public function attributeNames() {
		return array_keys($this->_item);
	}

	/**
	 * Load an RSS item.
	 *
	 * @param  SimpleXMLElement $simpleXmlElement must be named "item"
	 * @return void
	 * @throws GdFeedItemException
	 */
	public function setItem(SimpleXMLElement $simpleXmlElement) {
		if (strtolower($simpleXmlElement->getName()) === 'item') {
			$this->_item = [];
			
			foreach ($simpleXmlElement as $v) {
				$this->_item[$v->getName()] = $v;
			}
			
			if (!array_key_exists('shortDescription', $this->_item)) {
				$this->_item['shortDescription'] = $this->createShortDescription($simpleXmlElement);
			}
			
			if (!array_key_exists('shortDescriptionNoReadMore', $this->_item)) {
				$this->_item['shortDescriptionNoReadMore'] = $this->createShortDescription($simpleXmlElement, false);
			}
		}
		else {
			throw new GdFeedItemException('Element name must be "item".');
		}
	}
	
	/**
	 * Set a shortened description.
	 *
	 * @param  SimpleXMLElement $simpleXmlElement
	 * @param  boolean $readMore
	 * @return string
	 */
	public function createShortDescription(SimpleXMLElement $simpleXmlElement, bool $readMore = true) {
		$return = '';
		
		if (
			!empty($simpleXmlElement->description) &&
			trim($simpleXmlElement->description) !== ''
		) {
			$return = trim($simpleXmlElement->description);
			
			if (
				$readMore === true &&
				!empty($simpleXmlElement->link) &&
				trim($simpleXmlElement->link) !== ''
			) {
				$return = str_replace(' [&#8230;]', '... <a href="' . trim($simpleXmlElement->link) . '" target="_blank">Read more</a>', $return);
			}
			else {
				$return = str_replace(' [&#8230;]', '...', $return);
			}
			
			$return = preg_replace('/The post.*appeared first on .*\./', '', $return);
			$return = trim($return);
		}
		
		return $return;
	}
	
	/**
	 * PHP getter magic method.
	 *
	 * @param string $name property name
	 * @return mixed property value
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->_item)) {
			return $this->_item[$name];
		}
	}
}

/**
 * An exception generated by GdFeedItem.
 */
class GdFeedItemException extends Exception
{
}