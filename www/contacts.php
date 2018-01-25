<?php 
if(isset($_GET["json"]) || true)
{
	function cryptoJsAesEncrypt($passphrase, $value){
		$salt = openssl_random_pseudo_bytes(8);
		$salted = '';
		$dx = '';
		while (strlen($salted) < 48) {
			$dx = md5($dx.$passphrase.$salt, true);
			$salted .= $dx;
		}
		$key = substr($salted, 0, 32);
		$iv  = substr($salted, 32,16);
		$encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
		$data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
		return json_encode($data);
	}
	include_once 'client/config_thelia.php';
	$dsn = "mysql:host=" . THELIA_BD_HOST . "; dbname=". THELIA_BD_NOM . ";";
	$pdo = new PDO($dsn, THELIA_BD_LOGIN, THELIA_BD_PASSWORD);
	$q = "SELECT valeur FROM variable WHERE nom='passphrase'";
	$q= $pdo->query($q);
	$q->setFetchMode(PDO::FETCH_COLUMN, 0);
	$passphrase = $q->fetch();
	$data = new stdClass();
	$data->raphael = new stdClass();
	$data->stephanie = new stdClass();
	$data->raphael->email = cryptoJsAesEncrypt($passphrase, "raphael@ketmie.com");
	$data->raphael->phone = cryptoJsAesEncrypt($passphrase, "0674654493");
	$data->stephanie->email = cryptoJsAesEncrypt($passphrase, "stephanie@ketmie.com");
	$data->stephanie->phone = cryptoJsAesEncrypt($passphrase, "0674654493");
	
	die(json_encode($data));
}
?>