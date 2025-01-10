<?php
require_once 'database.php';

class Setup {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function runSQLFromFile($filePath) {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("File không tồn tại: $filePath");
            }
            $sql = file_get_contents($filePath);
            if ($sql === false) {
                throw new Exception("Không thể đọc file SQL: $filePath");
            }
            $this->conn->exec($sql);
            echo "File SQL đã được thực thi thành công: " . basename($filePath) . "\n";

        } catch(PDOException $e) {
            echo "Lỗi thực thi SQL (" . basename($filePath) . "): " . $e->getMessage() . "\n";
        } catch(Exception $e) {
            echo "Lỗi: " . $e->getMessage() . "\n";
        }
    }
}

try {
    $setup = new Setup();
    $schemaFile = 'D:\xampp\htdocs\LibraryManagementSystem\database\migrations\schema.sql';
    $dataFile = 'D:\xampp\htdocs\LibraryManagementSystem\database\seeders\data.sql';
    $updateDataFile = 'D:\xampp\htdocs\LibraryManagementSystem\database\seeders\phuocdata.sql';
    $updateFile = 'D:\xampp\htdocs\LibraryManagementSystem\database\migrations\update.sql';
    $accountFile = 'D:\xampp\htdocs\LibraryManagementSystem\database\migrations\adminAccount.sql';
    $setup->runSQLFromFile($schemaFile);
   // $setup->runSQLFromFile($updateFile);
    $setup->runSQLFromFile($dataFile);
   // $setup->runSQLFromFile($updateDataFile);
   // $setup->runSQLFromFile($accountFile);
  
} catch(Exception $e) {
    echo "Lỗi khởi tạo: " . $e->getMessage() . "\n";
}
?>