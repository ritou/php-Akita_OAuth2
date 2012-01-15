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
    private $authId;
    private $token;
    private $expiresIn;
    private $createdOn;

    public function __construct($authId='', $token='', $expiresIn=0, $createdOn=0){
        $this->auth_id = $authId;
        $this->token = $token;
        $this->expiresIn = $expiresIn;
        $this->createdOn = $creatd_on;
    }

    // Accessor
    public function __get($name){ 
        return $this->$name;
    }  

    public function __set($name, $value){
        $this->$name = $value;
    }  

    final public function isExpired(){
        return ((int)$this->createdOn + (int)$this->expiresIn < time());
    }

    public function getResponse(){
        $res = array(
            'access_token'  => $this->token,
            'expires_in'  => $this->expiresIn
        );
        return $res;
    }
}
