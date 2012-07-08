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
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Model_AccessToken
{
    public $authId;
    public $token;
    public $scope;
    public $expiresIn;
    public $createdOn;

    public function __construct($authId='', $token='', $scope='', $expiresIn=0, $createdOn=0){
        $this->authId = $authId;
        $this->token = $token;
        $this->scope = $scope;
        $this->expiresIn = $expiresIn;
        $this->createdOn = $createdOn;
    }

    final public function isExpired(){
        return ((int)$this->createdOn + (int)$this->expiresIn < time());
    }

    public function getResponse(){
        $res = array(
            'access_token'  => $this->token,
            'expires_in'  => $this->expiresIn,
            'scope'  => $this->scope,
            'token_type' => 'Bearer'
        );
        return $res;
    }
}
