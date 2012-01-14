<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/Error.php';

class Akita_OAuth2_Server_Error_Test extends PHPUnit_Framework_TestCase
{
    public function testError()
    {
        try{
            throw new Akita_OAuth2_Server_Error('400', 'invalid_request');
        }catch(Akita_OAuth2_Server_Error $e){
            $this->assertEquals('400', $e->getOAuth2Code(), 'Invalid Code');
            $this->assertEquals('invalid_request', $e->getOAuth2Error(), 'Invalid Error');
            $this->assertNull($e->getOAuth2ErrorDescription(), 'Invalid Error Description(null is not returned)');
            $this->assertNull($e->getOAuth2ErrorUri(), 'Invalid Error Uri(null is not returned)');
        }
    }

    public function testErrorAndErrorDescription()
    {
        try{
            throw new Akita_OAuth2_Server_Error('400', 'invalid_request', 'test description');
        }catch(Akita_OAuth2_Server_Error $e){
            $this->assertEquals('400', $e->getOAuth2Code(), 'Invalid Code');
            $this->assertEquals('invalid_request', $e->getOAuth2Error(), 'Invalid Error');
            $this->assertEquals('test description', $e->getOAuth2ErrorDescription(), 'Invalid Error Description');
            $this->assertNull($e->getOAuth2ErrorUri(), 'Invalid Error Uri(null is not returned)');
        }
    }

    public function testErrorAndErrorDescriptionAndErrorUri()
    {
        try{
            throw new Akita_OAuth2_Server_Error('400', 'invalid_request', 'test description', 'http://example.com/error_description');
        }catch(Akita_OAuth2_Server_Error $e){
            $this->assertEquals('400', $e->getOAuth2Code(), 'Invalid Code');
            $this->assertEquals('invalid_request', $e->getOAuth2Error(), 'Invalid Error');
            $this->assertEquals('test description', $e->getOAuth2ErrorDescription(), 'Invalid Error Description');
            $this->assertEquals('http://example.com/error_description',$e->getOAuth2ErrorUri(), 'Invalid Error Uri');
        }
    }
}
