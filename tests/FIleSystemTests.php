<?php declare(strict_types=1);
    define( 'ROOT_PATH', dirname(dirname(__FILE__)) . '\\' );
    require_once ROOT_PATH.'\\vendor\\autoload.php';
    use PHPUnit\Framework\TestCase;
    define('ITEMS_API_ENDPOINT','http://localhost/ProiectTW/api/items');
    class FileSystemTests extends TestCase
    {
        public static $token;
        public static $foldername;
        public static function setUpBeforeClass():void
        {
            $bytes=random_bytes(16);
            self::$foldername=substr(bin2hex($bytes),0,9);
            $login_test_curl=curl_init();
            curl_setopt_array($login_test_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>'http://localhost/ProiectTW/api/user/login',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'username'=>'test123',
                    'password'=>'test123',
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($login_test_curl),true);
            self::$token=$result_array['data']['access_token'];
        }
        public function testCreateFolderViaAPI():void
        {
            $create_folder_curl=curl_init();
            curl_setopt_array($create_folder_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>ITEMS_API_ENDPOINT,
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ".self::$token,'Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'foldername'=>self::$foldername,
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($create_folder_curl),true);
            $this->assertEquals(curl_getinfo($create_folder_curl,CURLINFO_HTTP_CODE),201);
        }
        public function testCreateFolderWithSameNameAPI()
        {
            $create_folder_curl=curl_init();
            curl_setopt_array($create_folder_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>ITEMS_API_ENDPOINT,
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_POST=>1,
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ".self::$token,'Content-Type:application/json'),
                CURLOPT_POSTFIELDS=>json_encode([
                    'foldername'=>self::$foldername,
                ]),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($create_folder_curl),true);
            $this->assertEquals(curl_getinfo($create_folder_curl,CURLINFO_HTTP_CODE),409);
        }
        public function testGetFilesFromRootAPI():void
        {
            $get_files_curl=curl_init();
            curl_setopt_array($get_files_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>ITEMS_API_ENDPOINT,
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ".self::$token,'Content-Type:application/json'),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $result_array=json_decode(curl_exec($get_files_curl),true);
            $this->assertEquals(curl_getinfo($get_files_curl,CURLINFO_HTTP_CODE),200);
            $this->assertNotNull($result_array['data']);
        }
    }
    ?>