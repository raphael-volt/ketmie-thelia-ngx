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
     * @depends testCNX_FetchObject
     */
    public function testBindPassword()
    {
        $ctx = new Cnx();
        $pdo = $ctx->getPDO();
        $devadmin = "devadmin";
        
        $cur = new stdClass();
        $cur->pwd = "dev1234";
        $cur->login = $devadmin;
        
        $stmt = $pdo->prepare("SELECT id FROM administrateur WHERE identifiant = ? AND motdepasse = PASSWORD(?)");
        $stmt->bindParam(1, $cur->login);
        $stmt->bindParam(2, $cur->pwd);
        $stmt->execute();
        $this->assertGreaterThan(0, $stmt->rowCount());
    }
    
    
    
}

