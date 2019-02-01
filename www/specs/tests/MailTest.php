<?php
use PHPUnit\Framework\TestCase;

/**
 * MailTest test case.
 */
class MailTest extends TestCase
{

    /**
     *
     * @var mixed MailTest
     */
    private $mailTest;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated MailTestTest::setUp()
        
        $this->mailTest = new MailTest(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated MailTestTest::tearDown()
        $this->mailTest = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 
     */

    public function testConnect()
    {
        $user = new Client();
        $res = $user->charger("dev@ketmie.com", "dev1234");
        $this->assertEquals(1, $res);
        /*
        $postData = array(
            'email' => 'dev@ketmie.com',
            'motdepasse' => 'dev1234'
        );
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "http://localhost:80?fond=ajax/ajaxconnexion",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => false
        ));
        
        $output = curl_exec($ch);
        $this->assertNotFalse($output);
        curl_close($ch);
        */
    }
    
    /*
     * @depends testConnect
    public function testDisconnect()
    {
        //<a href="http://localhost:4400/?action=deconnexion">Déconnexion</a>
        $user = new Client();
        $res = $user->charger("dev@ketmie.com", "dev1234");
        $this->assertEquals(1, $res);
        $postData = array(
            'email' => 'dev@ketmie.com',
            'motdepasse' => 'dev1234'
        );
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "http://localhost:4400/?action=deconnexion",
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => false
        ));
        
        $output = curl_exec($ch);
        $this->assertNotFalse($output);
        curl_close($ch);
    }
     */

    private function curl_example()
    {
        $handle = curl_init();
        
        $url = "https://localhost/curl/theForm.php";
        
        // Array with the fields names and values.
        // The field names should match the field names in the form.
        
        $postData = array(
            'firstName' => 'Lady',
            'lastName' => 'Gaga',
            'submit' => 'ok'
        );
        
        curl_setopt_array($handle, array(
            CURLOPT_URL => $url,
            // Enable the post response.
            CURLOPT_POST => true,
            // The data to transfer with the response.
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true
        ));
        
        $data = curl_exec($handle);
        
        curl_close($handle);
        
        echo $data;
    }
}

