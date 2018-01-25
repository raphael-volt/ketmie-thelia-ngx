<?php
/**
 * Plugin TinyMCE 4 Thelia 1.5
 *
 * @package	Thelia
 * @author	Benoit Asselin <contact@ab-d.fr>
 * @version	Tinymce4.class.php, 2014/01/21
 * @link	http://www.ab-d.fr/
 *
 */


//error_reporting(E_ALL);
//ini_set('display_errors', true);


include_once(realpath(dirname(__FILE__)) . '/../../../classes/PluginsClassiques.class.php');
include_once(realpath(dirname(__FILE__)) . '/../../../classes/Variable.class.php');


/**
 * Plugin TinyMCE 4 Thelia 1.5
 */
class Tinymce4 extends PluginsClassiques {
	
	/**
	 * Nom du module
	 * @return string
	 */
	const MODULE = 'tinymce4';
	
	/**
	 * Version du module
	 * @return string
	 */
	const VERSION = '1.0.0';
	
	
	
	/**
	 * Constructeur de la classe
	 */
	public function __construct() {
		parent::__construct(self::MODULE);
		
	}
	
	/**
	 * Pages autorisees a utiliser TinyMCE
	 * @param array $pages_autorisees
	 * @return bool
	 */
	public static function controle_acces($pages_autorisees) {
		$page = array();
		$return = (preg_match('/([^\/]+).php/', $_SERVER['PHP_SELF'], $page) && in_array($page[1], $pages_autorisees));
		if($return) {
			// cf: ./filemanager/config/config.php
			$_SESSION[self::MODULE] = $_SESSION['util']->id;
		}
		return $return;
	}
	
	/**
	 * Appeler dans les fichiers: ./tinymce4_admin_title.php ; ./filemanager/config.php 
	 * @return array
	 * @see extract()
	 */
	public static function pre_config() {
		if(!isset($_SESSION['util'], $_SESSION[self::MODULE])) { die('ERROR 403'); }
		
		$urlsite = new Variable();
		$urlsite->charger('urlsite');
		$baseurl = rtrim('/' . preg_replace('/https?:\/\/[^\/]+\/?/', '', $urlsite->valeur), '/') . '/';
		
		$style_chem = new Variable();
		$style_chem->charger('style_chem');
		
		return array(
			'thelia_path' => '../client/plugins/'.self::MODULE.'/',
			'thelia_urlsite' => $urlsite->valeur,
			'thelia_baseurl' => $baseurl,
			'thelia_utilisateur' => 'client/gfx/utilisateur/',
			'thelia_styles' => ($urlsite->valeur && $style_chem->valeur ? $urlsite->valeur . $style_chem->valeur : ''),
			);
	}
	
}


