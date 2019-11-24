<?php
use PHPUnit\Framework\TestCase;

class PanierTest extends TestCase
{
    /**
     * 
     * @var Panier
     */
    private static $_panier;
    /**
     * 
     * @var CommandHook
     */
    private static $hook;
    public static function setUpBeforeClass()
    {
        self::$hook = new CommandHook();
    }
    
    public static function tearDownAfterClass()
    {
        self::$hook = null;
        
    }
    
    function testHook() {
        $this->assertNotNull(self::$hook);
    }
    
    /**
     *
     * @depends testHook
     */
    public function testCatalog()
    {
           $catalog = self::$hook->getCatalog();
           $this->assertNotNull($catalog);
    }
    /**
     *
     * @depends testCatalog
     */
    public function testPanier()
    {
        
        $panier = new Panier();
        $products = self::$hook->findDeclinable(5);
        $total = 0;
        foreach ($products as $p) {
            $decs = $p->declinations;
            $i = rand(0, count($decs)-1);
            $dec = $decs[$i];
            $perso = new Perso();
            $perso->declinaison = BO_DECLINAISON;
            $total += floatval($dec->price);
            $perso->valeur = $dec->bo_carac;
            
            $panier->ajouter($p->ref, 1, array($perso));
        }
        $products = self::$hook->findSimple(5);
        foreach ($products as $p) {
            $total += floatval($p->prix);
            $panier->ajouter($p->ref, 1);
        }
        $this->assertEquals(10, $panier->nbart());
        $this->assertEquals($total, $panier->total());
        self::$_panier = $panier;
    }
    /**
     * 
     * @param mixed $type 1:paiement 2:transport
     * @return array|NULL|boolean|mixed
     */
    function getModules($type) {
        $modules = Modules::TABLE;
        
        $query = "select * from $modules where type='{$type}' and actif='1' $search order by classement";
        
        return CacheBase::getCache()->query($query);
    }
    /**
     *
     * @depends testPanier
     */
    public function testCommande()
    {
        $pdo = PDOHook::getPDO();
        $transports = $this->getModules(2);
        $paiements = $this->getModules(1);
        $user = UserHook::getDevUser();
        
        $s = $pdo->query("SELECT * FROM ". Adresse::TABLE . " WHERE client={$user->id}");
        $adresses = $s->fetchAll(PDO::FETCH_CLASS, Adresse::class);
        $navig = new Navigation();
        
        $panier = self::$_panier;
        $this->assertNotNull($panier);
        
        $navig->client = $user;
        $navig->panier = $panier;
        $navig->adresse = $adresses[0]->id;
        $navig->commande->paiement = $paiements[0]->id;
        $navig->commande->transport = $transports[0]->id;
        $navig->commande->adrfact = $adresses[0]->id;
        $navig->commande->adrlivr = $adresses[0]->id;
        $process = new ProcessPaiement();
        $process->save($navig, $navig->commande->paiement);
        $this->assertNotNull($process);
    }
    
    
}

