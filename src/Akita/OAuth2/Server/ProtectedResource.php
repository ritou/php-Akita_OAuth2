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
 * @link      http://
 */

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
 * @link      http://
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
        $access_token_str = $request->getAccessToken();
        if(empty($accessToken)){
            throw new Akita_OAuth2_Server_Error(
                '401',
                'invalid_request'
            );
        }
        $accessToken = $dataHandler->getAccessToken($access_token_str);
        if(is_null($accessToken)){
            throw new Akita_OAuth2_Server_Error(
                '401',
                'invalid_token'
            );
        }
    }
}
