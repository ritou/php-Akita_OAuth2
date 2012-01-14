<?php
/**
 * Akita_OAuth2_Model_AccessToken
 *
 * model class that represents access token
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://
 */
class Akita_OAuth2_Model_AccessToken
{
    private $auth_id;
    private $token;
    private $expires_in;
    private $created_on;

    public function __construct($auth_id='', $token='', $expires_in=0, $created_on=0){
        $this->auth_id = $auth_id;
        $this->token = $token;
        $this->expires_in = $expires_in;
        $this->created_on = $creatd_on;
    }

    // Accessor
    public function __get($name){ 
        return $this->$name;
    }  

    public function __set($name, $value){
        $this->$name = $value;
    }  

    public function isExpired(){
        return ((int)$this->created_on + (int)$this->expires_in < time());
    }
}
