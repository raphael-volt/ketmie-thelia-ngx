<?php
use PHPUnit\Framework\TestCase;

class PanierTest extends TestCase
{
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
}

