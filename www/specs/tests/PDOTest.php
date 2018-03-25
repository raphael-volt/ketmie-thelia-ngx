<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;

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
        echo PHP_EOL . "$n $i";
    }
}

