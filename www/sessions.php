<?php

require_once 'fonctions/autoload.php';
require_once 'client/config_thelia.php';
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/
function parseSessions($callback)
{
    $old = session_id();
    session_write_close();
    $tmp = "/tmp/";
    $files = scandir($tmp);
    foreach ($files as $f) {
        if (preg_match('/^sess_(.*)$/', $f, $data)) {
            $session_id = $data[1];
            session_id($session_id);
            session_start();
            $session = $_SESSION;
            call_user_func_array($callback, array(
                $session_id,
                $session,
                $tmp . $f
            ));
            session_commit();
        }
    }
}

function clearAllSessions()
{
    parseSessions(function ($id, $session, $file) {
        unlink($file);
    });
}
$result = array();

function logSessions()
{
    global $result;
    parseSessions(function ($id, $session, $file) {
        global $result;
        $result[] = array(
            $id,
            $session,
            $file
        ); // $session;
    });
    return $result;
}
$session_id = isset($_GET["id"]) ? $_GET["id"] : "";

$html = array();
$pre = array();
$sessions = array();

parseSessions(function ($i, $s, $f) {
    global $html;
    global $sessions;
    $sessions[$i] = $s;
    $html[] = <<<EOL
<form action="sessions.php" method="get">
<button name="card" type="submit">Card</button>
<input type="checkbox" name="map" value="1">
<button name="nav" type="submit">Nav</button>
<button name="customer" type="submit">Customer</button>
<button name="order" type="submit">Order</button>
<button name="clear" type="submit">Clear</button>
<label>{$i}</label>
<input type="hidden" name="id" value="{$i}">
</form>
EOL;
});
$sess = null;
$nav = null;
$card = null;
$j = null;
if (isset($sessions[$session_id])) {
    if(isset($_GET['clear'])) {
        session_id($session_id);
        session_start();
        
        // Détruit toutes les variables de session
        $_SESSION = array();
        
        // Si vous voulez détruire complètement la session, effacez également
        // le cookie de session.
        // Note : cela détruira la session et pas seulement les données de session !
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
                );
        }
        
        // Finalement, on détruit la session.
        session_destroy();
    }
    else {
        $sess = $sessions[$session_id];
        $nav = $sess["navig"];
        $nav instanceof Navigation;
        $j = new stdClass();
        $j->customer = new stdClass();
        $j = new stdClass();
        $j->card = new stdClass();
        $j->card->items = array();
        $j->order = new stdClass();
        $customer = $nav->client;
        $customer instanceof Client;
        if($customer)
        {
            /*
             foreach ($customer->bddvars as $key) {
             $j->customer->{$key} = $customer->{$key};
             }
             */
        }
        $c = $nav->panier;
        $c instanceof Panier;
        if($c) {
            $j->card->total = $c->total();
            // die("<pre>" . print_r($c));
            foreach ($c->tabarticle as $key => $i) {
                $i instanceof Article;
                $ci = new stdClass();
                $ci->product = new stdClass();
                $ci->quantity = $i->quantite;
                $ci->perso = $i->perso;
                /*
                 $p = $i->produit;
                 $p instanceof Produit;
                 foreach ($p->bddvars as $key) {
                 $ci->product->{$key} = $p->{$key};
                 }
                 */
                $j->card->items[] = $ci;
            }
        }
    }
    
}
if (isset($_GET["card"]) && $sess) {
    session_id($session_id);
    session_start();
    require_once 'client/plugins/filtreapi/CardController.class.php';
    $ctrl = new CardController();
    $map = isset($_GET['map']) ? $_GET['map']:0;
    $res = $ctrl->setRequestParameters("get", null, $map);
    $pre[] = json_encode($res->body, JSON_PRETTY_PRINT);//print_r($nav, true);
    session_commit();
}
if (isset($_GET["nav"]) && $j) {
    $pre[] = json_encode($j, JSON_PRETTY_PRINT);//print_r($nav, true);
}
if (count($pre))
    $pre = join("--------------" . PHP_EOL, $pre) . "--------------";
else
    $pre = "";
$html = join(PHP_EOL, $html);
$maxtime = ini_get("session.gc_maxlifetime");
$params = print_r(
    array(
        "cookie"=>session_get_cookie_params(),
        "maxtime"=>$maxtime
    )
    ,true);
echo <<<EOL
<html>
<body>
	<div>{$html}</div>
    <div>
        <h3>session_get_cookie_params</h3>
        <pre>{$params}</pre>    
    </div>
    <div>
        <pre>{$pre}</pre>
    </div>
</body>
</html>
EOL;
?>
