<?php

require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Server/GrantHandler/ClientCredentials.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Server/DataHandler.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Server/Request.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Model/AccessToken.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Model/AuthInfo.php';

// test class
class DataHandler_ClientCredentials_Test 
    extends Akita_OAuth2_Server_DataHandler
{
    private $_request;
    private $_authInfo;
    private $_accessToken;

    public function __construct($request, $authInfo, $accessToken){
        $this->_request = $request;
        $this->_authInfo = $authInfo;
        $this->_accessToken = $accessToken;
    }

    public function getRequest(){
        return $this->_request;
    }

    public function getUserId(){
        return null;
    }

    public function getUserIdByCredentials( $username, $password ){
        return null;
    }

    public function createOrUpdateAuthInfo( $params ){
        return $this->_authInfo;
    }

    public function createOrUpdateAccessToken( $params ){
        return $this->_accessToken;
    }

    public function getAuthInfoByCode( $code ){
        return null;
    }

    public function getAuthInfoByRefreshToken( $refreshToken ){
            return null;
    }

    public function getAccessToken( $token ){
        return null;
    }

    public function getAuthInfoById( $authId ){
        return null;
    }

    public function validateClient( $clientId, $clientSecret, $grantType ){
        if($clientId == 'valid_client_id' && $clientSecret == 'valid_client_secret'){
            return true;
        }else{
            return false;
        }
    }

    public function validateClientById( $clientId ){
        return false;
    }

    public function validateUserById( $userId ){
        return false;
    }

    public function validateRedirectUri( $clientId, $redirectUri){
        return false;
    }

    public function validateScope( $clientId, $scope ){
        if($clientId == 'valid_client_id' && $scope == 'valid_scope'){
            return true;
        }else{
            return false;
        }
    }

    public function validateScopeForTokenRefresh( $scope, $authInfo){
        return false;
    }
}

class Akita_OAuth2_Server_GrantHandler_ClientCredentials_Test extends PHPUnit_Framework_TestCase
{
    public function testClientCredentials_invalid_client()
    {
        $server = array();
        $params = array();
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_ClientCredentials_Test($request, null, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('401', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_client', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testClientCredentials_invalid_scope()
    {
        $server = array();
        $params = array(
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_ClientCredentials_Test($request, null, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_scope', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testClientCredentials_server_error_authinfo()
    {
        $server = array();
        $params = array(
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_ClientCredentials_Test($request, null, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('500', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('server_error', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testClientCredentials_server_error_access_token()
    {
        $server = array();
        $params = array(
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'valid_scope'
        );
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_ClientCredentials_Test($request, $authInfo, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('500', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('server_error', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testClientCredentials_success()
    {
        $server = array();
        $params = array(
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'valid_scope'
        );
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_ClientCredentials_Test($request, $authInfo, $accessToken);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
        try{
            $res = $grantHandler->handleRequest($dataHandler);
            $this->assertEquals('test_access_token', $res["access_token"], 'invalid response : access token');
            $this->assertEquals(3600, $res["expires_in"], 'invalid response : expires_in');
            $this->assertEquals('test_scope', $res["scope"], 'invalid response : scope');
            $this->assertEmpty($res["refresh_token"], 'invalid response : refresh token is not required');
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }
}
