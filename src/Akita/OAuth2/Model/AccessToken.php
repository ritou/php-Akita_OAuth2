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

    // Accessor
    public function __get($name){ 
        return $this->$name;
    }  

    public function __set($name, $value){
        $this->$name = $value;
    }  
}
