<?php
require_once __DIR__ . '/init.php';

if(isPostRequest()) {
    $article = new Article();
    if(isset($_POST['reorder_articles'])) {
        try {
            $article->reorderArticles();
            redirect('admin.php');
        } catch (Exception $e) {
            redirect('admin.php');
        }
    }
}