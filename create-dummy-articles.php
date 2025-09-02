<?php
require_once __DIR__ . '/init.php';

if(isPostRequest()) {
    $article = new Article();
    $count = isset($_POST['article_count']) ? (int)$_POST['article_count'] : 0;
    if($article->generateDummyData($count)){
        redirect('admin.php');
    }else{
        echo "Error generating dummy articles.";
    }
}