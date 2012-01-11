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
    private $auth_id;
    private $user_id;
    private $client_id;
    private $scope;
    private $refresh_token;
    private $code;
    private $redirecturi;

    // Accessor
    public function __get($name){ 
        return $this->$name;
    }  

    public function __set($name, $value){
        $this->$name = $value;
    }  
}
