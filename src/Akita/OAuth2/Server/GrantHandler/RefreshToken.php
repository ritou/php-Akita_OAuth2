<?php

/**
 * Akita_OAuth2_Server_GrantHandler_RefreshToken
 *
 * GrantHandler class for Token Refresh
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
require_once dirname(__FILE__) . '/../GrantHandler.php';
require_once dirname(__FILE__) . '/../Error.php';

/**
 * Akita_OAuth2_Server_GrantHandler_RefreshToken
 *
 * GrantHandler class for Token Refresh
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_GrantHandler_RefreshToken
    implements Akita_OAuth2_Server_GrantHandler
{
    public function handleRequest($dataHandler)
    {
        $request = $dataHandler->getRequest();

        $refresh_token = (isset($request->param['refresh_token'])) ? $request->param['refresh_token'] : "";
        if(empty($refresh_token)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'refresh_token' is required"
            );
        }

        // validate client credential
        $client_id = (isset($request->param['client_id'])) ? $request->param['client_id'] : "";
        $client_secret = (isset($request->param['client_secret'])) ? $request->param['client_secret'] : "";
        if(!$dataHandler->validateClient($client_id, $client_secret, 'refresh_token')){
            throw new Akita_OAuth2_Server_Error(
                '401',
                'invalid_client'
            );
        }

        // obtain AuthInfo from RefreshToken
        $authInfo = $dataHandler->getAuthInfoByRefreshToken($refresh_token);
        if(is_null($authInfo)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_grant',
                "'refresh_token' is invalid"
            );
        }

        if($authInfo->clientId != $client_id){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_grant',
                "'client_id' is not matched"
            );
        }

        $scope = (isset($request->param['scope'])) ? $request->param['scope'] : $authInfo->scope;
        if(!$dataHandler->validateScopeForTokenRefresh($scope, $authInfo)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_scope'
            );
        }

        // obtain AccessToken from AuthInfo
        $accessToken = $dataHandler->createOrUpdateAccessToken(
            array(
                'authInfo'  => $authInfo,
                'scope'     => $scope
            )
        );

        if(is_null($accessToken)){
            throw new Akita_OAuth2_Server_Error(
                '500',
                'server_error'
            );
        }

        // build response
        $res = $accessToken->getResponse();
        if($refresh_token != $authInfo->refreshToken){
            $res['refresh_token'] = $authInfo->refreshToken;
        }

        return $res;
    }
}
