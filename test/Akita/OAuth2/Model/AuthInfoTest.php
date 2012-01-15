<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AuthInfo.php';

class Akita_OAuth2_Model_AuthInfo_Test extends PHPUnit_Framework_TestCase
{
    public function testAccessor()
    {
        $authinfo = new Akita_OAuth2_Model_AuthInfo();
        $this->assertClassHasAttribute('authId','Akita_OAuth2_Model_AuthInfo');
        $this->assertClassHasAttribute('userId','Akita_OAuth2_Model_AuthInfo');
        $this->assertClassHasAttribute('clientId','Akita_OAuth2_Model_AuthInfo');
        $this->assertClassHasAttribute('scope','Akita_OAuth2_Model_AuthInfo');
        $this->assertClassHasAttribute('refreshToken','Akita_OAuth2_Model_AuthInfo');
        $this->assertClassHasAttribute('code','Akita_OAuth2_Model_AuthInfo');
        $this->assertClassHasAttribute('redirectUri','Akita_OAuth2_Model_AuthInfo');

        $this->assertEmpty($authinfo->authId);
        $authinfo->authId = 'authId';
        $this->assertEquals('authId', $authinfo->authId);
        $authinfo->authId = '';
        $this->assertEmpty($authinfo->authId);

        $this->assertEmpty($authinfo->userId);
        $authinfo->userId = 'userId';
        $this->assertEquals('userId', $authinfo->userId);
        $authinfo->userId = '';
        $this->assertEmpty($authinfo->userId);

        $this->assertEmpty($authinfo->clientId);
        $authinfo->clientId = 'clientId';
        $this->assertEquals('clientId', $authinfo->clientId);
        $authinfo->clientId = '';
        $this->assertEmpty($authinfo->clientId);

        $this->assertEmpty($authinfo->scope);
        $authinfo->scope = 'scope';
        $this->assertEquals('scope', $authinfo->scope);
        $authinfo->scope = '';
        $this->assertEmpty($authinfo->scope);

        $this->assertEmpty($authinfo->refreshToken);
        $authinfo->refreshToken = 'refreshToken';
        $this->assertEquals('refreshToken', $authinfo->refreshToken);
        $authinfo->refreshToken = '';
        $this->assertEmpty($authinfo->refreshToken);

        $this->assertEmpty($authinfo->code);
        $authinfo->code = 'code';
        $this->assertEquals('code', $authinfo->code);
        $authinfo->code = '';
        $this->assertEmpty($authinfo->code);

        $this->assertEmpty($authinfo->redirectUri);
        $authinfo->redirectUri = 'redirectUri';
        $this->assertEquals('redirectUri', $authinfo->redirectUri);
        $authinfo->redirectUri = '';
        $this->assertEmpty($authinfo->redirectUri);
    }
}
