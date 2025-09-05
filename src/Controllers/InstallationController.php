<?php
namespace App\Controllers;

use PDO;
use PDOException;

class InstallationController
{
    private $input;

    public function __construct()
    {
        $this->input = json_decode(file_get_contents('php://input'), true);
    }

    public function handleRequest()
    {
        $action = $_GET['action'] ?? null;

        switch ($action) {
            case 'requirements':
                $this->checkRequirements();
                break;
            case 'testdb':
                $this->testDatabase();
                break;
            case 'writeconfig':
                $this->writeConfigAndImportSQL();
                break;
            case 'getSiteDefaults':
                $this->getSiteDefaults();
                break;
            case 'createDatabase':
                $this->createDatabase($this->input['db_name'] ?? 'default_db');
                break;
            default:
                $this->sendJson(["status" => "error", "message" => "Geçersiz istek."]);
        }
    }

    private function checkRequirements()
    {
        $extensions = [
            "pdo_mysql" => extension_loaded('pdo_mysql'),
            "openssl" => extension_loaded('openssl'),
            "mbstring" => extension_loaded('mbstring'),
        ];
        $writable = is_writable(__DIR__.'/config');

        $this->sendJson([
            "php_version" => PHP_VERSION,
            "extensions" => $extensions,
            "writable" => $writable
        ]);
    }

private function testDatabase()
{
    $host = $this->input['db_host'] ?? '';
    $dbname = $this->input['db_name'] ?? '';
    $user = $this->input['db_user'] ?? '';
    $pass = $this->input['db_pass'] ?? '';

    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
        if($stmt->rowCount() === 0){
            $this->sendJson([
                "status"=>"unknown_database",
                "message"=>"Veritabanı bulunamadı. Oluşturulsun mu?",
                "db_name"=>$dbname
            ]);
        } else {
            $this->sendJson(["status"=>"success","message"=>"Veritabanı bağlantısı başarılı."]);
        }
    } catch (PDOException $e) {
        $this->sendJson(["status"=>"error","message"=>"Veritabanı bağlantısı başarısız: ".$e->getMessage()]);
    }
}


public function createDatabase($dbname)
{
    $host = $this->input['db_host'] ?? '';
    $user = $this->input['db_user'] ?? '';
    $pass = $this->input['db_pass'] ?? '';

    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $this->sendJson(["status"=>"success","message"=>"Veritabanı '$dbname' oluşturuldu."]);
    } catch (PDOException $e) {
        $this->sendJson(["status"=>"error","message"=>"Veritabanı oluşturulamadı: ".$e->getMessage()]);
    }
}


private function writeConfigAndImportSQL()
{
    $configData = [
        'db'=>[
            'host'=>$this->input['db_host'] ?? '',
            'name'=>$this->input['db_name'] ?? '',
            'user'=>$this->input['db_user'] ?? '',
            'pass'=>$this->input['db_pass'] ?? ''
        ],
        'websocket'=>[
            'host'=>$this->input['ws_host'] ?? '',
            'port'=>$this->input['ws_port'] ?? '',
            'password'=>$this->input['ws_password'] ?? ''
        ],
        'site'=>[
            'name'=>$this->input['site_name'] ?? '',
            'description'=>$this->input['site_description'] ?? '',
            'title'=>$this->input['site_title'] ?? ''
        ],
        'app'=>[
            'url'=>($_SERVER['REQUEST_SCHEME'] ?? 'http').'://'.$_SERVER['HTTP_HOST']
        ]
    ];

    $configDir = __DIR__.'/config';
    if(!is_dir($configDir)) mkdir($configDir, 0755, true);
    $configFile = $configDir.'/config.php';
    $configContent = "<?php\nreturn ".var_export($configData,true).";";
    if(!file_put_contents($configFile,$configContent)){
        $this->sendJson(["status"=>"error","message"=>"Config dosyası yazılamadı."]);
    }

    $host = $configData['db']['host'];
    $dbname = $configData['db']['name'];
    $user = $configData['db']['user'];
    $pass = $configData['db']['pass'];

    try {
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
        if($stmt->rowCount() === 0){
            $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        }

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $sqlFile = __DIR__.'/config/install.sql';
        if(file_exists($sqlFile)){
            $sql = file_get_contents($sqlFile);

        $commands = array_filter(array_map('trim', explode(';', $sql)));
            
        foreach ($commands as $command) {
            if ($command) {
                try {
                    $pdo->exec($command);
                } catch (\PDOException $e) {
                    echo "Hata: " . $e->getMessage() . "\n";
                }
            }
        }
    
    
            $sqlResult = ["status"=>"success","message"=>"SQL dosyası başarıyla içeri aktarıldı."];
        } else {
            $sqlResult = ["status"=>"error","message"=>"SQL dosyası bulunamadı: install.sql"];
        }
    
        $this->sendJson([
            "status"=>"success",
            "message"=>"Config yazıldı ve SQL import tamamlandı.",
            "sqlResult"=>$sqlResult
        ]);
        
    } catch(PDOException $e){
            $this->sendJson(["status"=>"error","message"=>"DB veya SQL import hatası: ".$e->getMessage()]);
        }
    }

    private function getSiteDefaults()
    {
        $json = '{
            "status": true,
            "message": "Ayarlar getirildi.",
            "data": {
                "siteInfo": {
                    "data": {
                        "siteName": "Marlex Forum",
                        "defaultSiteDescription": "Marlex Forum ama modern bir forum, sıvı cam tasarımıyla :)",
                        "defaultSiteTitle": ""
                    }
                },
                "defaultSiteDescription": "Marlex Forum ama modern bir forum, sıvı cam tasarımıyla :)"
            }
        }';

        $this->sendJson(json_decode($json,true));
    }

    

    private function sendJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
