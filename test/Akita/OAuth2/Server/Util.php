<?php

require_once dirname(__FILE__) . '/../../../../src/Akita/OAuth2/Server/Util.php';

class Akita_OAuth2_Server_Util_Test extends PHPUnit_Framework_TestCase
{
    public function test_jsonEncode()
    {
        $data =     array (
                        'access_token'  => 'test_access_token',
                        'expires_in'  => 3600,
                        'refresh_token'  => 'test_refresh_token'
                    );
        $expected_data = '{"access_token":"test_access_token","expires_in":3600,"refresh_token":"test_refresh_token"}';
        $this->assertEquals($expected_data, Akita_OAuth2_Server_Util::jsonEncode($data), 'encoded failed');
    }
}
