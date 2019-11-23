<?php
use PHPUnit\Framework\TestCase;

function mysqlPassword($raw) {
    return '*'.strtoupper(hash('sha1',pack('H*',hash('sha1', $raw))));
}

class PDOTest extends TestCase
{

    public function testPDO_FetchObject()
    {
        $pdo = PDOHook::getPDO();
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
        $ctx = PDOHook::getCnx();
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
        $pdo = PDOHook::getPDO();
        $stmt = $pdo->prepare("SELECT id FROM administrateur WHERE identifiant = ? AND motdepasse = PASSWORD(?)");
        $stmt->bindParam(1, UserHook::$ADMIN_LOGIN);
        $stmt->bindParam(2, UserHook::$ADMIN_PWD);
        $stmt->execute();
        $this->assertGreaterThan(0, $stmt->rowCount());
    }
    
    /**
     *
     * @depends testBindPassword
     */
    public function testAdmin()
    {
        $user = UserHook::getDevAdmin();
        $this->assertNotNull($user->id);
    }
    
    /**
     *
     * @depends testAdmin
     */
    public function testUser()
    {
        $user = UserHook::getDevUser();
        $this->assertNotNull($user->id);
    }
    
    
}

