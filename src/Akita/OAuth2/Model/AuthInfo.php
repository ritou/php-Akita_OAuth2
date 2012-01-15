<?php
/**
 * Akita_OAuth2_Model_AuthInfo
 *
 * model class that represents authorization info.
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://
 */
class Akita_OAuth2_Model_AuthInfo
{
    private $authId;
    private $userId;
    private $clientId;
    private $scope;
    private $refreshToken;
    private $code;
    private $redirectUri;

    // Accessor
    public function __get($name){ 
        return $this->$name;
    }  

    public function __set($name, $value){
        $this->$name = $value;
    }  
}
