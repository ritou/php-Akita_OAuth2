<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/GrantHandler.php';

// test class
class GrantHandler_Test 
    implements Akita_OAuth2_Server_GrantHandler
{
    public function __construct(){}
    public function handleRequest( $dataHandler ){
        return true;
    }
}

class Akita_OAuth2_Server_GrantHandler_Test extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $granthandler_test = new Granthandler_Test();
        $this->assertNotNull($granthandler_test);
        $this->assertTrue($granthandler_test->handleRequest(null));
    }
}
