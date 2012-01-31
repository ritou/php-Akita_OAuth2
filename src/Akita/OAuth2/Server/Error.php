<?php

/**
 * Akita_OAuth2_Server_Error
 *
 * OAuth 2.0 Server Custom Exception class
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
 * Akita_OAuth2_Server_Error
 *
 * OAuth 2.0 Server Custom Exception class
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://openpear.org/package/Akita_OAuth2
 */
class Akita_OAuth2_Server_Error extends Exception 
{
    private $_oauth2Code;
    private $_oauth2Error;
    private $_oauth2ErrorDescription;
    private $_oauth2ErrorUri;

    public function __construct(    $oauth2Code, 
                                    $oauth2Error, 
                                    $oauth2ErrorDescription = null, 
                                    $oauth2ErrorUri = null) {

        $this->_oauth2Code = $oauth2Code;
        $this->_oauth2Error = $oauth2Error;
        $this->_oauth2ErrorDescription = $oauth2ErrorDescription;
        $this->_oauth2ErrorUri = $oauth2ErrorUri;

        $message =  'Code : '.$this->_oauth2Code.
                    '; Error : '.$this->_oauth2Error.
                    '; Error Descripion : '.$this->_oauth2ErrorDescription.
                    '; Error Uri : '.$this->_oauth2ErrorUri.';';
        parent::__construct($message);
    }

    final public function getOAuth2Code(){
        return $this->_oauth2Code;
    }

    final public function getOAuth2Error(){
        return $this->_oauth2Error;
    }

    final public function getOAuth2ErrorDescription(){
        return $this->_oauth2ErrorDescription;
    }

    public function getOAuth2ErrorUri(){
        return $this->_oauth2ErrorUri;
    }
}
