<?php
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
 * @link      http://
 */
class Akita_OAuth2_Server_Request
{
    private $param;
    private $method;

    public function __construct($endpoint_type,
                                $server, 
                                $params=array())
    {
        $this->param = $params;
        $this->method = $server['REQUEST_METHOD'];

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

    // Accessor
    public function __get($name){ 
        return $this->$name;
    }  

    public function __set($name, $value){
        $this->$name = $value;
    }  
}
