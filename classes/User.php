<?php
class User{
    private $conn;
    private $table = 'users';

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function register($username, $email, $password){
        $query = "INSERT INTO " . $this->table . " SET username=:username, email=:email, password=:password";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':username',$username);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':password',$hashedPassword);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function login($email,$password){
        $query = "SELECT * FROM " . $this->table . " WHERE email=:email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email',$email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if($user && password_verify($password,$user->password)){
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['email'] = $user->email;
            return true;
        }
        return false;
    }
    public function isLoggedIn(){
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true;
    }
}
?>