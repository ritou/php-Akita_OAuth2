<?php

/**
 * Akita_OAuth2_Server_AuthorizationHandler
 *
 * AuthorizationHandler class
 *
 * PHP versions 5
 *
 * LICENSE: MIT License
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
require_once dirname(__FILE__) . '/Error.php';

/**
 * Akita_OAuth2_Server_AuthorizationHandler
 *
 * AuthorizationHandler class
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_AuthorizationHandler
{
    /**
     * process Authorization Request
     *
     * @param Akita_OAuth2_Server_DataHandler $dataHandler
     */
    public function processAuthorizationRequest($dataHandler, $allowed_response_type=array('code', 'token', 'code token'))
    {
        $request = $dataHandler->getRequest();

        $response_type = (isset($request->param['response_type'])) ? $request->param['response_type'] : "";
        if (empty($response_type)) {
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'response_type' is required"
            );
        }
        if(!in_array($response_type, $allowed_response_type)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'unsupported_response_type'
            );
        }
        
        // validate client_id
        $client_id = (isset($request->param['client_id'])) ? $request->param['client_id'] : "" ;
        if (empty($client_id)) {
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'client_id' is required"
            );
        }
        if (!$dataHandler->validateClientById( $client_id )) {
            throw new Akita_OAuth2_Server_Error(
                '400',
                'unauthorized_client'
            );
        }

        // validate redirect_uri
        $redirect_uri = (isset($request->param['redirect_uri'])) ? $request->param['redirect_uri'] : "";
        if(empty($redirect_uri)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'redirect_uri' is required"
            );
        }
        if(!$dataHandler->validateRedirectUri($client_id, $redirect_uri)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'redirect_uri' is invalid"
            );
        }

        // validate scope
        $scope = (isset($request->param['scope'])) ? $request->param['scope'] : "";
        if(!$dataHandler->validateScope($client_id, $scope)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_scope'
            );
        }
    }

    /**
     * create AuthInfo and AccessToken and build response
     *
     * @param Akita_OAuth2_Server_DataHandler $dataHandler
     */
    public function allowAuthorizationRequest($dataHandler)
    {
        $this->processAuthorizationRequest( $dataHandler );
        $request = $dataHandler->getRequest();
        
        $client_id = $request->param['client_id'];
        $user_id = $dataHandler->getUserId();
        $scope = $request->param['scope'];

        $authInfo = $dataHandler->createOrUpdateAuthInfo(
            array(
                'clientId' => $client_id,
                'userId'   => $user_id,
                'scope'     => $scope,
                'grant_type'     => 'authorization_code' 
            )
        );
        if(is_null($authInfo)){
            throw new Akita_OAuth2_Server_Error(
                '500',
                'server_error'
            );
        }

        $accessToken = null;
        if(     $request->param['response_type'] == "token" || 
                $request->param['response_type'] == "code token"
            ){
            // issue Access Token
            $accessToken =  $dataHandler->createOrUpdateAccessToken(
                array(
                    'authInfo'  => $authInfo
                )
            );
            if(is_null($accessToken)){
                throw new Akita_OAuth2_Server_Error(
                    '500',
                    'server_error'
                );
            }
        }

        // build response
        $params = array();
        if(!is_null($accessToken)){
            $params = $accessToken->getResponse();
        }

        if(     $request->param['response_type'] == "code" || 
                $request->param['response_type'] == "code token"
                ){
            $params['code'] = $authInfo->code;
        }

        $state = (isset($request->param['state'])) ? $request->param['state'] : "";
        if(!empty($state)){
            $params['state'] = $state;
        }
        if(empty($params['access_token'])){
            $res = array(
                'redirect_uri' => $request->param['redirect_uri'],
                'query'     => $params,
                'fragment'  => array()
            );
        }else{
            $res = array(
                'redirect_uri' => $request->param['redirect_uri'],
                'query'     => array(),
                'fragment'  => $params
            );
        }
        return $res;
    }

    /**
     * build denied response
     *
     * @param Akita_OAuth2_Server_DataHandler $dataHandler
     */
    public function denyAuthorizationRequest($dataHandler)
    {
        $this->processAuthorizationRequest( $dataHandler );
        $request = $dataHandler->getRequest();
        
        // build response
        $params['error'] = 'access_denied';
        $state = (isset($request->param['state'])) ? $request->param['state'] : "";
        if(!empty($state)){
            $params['state'] = $state;
        }
        if( $request->param['response_type'] == 'code' ){
            $res = array(
                'redirect_uri' => $request->param['redirect_uri'],
                'query'     => $params,
                'fragment'  => array()
            );
        }else{
            $res = array(
                'redirect_uri' => $request->param['redirect_uri'],
                'query'     => array(),
                'fragment'  => $params
            );
        }
        return $res;
    }
}
