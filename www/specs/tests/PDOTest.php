<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;

function mysqlPassword($raw) {
    return '*'.strtoupper(hash('sha1',pack('H*',hash('sha1', $raw))));
}

class PDOTest extends TestCase
{

    public function testPDO_FetchObject()
    {
        $ctx = new Cnx();
        $pdo = $ctx->getPDO();
        $this->assertNotNull($pdo);
        $stmt = $pdo->query("select * from variable");
        $n = $stmt->rowCount();
        $i = 0;
        while ($row = $stmt->fetchObject(stdClass::class)) {
            $i ++;
        }
        
        $this->assertGreaterThan(0, $i);
        $this->assertEquals($n, $i);
    }

    /**
     *
     * @depends testPDO_FetchObject
     */
    public function testCNX_FetchObject()
    {
        $ctx = new Cnx();
        $stmt = $ctx->query("select * from variable");
        $n = $stmt->rowCount();
        $i = 0;
        while ($row = $ctx->fetch_object($stmt, Variable::class)) {
            $i ++;
        }
        $this->assertGreaterThan(0, $i);
    }
    /**
     *
     * 
    public function testBindPassword()
    {
        $ctx = new Cnx();
        $pdo = $ctx->getPDO();
        // SELECT * FROM `administrateur` WHERE motdepasse = PASSWORD('devadmin') AND identifiant = 'devadmin'
        $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE motdepasse = PASSWORD(:pwd) AND identifiant = :id");
        $stmt->bindParam(":id", "devadmin", PDO::PARAM_STR);
        $stmt->bindParam(":pwd", "devadmin", PDO::PARAM_STR);
        $stmt->execute();
        $admin = null;
        if($stmt->rowCount()) {
            $admin = $stmt->fetch(PDO::FETCH_CLASS, Administrateur::class);
        }
        $this->assertNotNull($admin);
    }
     */
    /**
     *
     * @depends testCNX_FetchObject
     */
    public function testBindPassword()
    {
        $ctx = new Cnx();
        $pdo = $ctx->getPDO();
        $devadmin = "devadmin";
        
        $cur = new stdClass();
        $cur->pwd = $devadmin;
        $cur->login = $devadmin;
        
        $stmt = $pdo->prepare("SELECT id FROM administrateur WHERE identifiant = ? AND motdepasse = PASSWORD(?)");
        $stmt->bindParam(1, $cur->login);
        $stmt->bindParam(2, $cur->pwd);
        $stmt->execute();
        $this->assertGreaterThan(0, $stmt->rowCount());
        
        $crypt = mysqlPassword($devadmin);
        $stmt = $pdo->prepare("SELECT id FROM administrateur WHERE identifiant = ? AND motdepasse = ?");
        $stmt->bindParam(1, $devadmin);
        $stmt->bindParam(2, $crypt);
        $stmt->execute();
        $this->assertGreaterThan(0, $stmt->rowCount());
    }
    
    
    
}

