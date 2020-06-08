<?php declare(strict_types=1);
    define( 'ROOT_PATH', dirname(dirname(__FILE__)) . '\\' );
    require_once ROOT_PATH.'\\vendor\\autoload.php';
    use PHPUnit\Framework\TestCase;
    define('USER_API_ENDPOINT','http://localhost/ProiectTW/api/user');
    class UserTests extends TestCase
    {
        public static $username;
        public static $password;
        public static $email;
        public static $token;
        public static function setUpBeforeClass():void
        {
            $bytes=random_bytes(16);
            self::$username=substr(bin2hex($bytes),0,9);
            $bytes=random_bytes(16);
            self::$password=substr(bin2hex($bytes),0,9);
            $bytes=random_bytes(16);
            self::$email=substr(bin2hex($bytes),0,9).'@gmail.com';
        }
        public function testAPIRegisterUser():void
        {
            $register_test_curl=curl_init();
            curl_setopt_array($register_test_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_API_ENDPOINT.'/register',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>self::$username,
                    'password'=>self::$password,
                    'email'=>self::$email
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($register_test_curl),true); 
            $this->assertEquals(curl_getinfo($register_test_curl,CURLINFO_HTTP_CODE),200);
        }
        public function testAPIRegisterWithExistingUsername():void
        {
            $register_test_curl=curl_init();
            curl_setopt_array($register_test_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_API_ENDPOINT.'/register',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>self::$username,
                    'password'=>'somethingrandom',
                    'email'=>uniqid('',true).'@gmail.com'
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($register_test_curl),true); 
            $this->assertEquals(curl_getinfo($register_test_curl,CURLINFO_HTTP_CODE),400);
        }
        public function testAPIRegisterWithExistingEmail():void
        {
            $register_test_curl=curl_init();
            curl_setopt_array($register_test_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_API_ENDPOINT.'/register',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>uniqid('',true),
                    'password'=>'somethingrandom',
                    'email'=>self::$email
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($register_test_curl),true); 
            $this->assertEquals(curl_getinfo($register_test_curl,CURLINFO_HTTP_CODE),400);
        }
        public function testAPILoginUser():void
        {
            $login_test_curl=curl_init();
            curl_setopt_array($login_test_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_API_ENDPOINT.'/login',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>self::$username,
                    'password'=>self::$password,
                    'email'=>self::$email
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($login_test_curl),true);
            $this->assertNotNull($result_array);    
            self::$token=$result_array['data']['access_token'];
            $this->assertEquals(curl_getinfo($login_test_curl,CURLINFO_HTTP_CODE),200);
        }
        public function testAPILoginWrongPassword():void
        {
            $login_test_curl=curl_init();
            curl_setopt_array($login_test_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_API_ENDPOINT.'/login',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>self::$username,
                    'password'=>'somethingrandom',
                    'email'=>self::$email
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($login_test_curl),true);
            $this->assertNotNull($result_array);
            $this->assertEquals(curl_getinfo($login_test_curl,CURLINFO_HTTP_CODE),401);
        }
        public function testAPIChangeUserCredentials():void
        {
            $change_user_profile=curl_init();
            curl_setopt_array($change_user_profile,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_API_ENDPOINT,
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_CUSTOMREQUEST=>'PATCH',
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ".self::$token,'Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>self::$username.uniqid('',true),
            ]),
            CURLOPT_SSL_VERIFYPEER=>false]);
            $result_array=json_decode(curl_exec($change_user_profile),true);
            $this->assertEquals(curl_getinfo($change_user_profile,CURLINFO_HTTP_CODE),200);
        }

    }
?>