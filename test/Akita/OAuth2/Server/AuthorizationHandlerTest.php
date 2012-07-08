<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/AuthorizationHandler.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/DataHandler.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/Request.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AccessToken.php';
require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Model/AuthInfo.php';

// test class
class DataHandler_AuthorizationHandler_Test
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
        return $this->_userId;
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
        return false;
    }

    public function validateClientById( $clientId ){
        if($clientId == 'valid_client_id'){
            return true;
        }else{
            return false;
        }
    }

    public function validateUserById( $userId ){
        return false;
    }

    public function validateRedirectUri( $clientId, $redirectUri){
        if($clientId == 'valid_client_id' && $redirectUri == 'http://valid_redirect_uri/'){
            return true;
        }else{
            return false;
        }
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

    public function setRefreshToken( $authInfo ){
        return null;
    }
}

class Akita_OAuth2_Server_AuthorizationHandler_Test extends PHPUnit_Framework_TestCase
{
    public function test_processAuthorizationRequest_response_type_is_required()
    {
        $server = array();
        $params = array();
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'response_type' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_unsupported_response_type()
    {
        $server = array();
        $params = array(
            'response_type' => 'invalid'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unsupported_response_type', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_client_id_is_required_code()
    {
        $server = array();
        $params = array(
            'response_type' => 'code'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'client_id' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_client_id_is_required_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'token'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'client_id' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_client_id_is_required_code_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'client_id' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_unauthorized_client()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'invalid_client_id'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('unauthorized_client', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_redirect_uri_is_required()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'redirect_uri' is required", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_redirect_uri_is_invalid()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://invalid_redirect_uri/'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_request', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEquals("'redirect_uri' is invalid", $error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_processAuthorizationRequest_invalid_scope()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $authHandler->processAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('400', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('invalid_scope', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_denyAuthorizationRequest_code()
    {
        $server = array();
        $params = array(
            'response_type' => 'code',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->denyAuthorizationRequest($dataHandler);
            $this->assertEquals('access_denied', $res['query']['error']);
            $this->assertEmpty(@$res['query']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_denyAuthorizationRequest_code_state()
    {
        $server = array();
        $params = array(
            'response_type' => 'code',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope',
            'state' => 'valid_state'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->denyAuthorizationRequest($dataHandler);
            $this->assertEquals('access_denied', $res['query']['error']);
            $this->assertEquals('valid_state', $res['query']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_denyAuthorizationRequest_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->denyAuthorizationRequest($dataHandler);
            $this->assertEquals('access_denied', $res['fragment']['error']);
            $this->assertEmpty(@$res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_denyAuthorizationRequest_token_state()
    {
        $server = array();
        $params = array(
            'response_type' => 'token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope',
            'state' => 'valid_state'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->denyAuthorizationRequest($dataHandler);
            $this->assertEquals('access_denied', $res['fragment']['error']);
            $this->assertEquals('valid_state', $res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_denyAuthorizationRequest_code_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->denyAuthorizationRequest($dataHandler);
            $this->assertEquals('access_denied', $res['fragment']['error']);
            $this->assertEmpty(@$res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_denyAuthorizationRequest_code_token_state()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope',
            'state' => 'valid_state'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->denyAuthorizationRequest($dataHandler);
            $this->assertEquals('access_denied', $res['fragment']['error']);
            $this->assertEquals('valid_state', $res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_server_error_authInfo()
    {
        $server = array();
        $params = array(
            'response_type' => 'code',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, null, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('500', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('server_error', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_server_error_accessToken()
    {
        $server = array();
        $params = array(
            'response_type' => 'code',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->refreshToken = 'test_refresh_token';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, null);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertEquals('500', $error->getOAuth2Code(), $error->getMessage());
            $this->assertEquals('server_error', $error->getOAuth2Error(), $error->getMessage());
            $this->assertEmpty($error->getOAuth2ErrorDescription(), $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_success_code()
    {
        $server = array();
        $params = array(
            'response_type' => 'code',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->code = 'test_code';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, $accessToken);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
            $this->assertEquals('http://valid_redirect_uri/', $res['redirect_uri']);
            $this->assertEquals('test_code', $res['query']['code']);
            $this->assertEmpty(@$res['query']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_success_code_state()
    {
        $server = array();
        $params = array(
            'response_type' => 'code',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope',
            'state' => 'test_state'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->code = 'test_code';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, $accessToken);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
            $this->assertEquals('http://valid_redirect_uri/', $res['redirect_uri']);
            $this->assertEquals('test_code', $res['query']['code']);
            $this->assertEquals('test_state', $res['query']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_success_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->code = 'test_code';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, $accessToken);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
            $this->assertEquals('http://valid_redirect_uri/', $res['redirect_uri']);
            $this->assertEquals('test_access_token', $res['fragment']['access_token']);
            $this->assertEquals(3600, $res['fragment']['expires_in']);
            $this->assertEquals('test_scope', $res['fragment']['scope']);
            $this->assertEmpty(@$res['fragment']['code']);
            $this->assertEmpty(@$res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_success_token_state()
    {
        $server = array();
        $params = array(
            'response_type' => 'token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope',
            'state' => 'test_state'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->code = 'test_code';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, $accessToken);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
            $this->assertEquals('http://valid_redirect_uri/', $res['redirect_uri']);
            $this->assertEquals('test_access_token', $res['fragment']['access_token']);
            $this->assertEquals(3600, $res['fragment']['expires_in']);
            $this->assertEquals('test_scope', $res['fragment']['scope']);
            $this->assertEmpty(@$res['fragment']['code']);
            $this->assertEquals('test_state', $res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_success_code_token()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->code = 'test_code';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, $accessToken);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
            $this->assertEquals('http://valid_redirect_uri/', $res['redirect_uri']);
            $this->assertEquals('test_code', $res['fragment']['code']);
            $this->assertEquals('test_access_token', $res['fragment']['access_token']);
            $this->assertEquals(3600, $res['fragment']['expires_in']);
            $this->assertEquals('test_scope', $res['fragment']['scope']);
            $this->assertEmpty(@$res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }

    public function test_allowAuthorizationRequest_success_code_token_state()
    {
        $server = array();
        $params = array(
            'response_type' => 'code token',
            'client_id' => 'valid_client_id',
            'redirect_uri' => 'http://valid_redirect_uri/',
            'scope' => 'valid_scope',
            'state' => 'test_state'
        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $authInfo = new Akita_OAuth2_Model_AuthInfo();
        $authInfo->code = 'test_code';
        $accessToken = new Akita_OAuth2_Model_AccessToken();
        $accessToken->token = 'test_access_token';
        $accessToken->expiresIn = 3600;
        $accessToken->scope = 'test_scope';
        $dataHandler = new DataHandler_AuthorizationHandler_Test($request, $authInfo, $accessToken);
        $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
        try{
            $res = $authHandler->allowAuthorizationRequest($dataHandler);
            $this->assertEquals('http://valid_redirect_uri/', $res['redirect_uri']);
            $this->assertEquals('test_code', $res['fragment']['code']);
            $this->assertEquals('test_access_token', $res['fragment']['access_token']);
            $this->assertEquals(3600, $res['fragment']['expires_in']);
            $this->assertEquals('test_scope', $res['fragment']['scope']);
            $this->assertEquals('test_state', $res['fragment']['state']);
        }catch(Akita_OAuth2_Server_Error $error){
            $this->assertTrue(false, $error->getMessage());
        }
    }
}
