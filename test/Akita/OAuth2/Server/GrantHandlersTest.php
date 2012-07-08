<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/GrantHandlers.php';


class Akita_OAuth2_Server_GrantHandlers_Test extends PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('authorization_code');
        $this->assertInstanceOf('Akita_OAuth2_Server_GrantHandler_AuthorizationCode',$grantHandler);
        $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('refresh_token');
        $this->assertInstanceOf('Akita_OAuth2_Server_GrantHandler_RefreshToken',$grantHandler);
        $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('client_credentials');
        $this->assertInstanceOf('Akita_OAuth2_Server_GrantHandler_ClientCredentials',$grantHandler);
        $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('password');
        $this->assertInstanceOf('Akita_OAuth2_Server_GrantHandler_Password',$grantHandler);
        try{
            $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('invalid_grant');
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unsupported_grant_type', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testInstance_unsupported_grant()
    {
        try{
            $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('authorization_code', array('refresh_token', 'client_credentials', 'password'));
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unsupported_grant_type', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
        $this->assertNotInstanceOf('Akita_OAuth2_Server_GrantHandler_AuthorizationCode',@$grantHandler);

        try{
            $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('refresh_token', array('authorization_code', 'client_credentials', 'password'));
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unsupported_grant_type', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
        $this->assertNotInstanceOf('Akita_OAuth2_Server_GrantHandler_RefreshToken',@$grantHandler);

        try{
            $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('client_credentials', array('authorization_code', 'refresh_token', 'password'));
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unsupported_grant_type', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
        $this->assertNotInstanceOf('Akita_OAuth2_Server_GrantHandler_ClientCredentials',@$grantHandler);

        try{
            $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler('password', array('authorization_code', 'refresh_token', 'client_credentials'));
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unsupported_grant_type', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
        $this->assertNotInstanceOf('Akita_OAuth2_Server_GrantHandler_Password',@$grantHandler);
    }
}
