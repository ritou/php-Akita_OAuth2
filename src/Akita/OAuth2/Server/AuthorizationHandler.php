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
 * @link      http://
 */
require_once dirname(__FILE__) . '/../GrantHandler.php';
require_once dirname(__FILE__) . '/../Error.php';

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
 * @link      http://
 */
class Akita_OAuth2_Server_AuthorizationHandler
{
    public function processAuthorizationRequest($dataHandler)
    {
        $request = $dataHandler->getRequest();
        
        // validate client_id
        $client_id = $request->param['client_id'];
        if (!$dataHandler->validateClientById( $client_id )) {
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'client_id' is required"
            );
        }

        // validate redirect_uri
        $redirect_uri = $request->param['redirect_uri'];
        if(!$dataHandler->validateRedirectUri($client_id, $redirect_uri)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_request',
                "'redirect_uri' is required"
            );
        }

        // validate scope
        if(!$dataHandler->validateScope($client_id, $scope)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'invalid_scope'
            );
        }
    }

    public function allowAuthorizationRequest($dataHandler)
    {
        $this->processAuthorizationRequest( $dataHandler );
        
        // 
    }

    public function denyAuthorizationRequest($dataHandler)
    {
        $this->processAuthorizationRequest( $dataHandler );
        
        // 
    }
}
