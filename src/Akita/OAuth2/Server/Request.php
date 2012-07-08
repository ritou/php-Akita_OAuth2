<?php
/**
 * Akita_OAuth2_Server_Request
 *
 * Simple HTTP Request class for OAuth 2.0 Endpoint.
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

/**
 * Akita_OAuth2_Server_Request
 *
 * Simple HTTP Request class for OAuth 2.0 Endpoint.
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_Request
{
    public $param;
    public $method;
    public $header;

    public function __construct($endpoint_type,
                                $server, 
                                $params=array(),
                                $headers=array())
    {
        $this->param = $params;
        $this->header = $headers;
        $this->method = (isset($server['REQUEST_METHOD'])) ? $server['REQUEST_METHOD'] : "GET";

        if( $endpoint_type=='token' &&
            $this->method=='POST')
        {
            // Decode Basic Authorization Header
            if( isset($server['PHP_AUTH_USER']) &&
                isset($server['PHP_AUTH_PW']) &&
                !empty($server['PHP_AUTH_USER']) && 
                !empty($server['PHP_AUTH_PW']))
            {
                $this->param['client_id'] = $server['PHP_AUTH_USER'];
                $this->param['client_secret'] = $server['PHP_AUTH_PW'];
            }
        }
    }

    public function getAccessToken(){
        $accessToken = (isset($this->param['access_token'])) ? $this->param['access_token'] : "";
        $authorizationHeader = (isset($this->header['Authorization'])) ? $this->header['Authorization'] : "";
        if(!empty($authorizationHeader) && substr($authorizationHeader, 0, 7) == 'Bearer '){
            $accessToken = ltrim(substr($authorizationHeader, 7));
        }
        return $accessToken;
    }
}
