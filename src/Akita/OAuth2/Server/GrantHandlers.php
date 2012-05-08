<?php

/**
 * Akita_OAuth2_Server_GrantHandlers
 *
 * GrantHandler utility class
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
require_once dirname(__FILE__) . '/GrantHandler/AuthorizationCode.php';
require_once dirname(__FILE__) . '/GrantHandler/RefreshToken.php';
require_once dirname(__FILE__) . '/GrantHandler/ClientCredentials.php';
require_once dirname(__FILE__) . '/GrantHandler/Password.php';
require_once dirname(__FILE__) . '/Error.php';

/**
 * Akita_OAuth2_Server_GrantHandlers
 *
 * GrantHandler utility class
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_GrantHandlers
{
    /**
     * return DataHandler for each Grant Type
     *
     * @param string $grantType Grant Type
     * @return Akita_OAut2_Server_GrantHandler_* or null
     */
    public static function getHandler(  $grantType, 
                                        $supportedGrantTypes=array(
                                            'authorization_code',
                                            'refresh_token',
                                            'client_credentials',
                                            'password'
                                            )
                                        ){

        // check supported grant types
        if(!in_array($grantType, $supportedGrantTypes)){
            throw new Akita_OAuth2_Server_Error(
                '400',
                'unsupported_grant_type'
            );
        }

        switch ($grantType) {
            case 'authorization_code':
                $handler = new Akita_OAuth2_Server_GrantHandler_AuthorizationCode();
                break;
            case 'refresh_token':
                $handler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
                break;
            case 'client_credentials':
                $handler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
                break;
            case 'password':
                $handler = new Akita_OAuth2_Server_GrantHandler_Password();
                break;
            default:
                throw new Akita_OAuth2_Server_Error(
                    '400',
                    'unsupported_grant_type'
                );
        }
        return $handler;
    }
}
