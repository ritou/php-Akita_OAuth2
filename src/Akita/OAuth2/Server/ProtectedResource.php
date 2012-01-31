<?php

/**
 * Akita_OAuth2_Server_ProtectedResource
 *
 * ProtectedResource class
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
 * Akita_OAuth2_Server_ProtectedResource
 *
 * ProtectedResource class
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_ProtectedResource
{
    /**
     * process API Request
     *
     * @param Akita_OAuth2_Server_DataHandler $dataHandler
     */
    public function processRequest($dataHandler)
    {
        $request = $dataHandler->getRequest();
        $param_access_token = $request->getAccessToken();
        if(empty($param_access_token)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'access_token' is required"
            );
        }
        $accessToken = $dataHandler->getAccessToken($param_access_token);
        if(is_null($accessToken)){
            throw new Akita_OAuth2_Server_Error(
                '401',
                'invalid_token'
            );
        }
        $authInfo = $dataHandler->getAuthInfoById($accessToken->authId);
        if(is_null($authInfo)){
            throw new Akita_OAuth2_Server_Error(
                '500',
                'server_error'
            );
        }
        return $authInfo;
    }
}
