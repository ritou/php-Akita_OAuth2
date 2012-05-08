<?php

require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Server/GrantHandler/RefreshToken.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Server/DataHandler.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Server/Request.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Model/AccessToken.php';
require_once dirname(__FILE__) . '/../../../../../src/Akita/OAuth2/Model/AuthInfo.php';

// test class
class DataHandler_RefreshToken_Test 
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
        return null;
    }

    public function createOrUpdateAccessToken( $params ){
        return $this->_accessToken;
    }

    public function getAuthInfoByCode( $code ){
        return null;
    }

    public function getAuthInfoByRefreshToken( $refreshToken ){
        if($refreshToken=='valid_refresh_token'){
            return $this->_authInfo;
        }else{
            return null;
        }
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
        return false;
    }

    public function validateScopeForTokenRefresh( $scope, $authInfo){
        if(strpos($authInfo->scope, $scope) === false){
            return false;
        }else{
            return true;
        }
    }

    public function setRefreshToken( $authInfo ){
        return false;
    }
}

class Akita_OAuth2_Server_GrantHandler_RefreshToken_Test extends PHPUnit_Framework_TestCase
{
    public function testRefreshToken_refresh_token_is_required()
    {
        $server = array();
        $params = array();
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_RefreshToken_Test($request, null, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'refresh_token' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testRefreshToken_invalid_client()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_RefreshToken_Test($request, null, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('401', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_client', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testRefreshToken_refresh_token_is_invalid()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token',
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $dataHandler = new DataHandler_RefreshToken_Test($request, null, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_grant', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'refresh_token' is invalid", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testRefreshToken_client_id_is_not_matched()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token',
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->clientId = 'invalid_client_id';
        $authInfo->redirectUri = 'valid_redirect_uri';
        $authInfo->refreshToken = 'valid_refresh_token';
        $dataHandler = new DataHandler_RefreshToken_Test($request, $authInfo, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_grant', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'client_id' is not matched", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testRefreshToken_invalid_scope()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token',
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'invalid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->clientId = 'valid_client_id';
        $authInfo->redirectUri = 'valid_redirect_uri';
        $authInfo->refreshToken = 'valid_refresh_token';
        $authInfo->scope = 'valid_scope';
        $dataHandler = new DataHandler_RefreshToken_Test($request, $authInfo, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_scope', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testRefreshToken_server_error()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token',
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->clientId = 'valid_client_id';
        $authInfo->redirectUri = 'valid_redirect_uri';
        $authInfo->refreshToken = 'valid_refresh_token';
        $authInfo->scope = 'valid_scope';
        $dataHandler = new DataHandler_RefreshToken_Test($request, $authInfo, null);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('500', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('server_error', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function testRefreshToken_success()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token',
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->clientId = 'valid_client_id';
        $authInfo->redirectUri = 'valid_redirect_uri';
        $authInfo->refreshToken = 'valid_refresh_token';
        $authInfo->scope = 'valid_scope';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_RefreshToken_Test($request, $authInfo, $accessToken);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $res = $grantHandler->handleRequest($dataHandler);
            $this->assertEquals('test_access_token', $res["access_token"], 'invalid response : access token');
            $this->assertEquals(3600, $res["expires_in"], 'invalid response : expires_in');
            $this->assertEquals('test_scope', $res["scope"], 'invalid response : scope');
            $this->assertEmpty($res["refresh_token"], 'invalid response : refresh token');
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function testRefreshToken_success_new_refresh_token()
    {
        $server = array();
        $params = array(
            'refresh_token' => 'valid_refresh_token',
            'client_id' => 'valid_client_id',
            'client_secret' => 'valid_client_secret',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->clientId = 'valid_client_id';
        $authInfo->redirectUri = 'valid_redirect_uri';
        $authInfo->refreshToken = 'new_refresh_token';
        $authInfo->scope = 'valid_scope';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_RefreshToken_Test($request, $authInfo, $accessToken);
        $grantHandler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
        try{
            $res = $grantHandler->handleRequest($dataHandler);
            $this->assertEquals('test_access_token', $res["access_token"], 'invalid response : access token');
            $this->assertEquals(3600, $res["expires_in"], 'invalid response : expires_in');
            $this->assertEquals('test_scope', $res["scope"], 'invalid response : scope');
            $this->assertEquals('new_refresh_token', $res["refresh_token"], 'invalid response : refresh token');
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }
}
