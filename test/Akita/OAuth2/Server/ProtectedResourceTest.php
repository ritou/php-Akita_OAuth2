<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/ProtectedResource.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/DataHandler.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/Request.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AccessToken.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AuthInfo.php';

// test class
class DataHandler_ProtectedResource_Test
    extends Akita_OAuth2_Server_DataHandler
{
    private $_request;
    private $_authInfo;
    private $_accessToken;

    private $_userId;

    public function __construct($request, $authInfo, $accessToken){
        $this->_request = $request;
        $this->_authInfo = $authInfo;
        $this->_accessToken = $accessToken;
    }

    public function setUserId($userId){
        $this->_userId = $userId;
    }

    /* abstruct functions */
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
        return null;
    }

    public function getAuthInfoByCode( $code ){
        return null;
    }

    public function getAuthInfoByRefreshToken( $refreshToken ){
        return null;
    }

    public function getAccessToken( $token ){
        if($token == 'valid_access_token'){
            return $this->_accessToken;
        }else{
            return null;
        }
    }

    public function getAuthInfoById( $authId ){
        return $this->_authInfo;
    }

    public function validateClient( $clientId, $clientSecret, $grantType ){
        return false;
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
        return false;
    }
}

class Akita_OAuth2_Server_ProtectedResource_Test extends PHPUnit_Framework_TestCase
{
    public function test_processRequest_invalid_request()
    {
        $server = array();
        $params = array(
            'response_type' => 'invalid'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_ProtectedResource_Test($request, null, null);
        $protectedResource = new Akita_OAuth2_Server_ProtectedResource();
        try{
            unset($accessToken);
            $authInfo = $protectedResource->processRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'access_token' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processRequest_invalid_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'invalid',
            'access_token' => 'invalid_access_token'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'valid_access_token';
        $dataHandler = new DataHandler_ProtectedResource_Test($request, null, $accessToken);
        $protectedResource = new Akita_OAuth2_Server_ProtectedResource();
        try{
            unset($accessToken);
            $authInfo = $protectedResource->processRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('401', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_token', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processRequest_server_error()
    {
        $server = array();
        $params = array(
            'response_type' => 'invalid',
            'access_token' => 'valid_access_token'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'valid_access_token';
        $dataHandler = new DataHandler_ProtectedResource_Test($request, null, $accessToken);
        $protectedResource = new Akita_OAuth2_Server_ProtectedResource();
        try{
            unset($accessToken);
            $authInfo = $protectedResource->processRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('500', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('server_error', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processRequest_success()
    {
        $server = array();
        $params = array(
            'response_type' => 'invalid',
            'access_token' => 'valid_access_token'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->clientId = 'valid_client_id';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'valid_access_token';
        $dataHandler = new DataHandler_ProtectedResource_Test($request, $authInfo, $accessToken);
        $protectedResource = new Akita_OAuth2_Server_ProtectedResource();
        try{
            unset($accessToken);
            $authInfo = $protectedResource->processRequest($dataHandler);
            $this->assertEquals('valid_client_id', $authInfo->clientId, 'invalid authInfo');
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }
}
