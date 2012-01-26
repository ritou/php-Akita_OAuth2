<?php
/**
 * Akita_OAuth2_Server_Util
 *
 * OAuth 2.0 Server Utility Class
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
 * Akita_OAuth2_Server_Util
 *
 * OAuth 2.0 Server Utility Class
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://
 */
class Akita_OAuth2_Server_Util
{
    public static function jsonEncode($data){
        return str_replace("\/", "/", json_encode($data));
    }
}
