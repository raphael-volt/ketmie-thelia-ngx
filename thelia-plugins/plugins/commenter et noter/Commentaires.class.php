<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");

	class Commentaires extends PluginsClassiques{

		public $id;
		public $nom;
		public $message;
		public $note;
		public $ref;
		public $etat;
		public $date;

		const TABLE = 'commentaires';

		public $table = self::TABLE;

		public $bddvars = array("id", "nom", "message", "note", "ref", "etat", "date");

		public function __construct(){
			parent::__construct("commentaires");
		}

		public function charger($id){
			return $this->getVars("select * from $this->table where id=\"$id\"");
		}

		public function valider($id){
			return $this->query("UPDATE $this->table SET etat=1 WHERE id=$id");
		}

		public function init(){
			$query_commentaires = "CREATE TABLE `".self::TABLE."` (
			  `id` int(11) NOT NULL auto_increment,
			  `nom` text NOT NULL,
			  `message` text NOT NULL,
			  `note` tinyint(4) NOT NULL,
			  `ref` text NOT NULL,
			  `etat` tinyint(4) NOT NULL,
			  `date` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) AUTO_INCREMENT=1 ;";

			$resul_commentaires = $this->query($query_commentaires);
		}

		public function destroy(){
		}

		public function boucle($texte, $args){

			$search = $order = $res = "";

			// récupération des arguments
			$ref = lireTag($args, "ref");
			$ordre = lireTag($args, "ordre");
			$nofiltre = lireTag($args, "nofiltre");
			$noboucle = lireTag($args, "noboucle");

			// préparation de la requête
			if ($ref != "")  $search .= " and ref=\"$ref\"";
			if ($nofiltre != "true") $search .= " and etat=1";

			switch($ordre){
				case "decroissant":
					$order = "ORDER BY date DESC";
					break;

				case "croissant":
					$order = "ORDER BY date ASC";
					break;

				default :
					$order = "ORDER BY date DESC";
					break;
			}

			$query_commentaires = "select * FROM $this->table where 1 $search $order";
			$query_commentaires_avg = "select AVG(note) as moyenne FROM $this->table WHERE 1 $search";

			$resul_commentaires = $this->query($query_commentaires);
			$resul_commentaires_avg = $this->query($query_commentaires_avg);

			if ($resul_commentaires && $resul_commentaires_avg) {

				$nbres = $this->num_rows($resul_commentaires);
				$moyenne = round($this->fetch_object($resul_commentaires_avg)->moyenne);

				if ($nbres > 0 && $noboucle != "true") {

					while ($row = $this->fetch_object($resul_commentaires)){

						$temp = $texte;

						$temp = str_replace("#NOM", $row->nom, $temp);
						$temp = str_replace("#MESSAGE", $row->message, $temp);
						$temp = str_replace("#NOTE", $row->note, $temp);
						$temp = str_replace("#DATE", date("d-m-Y", $row->date), $temp);
						$temp = str_replace("#HEURE", date("H:i", $row->date), $temp);

						$res .= $temp;
					}
				}
				else if ($noboucle == "true"){

					$texte = str_replace("#NBRE", $nbres, $texte);
					$texte = str_replace("#MOYENNE", $moyenne, $texte);

					$res = $texte;
				}
			}

			return $res;
		}

		public function action(){
			if($_POST['action'] == "ajcommentaire"){
				$error = false;

				$tmp_array = array("commentaire_nom", "commentaire_message", "commentaire_note", "commentaire_ref", "ref");

				foreach($tmp_array as $key) {
					if(empty($_POST[$key])) {
						$error = true;
						break;
					}
				}

				if(!$error){
					$commentaire = new Commentaires();
					$commentaire->nom = $_POST['commentaire_nom'];
					$commentaire->message = $_POST['commentaire_message'];
					$commentaire->note = $_POST['commentaire_note'];
					$commentaire->ref = $_POST['commentaire_ref'];
					$commentaire->date = time();

					$commentaire->add();

					$cache = new Cache();
					$cache->vider("COMMENTAIRES", "%");
				}
			}
		}
	}
?>