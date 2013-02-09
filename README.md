# php-Akita_OAuth2 #

This is PHP library for OAuth 2.0 Authorization Server.  

## Specifications to support ##

- [http://tools.ietf.org/html/rfc6749](http://tools.ietf.org/html/rfc6749 "The OAuth 2.0 Authorization Framework")
- [http://tools.ietf.org/html/rfc6750](http://tools.ietf.org/html/rfc6750 "The OAuth 2.0 Authorization Framework: Bearer Token Usage")

## Source ##

    src/
    `-- Akita
        |-- OAuth2
        |   |-- Model
        |   |   |-- AccessToken.php
        |   |   `-- AuthInfo.php
        |   `-- Server
        |       |-- AuthorizationHandler.php
        |       |-- DataHandler.php
        |       |-- Error.php
        |       |-- GrantHandler
        |       |   |-- AuthorizationCode.php
        |       |   |-- ClientCredentials.php
        |       |   |-- Password.php
        |       |   `-- RefreshToken.php
        |       |-- GrantHandler.php
        |       |-- GrantHandlers.php
        |       |-- ProtectedResource.php
        |       `-- Request.php
        `-- OAuth2.php

### DataHandler ###

*Akita_OAuth2_Server_DataHandler* class is abstract class.
You must inherit it and implement own processing.

    class Your_DataHandler
        extends Akita_OAuth2_Server_DataHandler
    {
        ....
    }

### Endpoints ###

There are classes implementing each endpoint.  
The processing defines it in the DataHandler class mentioned above.

#### Authorization Endpoint ####

The *Akita_OAuth2_Server_AuthorizationHandler* class implements the handling of request and the processing after the agreement.  

    // process request
    $headers = apache_request_headers();
    $server = $_SERVER;
    $params = $_GET;
    
    $request = new Akita_OAuth2_Server_Request('authorization', $server, $params, $headers);
    $dataHandler = new Your_DataHandler($request);
    $authHandler = new Akita_OAuth2_Server_AuthorizationHandler();
    try{
        $authHandler->processAuthorizationRequest($dataHandler);
    }catch(Akita_OAuth2_Server_Error $error){
        .... // error handling
    }
    
    // after the agreement
    try{
        if(.... // User denied){
            $respose = $authHandler->denyAuthorizationRequest($dataHandler);
        }else{ // User allowed
            $respose = $authHandler->allowAuthorizationRequest($dataHandler);
        }
    }catch(Akita_OAuth2_Server_Error $error){
        .... // error handling
    }

#### Token Endpoint ####

The *Akita_OAuth2_Server_GrantHandlers* class returns handler for each grant type.  
Grant handler process the request and returns response data.  

    // process request
    $headers = apache_request_headers();
    $server = $_SERVER;
    $params = $_POST;
    
    $request = new Akita_OAuth2_Server_Request('token', $server, $params, $headers);
    $dataHandler = new Your_DataHandler($request);
    try{
        $grantHandler = Akita_OAuth2_Server_GrantHandlers::getHandler($request->param['grant_type']);
        $res = $grantHandler->handleRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
        .... // error handling
    }

There are four grant handlers.

- *authorization_code* : *Akita_OAuth2_Server_GrantHandler_AuthorizationCode*
- *refresh_token* : *Akita_OAuth2_Server_GrantHandler_RefreshToken*
- *client_credentials* : *Akita_OAuth2_Server_GrantHandler_ClientCredentials*
- *password* : *Akita_OAuth2_Server_GrantHandler_Password*

### UserInfo Endpoint ###

*Akita_OAuth2_Server_ProtectedResource* class returns AuthInfo class.

    $headers = apache_request_headers();
    $server = $_SERVER;
    $params = $_GET;
    
    $request = new Akita_OAuth2_Server_Request('protectedresource', $server, $params, $headers);
    $dataHandler = new Your_DataHandler($request);
    $resource = new Akita_OAuth2_Server_ProtectedResource();
    try{
        $authInfo = $resource->processRequest($dataHandler);
        }catch(Akita_OAuth2_Server_Error $error){
        .... // error handling
    }

### Model ###

- *Akita_OAuth2_Model_AuthInfo* : represents authorization information
- *Akita_OAuth2_Model_AccessToken* : represents access token

## Example ##

Sample Server and Client are here. 
https://github.com/ritou/php-Akita_OAuth2_Server_Sample

AUTHOR
------------------------------------------------------
@ritou ritou@gmail.com

LISENCE
------------------------------------------------------
MIT Lisense.
