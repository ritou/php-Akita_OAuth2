<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/Request.php';

class Akita_OAuth2_Server_Request_Test extends PHPUnit_Framework_TestCase
{
    public function testAuthorizationRequestWithParams()
    {
        $server = array (
                        'REQUEST_METHOD' => 'GET'
                        );
        $params = array(
                        'client_id' => 'client_id_value'
                        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params);
        $this->assertEquals('GET', $request->method, 'Invalid method');
        $tmp_param = $request->param;
        $this->assertEquals('client_id_value', $tmp_param['client_id'], 'Invalid param');
    }

    public function testTokenRequest()
    {
        $server = array (
                        'REQUEST_METHOD' => 'POST'
                        );
        $params = array(
                        'client_id' => 'client_id_value',
                        'client_secret' => 'client_secret_value'
                        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $this->assertEquals('POST', $request->method, 'Invalid method');
        $tmp_param = $request->param;
        $this->assertEquals('client_id_value', $tmp_param['client_id'], 'Invalid param');
        $this->assertEquals('client_secret_value', $tmp_param['client_secret'], 'Invalid param');
    }

    public function testTokenRequestWithBasicAuth()
    {
        $server = array (
                        'REQUEST_METHOD' => 'POST',
                        'PHP_AUTH_USER' => 'client_id_value_from_basic',
                        'PHP_AUTH_PW' => 'client_secret_value_from_basic'
                        );
        $params = array(
                        'client_id' => 'client_id_value',
                        'client_secret' => 'client_secret_value'
                        );
        $request = new Akita_OAuth2_Server_Request('token', $server, $params);
        $this->assertEquals('POST', $request->method, 'Invalid method');
        $tmp_param = $request->param;
        $this->assertEquals('client_id_value_from_basic', $tmp_param['client_id'], 'Invalid param');
        $this->assertEquals('client_secret_value_from_basic', $tmp_param['client_secret'], 'Invalid param');
    }

    public function testAuthorizationRequestWithHeaders()
    {
        $server = array (
                        'REQUEST_METHOD' => 'GET'
                        );
        $params = array();
        $headers = array(
                        'Authorization' => 'Bearer param_access_token'
                        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params, $headers);
        $this->assertEquals('Bearer param_access_token', $request->header['Authorization'], 'Invalid header');
        $this->assertEquals('param_access_token', $request->getAccessToken(), 'Invalid Access Token');
    }

    public function testAuthorizationRequestWithNoHeaders()
    {
        $server = array (
                        'REQUEST_METHOD' => 'GET'
                        );
        $params = array(
                        'access_token' => 'header_access_token'
        );
        $headers = array();
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params, $headers);
        $this->assertEquals('header_access_token', $request->param['access_token'], 'Invalid Access Token param');
        $this->assertEquals('header_access_token', $request->getAccessToken(), 'Invalid Access Token');
    }

    public function testAuthorizationRequestWithParamAndHeaders()
    {
        $server = array (
                        'REQUEST_METHOD' => 'GET'
                        );
        $params = array(
                        'access_token' => 'param_access_token'
        );
        $headers = array(
                        'Authorization' => 'Bearer header_access_token'
                        );
        $request = new Akita_OAuth2_Server_Request('authorization', $server, $params, $headers);
        $this->assertEquals('header_access_token', $request->getAccessToken(), 'Invalid Access Token');
    }
}
