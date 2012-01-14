<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AccessToken.php';

class Akita_OAuth2_Model_AccessToken_Test extends PHPUnit_Framework_TestCase
{
    public function testAccessor()
    {
        $authinfo = new Akita_OAuth2_Model_AccessToken();

        $this->assertEmpty($authinfo->auth_id);
        $authinfo->auth_id = 'auth_id';
        $this->assertEquals($authinfo->auth_id, 'auth_id');
        $authinfo->auth_id = '';
        $this->assertEmpty($authinfo->auth_id);

        $this->assertEmpty($authinfo->token);
        $authinfo->token = 'token';
        $this->assertEquals($authinfo->token, 'token');
        $authinfo->token = '';
        $this->assertEmpty($authinfo->token);

        $this->assertEmpty($authinfo->expires_in);
        $authinfo->expires_in = 3600;
        $this->assertEquals($authinfo->expires_in, 3600);
        $authinfo->expires_in = 0;
        $this->assertEmpty($authinfo->expires_in);

        $this->assertEmpty($authinfo->created_on);
        $ts = time();
        $authinfo->created_on = $ts;
        $this->assertEquals($authinfo->created_on, $ts);
        $authinfo->created_on = 0;
        $this->assertEmpty($authinfo->created_on);
    }

    public function testExpire()
    {
        $authinfo = new Akita_OAuth2_Model_AccessToken();
        $expired_ts = time() - 1;
        $authinfo->expires_in = 3600;
        $authinfo->created_on = $expired_ts - 3600;
        $this->assertTrue($authinfo->isExpired());
        $authinfo->created_on = time();
        $this->assertFalse($authinfo->isExpired());
    }
}
