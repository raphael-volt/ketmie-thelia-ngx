<?php

class XMLUtils
{
	/**
	 * @param unknown $str
	 * @return DOMElement
	 */
	public static function getDom($str)
	{
		$xml = simplexml_load_string($str);
		if($xml === false)
			return null;
		$xml = dom_import_simplexml($xml);
		$doc = $xml->ownerDocument;
		$doc->formatOutput = true;
		$doc->encoding = "utf-8";
		return $xml;
	}
	/**
	 * @param DOMDocument $doc
	 * @param DOMElement $parent
	 * @param unknown $str
	 * @return DOMNode new node
	 */
	public static function appendXML(DOMDocument $doc, DOMElement $parent, $str)
	{
		$node = simplexml_load_string($str);
		$node = dom_import_simplexml($node);
		$node = $doc->importNode($node, true);
		return $parent->appendChild($node);
	}
}