<?php

/**
 * Akita_OAuth2_Server_GrantHandler_Password
 *
 * GrantHandler class for Password Grant Type
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
 * Akita_OAuth2_Server_GrantHandler_Password
 *
 * GrantHandler class for Password Grant Type
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_GrantHandler_Password
    implements Akita_OAuth2_Server_GrantHandler
{
    public function handleRequest($dataHandler)
    {
        $request = $dataHandler->getRequest();

        // validate client credential
        $client_id = (isset($request->param['client_id'])) ? $request->param['client_id'] : "";
        $client_secret = (isset($request->param['client_secret'])) ? $request->param['client_secret'] : "";
        if(!$dataHandler->validateClient($client_id, $client_secret, 'password')){
            throw new Akita_OAuth2_Server_Error(
                '401',
                'invalid_client'
            );
        }

        $username = (isset($request->param['username'])) ? $request->param['username'] : "";
        if(empty($username)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'username' is required"
            );
        }
        $password = (isset($request->param['password'])) ? $request->param['password'] : "";
        if(empty($password)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'password' is required"
            );
        }
        $user_id = $dataHandler->getUserIdByCredentials( $username, $password );
        if(is_null($user_id)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_grant'
            );
        }

        $scope = (isset($request->param['scope'])) ? $request->param['scope'] : "";
        if(!$dataHandler->validateScope($client_id, $scope)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_scope'
            );
        }

        // obtain AuthInfo from RefreshToken
        $authInfo = $dataHandler->createOrUpdateAuthInfo(
            array(
                'clientId' => $client_id,
                'userId'   => $user_id,
                'scope'     => $scope,
                'grant_type'     => 'password'
            )
        );
        if(is_null($authInfo)){
            throw new Akita_OAuth2_Server_Error(
                '500',
                'server_error'
            );
        }

        // obtain AccessToken from AuthInfo
        $accessToken = $dataHandler->createOrUpdateAccessToken(
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

        // build response
        $res = $accessToken->getResponse();
        $res['refresh_token'] = $authInfo->refreshToken;
        return $res;
    }
}
