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
    private $scope;
    private $expiresIn;
    private $createdOn;

    public function __construct($authId='', $token='', $scope='', $expiresIn=0, $createdOn=0){
        $this->authId = $authId;
        $this->token = $token;
        $this->scope = $scope;
        $this->expiresIn = $expiresIn;
        $this->createdOn = $creatdOn;
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
            'expires_in'  => $this->expiresIn,
            'scope'  => $this->scope,
        );
        return $res;
    }
}
