<?php
include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");

class DecliPrix extends PluginsClassiques
{

	public $id;
	public $id_declidisp;
	public $prix;
	public $ref;
	public $etat;

	const TABLE = 'decliprix';

	public $table = self::TABLE;

	public $bddvars = array("id", "id_declidisp", "prix", "ref", "etat");

	public function __construct()
	{
		parent::__construct("decliprixs");
	}

	public function charger($id, $lang=null)
	{
		return $this->getVars("select * from $this->table where id=\"$id\"");
	}
	
	public function valider($id)
	{
		return $this->query("UPDATE $this->table SET etat=1 WHERE id=$id");
	}

	public function init()
	{
		$query_decliprixs = "CREATE TABLE `" . self::TABLE . "` (
			  `id` int(11) NOT NULL auto_increment,
			  `id declidisp` int(11) text NOT NULL,
			  `prix` DECIMAL(8,2) DEFAULT,
			  `ref` text NOT NULL,
			  `etat` tinyint(4) NOT NULL,
			  PRIMARY KEY  (`id`)
			) AUTO_INCREMENT=1 ;";

		$resul_decliprixs = $this->query($query_decliprixs);
	}

	public function destroy()
	{ }

	public function boucle($texte, $args)
	{

		$search = $order = $res = "";

		// récupération des arguments

		$id_declidisp = lireTag($args, "id_declidisp");
		$prix = lireTag($args, "prix");
		$ref = lireTag($args, "ref");
		$ordre = lireTag($args, "ordre");
		$nofiltre = lireTag($args, "nofiltre");
		$noboucle = lireTag($args, "noboucle");

		// préparation de la requête
		if ($ref != "")  $search .= " and ref=\"$ref\"";
		if ($nofiltre != "true") $search .= " and etat=1";

		switch ($ordre) {
			default:
				$order = "";
				break;
		}

		$query_decliprixs = "select * FROM $this->table where 1 $search $order";

		$resul_decliprixs = $this->query($query_decliprixs);

		if ($resul_decliprixs) {

			$nbres = $this->num_rows($resul_decliprixs);

			if ($nbres > 0 && $noboucle != "true") {

				while ($row = $this->fetch_object($resul_decliprixs)) {

					$temp = $texte;

					$temp = str_replace("#PRIX", $row->prix, $temp);
					$temp = str_replace("#REF", $row->ref, $temp);

					$res .= $temp;
				}
			} else if ($noboucle == "true") {

				$texte = str_replace("#NBRE", $nbres, $texte);
				$res = $texte;
			}
		}

		return $res;
	}

	public function action()
	{
		$action = $_POST['action'];
		switch ($action) {
			case 'ajdecliprix':
				$this->ajdecliprix();
				# code...
				break;
		}
	}
	private function ajdecliprix()
	{
		$error = false;

		$tmp_array = array("decliprix_id_declidisp", "decliprix_prix", "decliprix_ref", "ref");

		foreach ($tmp_array as $key) {
			if (empty($_POST[$key])) {
				$error = true;
				break;
			}
		}

		if (!$error) {
			$decliprix = new DecliPrix();
			$decliprix->prix = $_POST['decliprix_prix'];
			$decliprix->ref = $_POST['decliprix_ref'];
			$decliprix->id_declidisp = $_POST['decliprix_id_declidisp'];
			
			$decliprix->add();

			$cache = new Cache();
			$cache->vider("DECLIPRIX", "%");
		}
	}
}
