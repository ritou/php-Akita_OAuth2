<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AuthInfo.php';

class Akita_OAuth2_Model_AuthInfo_Test extends PHPUnit_Framework_TestCase
{
    public function testAccessor()
    {
        $authinfo = new Akita_OAuth2_Model_AuthInfo();

        $this->assertEmpty($authinfo->auth_id);
        $authinfo->auth_id = 'auth_id';
        $this->assertEquals($authinfo->auth_id, 'auth_id');
        $authinfo->auth_id = '';
        $this->assertEmpty($authinfo->auth_id);

        $this->assertEmpty($authinfo->user_id);
        $authinfo->user_id = 'user_id';
        $this->assertEquals($authinfo->user_id, 'user_id');
        $authinfo->user_id = '';
        $this->assertEmpty($authinfo->user_id);

        $this->assertEmpty($authinfo->client_id);
        $authinfo->client_id = 'client_id';
        $this->assertEquals($authinfo->client_id, 'client_id');
        $authinfo->client_id = '';
        $this->assertEmpty($authinfo->client_id);

        $this->assertEmpty($authinfo->scope);
        $authinfo->scope = 'scope';
        $this->assertEquals($authinfo->scope, 'scope');
        $authinfo->scope = '';
        $this->assertEmpty($authinfo->scope);

        $this->assertEmpty($authinfo->refresh_token);
        $authinfo->refresh_token = 'refresh_token';
        $this->assertEquals($authinfo->refresh_token, 'refresh_token');
        $authinfo->refresh_token = '';
        $this->assertEmpty($authinfo->refresh_token);

        $this->assertEmpty($authinfo->code);
        $authinfo->code = 'code';
        $this->assertEquals($authinfo->code, 'code');
        $authinfo->code = '';
        $this->assertEmpty($authinfo->code);

        $this->assertEmpty($authinfo->redirect_uri);
        $authinfo->redirect_uri = 'redirect_uri';
        $this->assertEquals($authinfo->redirect_uri, 'redirect_uri');
        $authinfo->redirect_uri = '';
        $this->assertEmpty($authinfo->redirect_uri);
    }
}
