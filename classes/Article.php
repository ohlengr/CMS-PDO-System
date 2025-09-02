<?php
class Article{
    private $conn;
    private $table = 'articles';
    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function get_all(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function get_excerpt($content,$length=100){
        if(strlen($content) > $length){
            return substr($content,0,$length) . '.....';
        }
        return $content;
    }
    public function get_article_by_id($id){
        $query = "SELECT " . $this->table . ".*,users.username as auther, users.email as auther_email FROM " . $this->table . " JOIN users ON users.id=" . $this->table . ".user_id WHERE " . $this->table . ".id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_OBJ);
        if($article){
            if($article->user_id == $_SESSION['user_id']){
                return $article;
            }else{
                redirect("admin.php");
            }
        }
        return false;
    }
    public function deleteWithImage($id){
        $article = $this->get_article_by_id($id);
        if($article){
            if($article->user_id == $_SESSION['user_id']){
                if(!empty($article->image) && file_exists($article->image)){
                    if(!unlink($article->image)){
                        return false;
                    }
                }
                $query = "DELETE FROM " . $this->table . " WHERE id=:id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":id",$id, PDO::PARAM_INT);
                return $stmt->execute();
            }else{
                redirect("admin.php");
            }
        }
        return false;
    }
    public function get_articles_by_user($userId){
        $query = "SELECT * FROM " . $this->table . " WHERE user_id=:user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function create($title, $content, $author_id, $created_at, $image){
        $query = "INSERT INTO " . $this->table . " SET `title`=:title, `content`=:content, `user_id`=:user_id, `created_at`=:created_at, `image`=:image";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title",$title);
        $stmt->bindParam(":content",$content);
        $stmt->bindParam(":user_id",$author_id);
        $stmt->bindParam(":created_at",$created_at);
        $stmt->bindParam(":image",$image);
        return $stmt->execute();
    }
    public function uploadImage($file){
        $targetDir = 'uploads/';
        if(!is_dir($targetDir)){
            mkdir($targetDir,0755,true);
        }
        if(isset($file) && $file['error']===0){
            $targetFile = $targetDir . basename($file['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $extLen = strlen($imageFileType);
            $targetFile = substr($targetFile,0,-($extLen+1));
            $allowedTypes = ['jpg','jpeg','png'];
            if(in_array($imageFileType,$allowedTypes)){
                $uniqueFileName = uniqid() . "_" . time() . "." .$imageFileType;
                $targetFile = $targetFile ."_". $uniqueFileName;
                if(move_uploaded_file($file['tmp_name'], $targetFile)){
                    return $targetFile;
                }else{
                    return "There was error uploading the file";
                }
            }else{
                return "There was error only jpg, jpeg and png files are allowed";
            }
        }
        return '';
    }
    public function update($articleId, $title, $content, $created_at, $imagePath = null){
        $query = "UPDATE " . $this->table . " SET `title`=:title, `content`=:content, `created_at`=:created_at ";
        if($imagePath!=null){
            $query.=", `image`=:image ";
        }
        $query.=" WHERE id=:id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title",$title);
        $stmt->bindParam(":content",$content);
        $stmt->bindParam(":created_at",$created_at);
        if($imagePath!=null){
            $stmt->bindParam(":image",$imagePath);
        }
        $stmt->bindParam(":id",$articleId);
        return $stmt->execute();
    }
    public function generateDummyData($num = 0){
        $query = "INSERT INTO " . $this->table . " SET `title`=:title, `content`=:content, `user_id`=:user_id, `created_at`=:created_at, `image`=:image";
        $dummy_titles = [
            "The Rise of AI: How Artificial Intelligence is Changing the World",
            "10 Tips for a Healthier Lifestyle",
            "Exploring the Wonders of the Universe",
            "The Future of Work: Remote vs. In-Office",
            "Top 5 Travel Destinations for 2024",
            "The Impact of Social Media on Society",
            "Sustainable Living: Small Changes, Big Impact",
            "The Art of Mindfulness: Finding Peace in a Busy World",
            "Tech Innovations to Watch in the Next Decade",
            "The Power of Positive Thinking"
        ];
        $dummy_contents = [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
            "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
            "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra.",
            "Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh.",
            "Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc.",
            "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.",
            "Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.",
            "Aenean ultricies mi vitae est. Mauris placerat eleifend leo."
        ];
        $author_id = $_SESSION['user_id'];
        $image = '';
        for($i=0; $i<$num; $i++){
            $title = $dummy_titles[array_rand($dummy_titles)];
            $content = $dummy_contents[array_rand($dummy_contents)];
            $created_at = date('Y-m-d');
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":title",$title);
            $stmt->bindParam(":content",$content);
            $stmt->bindParam(":user_id",$author_id);
            $stmt->bindParam(":created_at",$created_at);
            $stmt->bindParam(":image",$image);
            if(!$stmt->execute()){
                return false;
            }
        }
        return true;
    }
    public function reorderArticles(){
        try {
            $this->conn->beginTransaction();
            $query = "SELECT id FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_OBJ);
            $count = 1;
            foreach($articles as $article){
                $updateQuery = "UPDATE " . $this->table . " SET id = :new_id WHERE id = :current_id;";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':new_id', $count, PDO::PARAM_INT);
                $updateStmt->bindParam(':current_id', $article->id, PDO::PARAM_INT);
                $updateStmt->execute();
                $count++;
            }
            //Reset the auto-increment to the next value after the last updated id
            $resetQuery = "ALTER TABLE " . $this->table . " AUTO_INCREMENT = :next_id;";
            $resetStmt = $this->conn->prepare($resetQuery);
            $resetStmt->bindParam(':next_id', $count, PDO::PARAM_INT);
            $resetStmt->execute();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>