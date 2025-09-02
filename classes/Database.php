<?php
class Database{
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    public $conn;

    //database PDO connection function
    public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}",$this->db_user,$this->db_pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $error){
            die("Connection Failed" . $error->getMessage());
        }
        return $this->conn;
    }
}
?>