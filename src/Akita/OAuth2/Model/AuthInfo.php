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
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Model_AuthInfo
{
    public $authId;
    public $userId;
    public $clientId;
    public $scope;
    public $refreshToken;
    public $code;
    public $redirectUri;

    public function __construct(    $authId='', 
                                    $userId='', 
                                    $clientId='', 
                                    $scope='', 
                                    $refreshToken='', 
                                    $code='', 
                                    $redirectUri=''
    ){
        $this->authId   = $authId;
        $this->userId   = $userId;
        $this->clientId = $clientId;
        $this->scope    = $scope;
        $this->refreshToken    = $refreshToken;
        $this->code    = $code;
        $this->redirectUri    = $redirectUri;
    }
}
