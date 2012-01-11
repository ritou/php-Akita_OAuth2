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
    abstract public function request();

    /**
     * validate username(or email)/password and return user_id
     *
     * @param string $username User's Name or Email
     * @param string $password User's Password
     * @return string user identifier or throw Exception
     */
    abstract public function get_user_id( $username, $password );

    /**
     * create or update AuthInfo
     *
     * @param array $params params for generate AuthInfo
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function create_or_update_auth_info( $params );

    /**
     * create or update AccessToken
     *
     * @param array $params params for issue new Access Toen
     * @return Akita_OAuth2_Model_AccessToken object
     */
    abstract public function create_or_update_access_token( $params );

    /**
     * validate Authorization Code and return related AuthInfo
     *
     * @param string $code Authrization Code
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function get_auth_info_by_code( $code );

    /**
     * validate Authorization Code and return related AuthInfo
     *
     * @param string $code Authrization Code
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function get_auth_info_by_refresh_token( $refresh_token );

    /**
     * validate Access Token Scting and return Access Token object
     *
     * @param string $token Access Token String in HTTP Request
     * @return Akita_OAuth2_Model_AccessToken object
     */
    abstract public function get_access_token( $token );

    /**
     * get AuthInfo object from auth_id which is member of Access Token object
     *
     * @param string $auth_id Identifier of AuthInfo
     * @return Akita_OAuth2_Model_Authinfo object
     */
    abstract public function get_auth_info_by_id( $auth_id );

    /**
     * validate client crdential and grant type
     *
     * @param string $client_id Client ID
     * @param string $client_secret Client Secret 
     * @param string $grant_type Grant Type 
     * @return boolean vavalidation result
     */
    abstract public function validate_client( $client_id, $client_secret, $grant_type );

    /**
     * check cient after checking AccessToken and AuthInfo
     *
     * @param string $client_id Client Identifier of AuthInfo
     * @return boolen
     */
    abstract public function validate_client_by_id( $client_id );

    /**
     * check user after checking AccessToken and AuthInfo
     *
     * @param string $user_id User Identifier of AuthInfo
     * @return boolen
     */
    abstract public function validate_user_by_id( $user_id );
}
