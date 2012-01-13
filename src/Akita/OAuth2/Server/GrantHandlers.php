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
 * @link      http://
 */
require_once dirname(__FILE__) . '/GrantHandler/*.php';

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
 * @link      http://
 */
class Akita_OAuth2_Server_GrantHandlers
{
    /**
     * return DataHandler for each Grant Type
     *
     * @param string $grantType Grant Type
     * @return Akita_OAut2_Server_GrantHandler_* or null
     */
    public static function getHandler( $grantType ){
        switch ($grantType) {
            case 'authorization_code':
                $handler = new Akita_OAuth2_Server_GrantHandler_AuthorizationCode();
                break;
            case 'refresh_token':
                $handler = new Akita_OAuth2_Server_GrantHandler_RefreshToken();
                break;
/*
            case 'client_credentials':
                $handler = new Akita_OAuth2_Server_GrantHandler_ClientCredentials();
                break;
            case 'password':
                $handler = new Akita_OAuth2_Server_GrantHandler_Password();
                break;
*/
            default:
                $handler = null;
        }
        return $handler;
    }
}
