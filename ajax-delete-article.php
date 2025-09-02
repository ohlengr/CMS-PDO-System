<?php
require_once __DIR__ . '/init.php';
header('Content-Type: aplication/json');
if(isPostRequest()){
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['ids']) && is_array($data['ids'])){
        $article = new Article();
        $allDeleted = true;
        foreach($data['ids'] as $id){
            if(!$article->deleteWithImage((int)$id)){
                $allDeleted = false;
            }
        }
        if($allDeleted){
            echo json_encode(['success' => true, 'message' => 'Articles deleted successfully.']);
        }else{
            echo json_encode(['success' => false, 'message' => 'Some articles could not be deleted.']);
        }
    }else{
        echo json_encode(['success' => false, 'message' => 'Invalid request. No article IDs provided.']);
    }
}
?>