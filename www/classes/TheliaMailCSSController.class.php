<?php
class TheliaMailCSSController
{
	/*
	private static $styles = array(
			"mail-body"=>"background-color: #FFFFFF; color:#333333; width:480px; text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-size-adjust:none; padding:8px;",
			"mail-header"=>"margin:0; padding:0",
			"mail-title"=>"font-size:16px; font-weight:bold; margin:8px 0 4px 0;",
			"table-base"=>array(
					"border-collapse: collapse; border:1px solid #333333; margin:0 0 8px 0; padding:0; border-spacing:0px",
					array("//caption","text-align:left; white-space:nowrap; font-size:14px; margin:0 0 4px 0"),
					array("//td","font-weight:bold;border-collapse: collapse; border:1px solid #333333; margin:0 0 8px 0; padding:0; border-spacing:0px"),
					array("//td[@class='right']","border-collapse: collapse; border:1px solid #333333; margin:0 0 8px 0; padding:0; border-spacing:0px; font-weight:initial; text-align:right; vertical-align:top;")
			),
			"default"=>"margin:0; padding:0;",
			"mail-span"=>"color:#333333",
			"link"=>"color:inherit;outline: 0px none;text-decoration:underline;"
	);
	 */
	private static $styles = array(
			"mail-body"=>"background-color: #FFFFFF; color:#333333; width:480px; text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-size-adjust:none; padding:8px;",
			"mail-header"=>"margin:0; padding:0",
			"mail-title"=>"font-size:16px; font-weight:bold; margin:8px 0 4px 0;",
			"table-base"=>array(
					"border:1px solid #333333; margin:0 0 8px 0; padding:0; border-spacing:4px",
					array("//caption","text-align:left; white-space:nowrap; font-size:14px; margin:0 0 4px 0"),
					array("//td","font-weight:bold"),
					array("//td[@class='right']","font-weight:initial; text-align:right; vertical-align:top;")
			),
			"default"=>"margin:0 0 5px 0; padding:0;",
			"mail-span"=>"color:#333333",
			"link"=>"color:inherit;outline: 0px none;text-decoration:underline;"
	);
	
	public static function addStyle($class, $styleObject)
	{
		self::$styles[$class] = $styleObject;
	}
	
	public static function classToInlineCSS($template)
	{
		$template = simplexml_load_string($template);
		$template = dom_import_simplexml($template);
		$doc = $template->ownerDocument;
		$xp = new DOMXPath($doc);
		
		foreach (self::$styles as $class => $style)
		{
			$elements = $xp->query("//*[@class='$class']", $template);
			if(is_array($style))
			{
				for ($i = 0; $i < $elements->length; $i++)
				{
					for ($k = 0; $k < count($style); $k++)
					{
						if(is_array($style[$k]))
						{
							$children = $xp->query($style[$k][0], $elements->item($i));
							for ($j = 0; $j < $children->length; $j++)
								$children->item($j)->setAttribute("style", $style[$k][1]);
						}
						else
							$elements->item($i)->setAttribute("style", $style[$k]);
					}
				}
			}
			else
			{
				for ($i = 0; $i < $elements->length; $i++)
					$elements->item($i)->setAttribute("style", $style);
			}
		}
		$elements = $xp->query("//*[@class]", $template);
		for ($i = 0; $i < $elements->length; $i++)
			$elements->item($i)->removeAttribute("class");

		return $doc->saveXML($template);
	}
	
}