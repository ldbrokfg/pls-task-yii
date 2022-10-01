<?php

/**
 * @class      RssItem
 *
 * This is the data structure for login form data. It is used by the 'login' action of 'SiteController'.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */
class RssItem extends CModel {

	/** @var array[] $_attrs */
	private $_attrs = [];

	/**
	 * Returns the list of attribute names.
	 *
	 * @return array[]
	 */
	public function attributeNames() {
		return array_keys($this->_attrs);
	}
	
	public function loadItem(SimpleXMLElement $simpleXmlElement) {
		$this->_attrs = [];
		
		foreach ($simpleXmlElement as $k => $v) {
			$this->_attrs[$k] = $v;
		}
		
		if (array_key_exists('description', $this->_attrs) && !array_key_exists('shortDescription', $this->_attrs)) {
			$this->_attrs['shortDescription'] = $this->getShortDescription($simpleXmlElement);
		}
	}
	
	/**
	 * Return a shortened description
	 * @param  int
	 * @return false|string
	 */
	public function getShortDescription(SimpleXMLElement $simpleXmlElement) {
		$return = false;
		if (!array_key_exists('shortDescription', $this->_attrs)) {
			if (property_exists($simpleXmlElement, 'description')) {
				$return = trim($simpleXmlElement->description);
				if (property_exists($simpleXmlElement, 'link')) {
					$return = str_replace(' [&#8230;]', '... <a href="' . $simpleXmlElement->link . '" target="_blank">Read more</a>', $return);
				}
				$return = preg_replace('/The post.*appeared first on .*\./', '', $return);
				$return = trim($return);
			}
		}
		else {
			$return = $this->_attrs['shortDescription'];
		}
		
		return $return;
	}
	
	/**
	 * PHP getter magic method.
	 * @param string $name property name
	 * @return mixed property value
	 */
	public function __get($name) {
		if (isset($this->_attrs[$name])) {
			return $this->_attrs[$name];
		}
	}
}