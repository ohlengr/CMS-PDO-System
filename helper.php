<?php
//get complete base url
function base_url($path = ""){
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=="off")?'https://':'http://';
    $host = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . $host . '/' . PROJECT_DIRECTORY;
    return $baseUrl . '/' . ltrim($path,'/');
}
//get complete base path
function base_path($path = ""){
    $rootPath = dirname(__DIR__)  . DIRECTORY_SEPARATOR . PROJECT_DIRECTORY;
    return $rootPath . DIRECTORY_SEPARATOR . ltrim($path,DIRECTORY_SEPARATOR);
}
//get complete upload path
function upload_path($fileName = ""){
    return base_path("uploads" . DIRECTORY_SEPARATOR . $fileName);
}
//get complete upload file url
function upload_url($fileName = ""){
    return base_url("uploads/" . ltrim($fileName,'/'));
}
//get complete asset url
function asset_url($path = ''){
    return base_url('assets/') . ltrim($path,'/');
}
//redirect helper
function redirect($url){
    header('Location: '.base_url($url));
    exit;
}
//check if form submited using post request
function isPostRequest(){
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST';
}
//get post data
function getPostData($field, $default=null){
    return isset($_POST[$field]) ? trim($_POST[$field]) : $default;
}
//format date
function formatCreatedAt($date){
    return date('F j, Y',strtotime($date));
}
//check login status of admin
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    }
    return false;
}
//check login status of admin
function checkUserLoggedIn(){
    if(session_status()===PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['user_id'])){
        redirect("login.php");
    }
}
?>