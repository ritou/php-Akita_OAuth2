<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AccessToken.php';

class Akita_OAuth2_Model_AccessToken_Test extends PHPUnit_Framework_TestCase
{
    public function testAccessor()
    {
        $authinfo = new Akita_OAuth2_Model_AccessToken();
        $this->assertClassHasAttribute('authId','Akita_OAuth2_Model_AccessToken');
        $this->assertClassHasAttribute('token','Akita_OAuth2_Model_AccessToken');
        $this->assertClassHasAttribute('expiresIn','Akita_OAuth2_Model_AccessToken');
        $this->assertClassHasAttribute('createdOn','Akita_OAuth2_Model_AccessToken');

        $this->assertEmpty($authinfo->authId);
        $authinfo->authId = 'authId';
        $this->assertEquals($authinfo->authId, 'authId');
        $authinfo->authId = '';
        $this->assertEmpty($authinfo->authId);

        $this->assertEmpty($authinfo->token);
        $authinfo->token = 'token';
        $this->assertEquals($authinfo->token, 'token');
        $authinfo->token = '';
        $this->assertEmpty($authinfo->token);

        $this->assertEmpty($authinfo->expiresIn);
        $authinfo->expiresIn = 3600;
        $this->assertEquals($authinfo->expiresIn, 3600);
        $authinfo->expiresIn = 0;
        $this->assertEmpty($authinfo->expiresIn);

        $this->assertEmpty($authinfo->createdOn);
        $ts = time();
        $authinfo->createdOn = $ts;
        $this->assertEquals($authinfo->createdOn, $ts);
        $authinfo->createdOn = 0;
        $this->assertEmpty($authinfo->createdOn);
    }

    public function testExpire()
    {
        $authinfo = new Akita_OAuth2_Model_AccessToken();
        $expired_ts = time() - 1;
        $authinfo->expiresIn = 3600;
        $authinfo->createdOn = $expired_ts - 3600;
        $this->assertTrue($authinfo->isExpired());
        $authinfo->createdOn = time();
        $this->assertFalse($authinfo->isExpired());
    }

    public function testResponse()
    {
        $authinfo = new Akita_OAuth2_Model_AccessToken();
        $authinfo->token = 'token_str';
        $authinfo->expiresIn = 3600;
        $expect_response = array(
            'access_token'  => 'token_str',
            'expires_in'  => 3600
        );
        $this->assertEquals($expect_response, $authinfo->getResponse());
    }
}
