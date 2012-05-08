<?php
/**
 * Akita_OAuth2_Server_DataHandler 
 *
 * datahandler abstract class
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
 * @link      http://
 */

/**
 * Akita_OAuth2_Server_DataHandler
 *
 * abstrct class
 *
 * @category  OAuth2
 * @package   Akita_OAuth2
 * @author    Ryo Ito <ritou.06@gmail.com>
 * @copyright 2012 Ryo Ito
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @link      http://
 */
abstract class Akita_OAuth2_Server_DataHandler
{

    /**
     * return request object
     *
     * @return Akita_OAuth2_Server_Request object
     */
    abstract public function getRequest();

    /**
     * return user_id from request
     *
     * @return string user identifier or null
     */
    abstract public function getUserId();

    /**
     * validate username(or email)/password and return user_id
     *
     * @param string $username User's Name or Email
     * @param string $password User's Password
     * @return string user identifier or null
     */
    abstract public function getUserIdByCredentials( $username, $password );

    /**
     * create or update AuthInfo
     *
     * @param array $params params for generate AuthInfo
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function createOrUpdateAuthInfo( $params );

    /**
     * create or update AccessToken
     *
     * @param array $params params for issue new Access Toen
     * @return Akita_OAuth2_Model_AccessToken object
     */
    abstract public function createOrUpdateAccessToken( $params );

    /**
     * validate Authorization Code and return related AuthInfo
     *
     * @param string $code Authrization Code
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function getAuthInfoByCode( $code );

    /**
     * validate Authorization Code and return related AuthInfo
     *
     * @param string $refreshToken Refresh Token
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function getAuthInfoByRefreshToken( $refreshToken );

    /**
     * validate Access Token Scting and return Access Token object
     *
     * @param string $token Access Token String in HTTP Request
     * @return Akita_OAuth2_Model_AccessToken object
     */
    abstract public function getAccessToken( $token );

    /**
     * get AuthInfo object from auth_id which is member of Access Token object
     *
     * @param string $authId Identifier of AuthInfo
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function getAuthInfoById( $authId );

    /**
     * validate client crdential and grant type
     *
     * @param string $clientId Client ID
     * @param string $clientSecret Client Secret 
     * @param string $grantType Grant Type 
     * @return boolean vavalidation result
     */
    abstract public function validateClient( $clientId, $clientSecret, $grantType );

    /**
     * check client
     *
     * @param string $clientId Client Identifier of AuthInfo
     * @return boolen
     */
    abstract public function validateClientById( $clientId );

    /**
     * check user
     *
     * @param string $userId User Identifier of AuthInfo
     * @return boolen
     */
    abstract public function validateUserById( $userId );

    /**
     * check redirect_uri and Client ID
     *
     * @param string $clientId Client ID
     * @param string $redirect_uri redirect_uri Parameter
     * @return boolen
     */
    abstract public function validateRedirectUri( $clientId, $redirectUri );

    /**
     * check scope and Client ID
     *
     * @param string $clientId Client ID
     * @param string $scope Sope parameter
     * @return boolen
     */
    abstract public function validateScope( $clientId, $scope );

    /**
     * check scope and AuthInfo
     *
     * @param string $scope Sope parameter
     * @param Akita_OAuth2_Model_Authinfo $authInfo AuthInfo
     * @return boolen
     */
    abstract public function validateScopeForTokenRefresh( $scope, $authInfo);


    /**
     * remove Authorization Code from AuthInfo, and set Refresh Token
     *
     * @param string $scope Sope parameter
     * @param Akita_OAuth2_Model_Authinfo $authInfo AuthInfo
     * @return boolen
     */
    abstract public function setRefreshToken( $authInfo );
}
