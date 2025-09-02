<?php
require_once __DIR__.'/init.php';
checkUserLoggedIn();
if(isPostRequest()){
    $id = $_POST['id'];
    $article = new Article();
    if($article->deleteWithImage($id)){
        redirect("admin.php");
    }else{
        echo "Failed to Delete Article";
    }
}
?>