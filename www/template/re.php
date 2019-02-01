<?php 
// $values = explode("||", $match[2]);
$url = "http://thelia.ketmie.com/?fond=rubrique&amp;id_rubrique=1";
$m = array();
$url = preg_replace("/&amp;/", "&", $url);
$url = explode("?", $url);
$inputs = array();
if(count($url) > 1)
{
	$url = explode("&", $url[1]);
	if(count($url))
	{
		foreach ($url as $val)
		{
			$val = explode("=", $val);
			$n = $val[0];
			$val = $val[1];
			$inputs[] = <<<EOL
						<input type="hidden" name="$n" value="$val"/>
EOL;
		}
		return "<pre>" . implode("\n", $inputs);
	}
}
				
?>