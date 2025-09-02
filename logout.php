<?php
require_once __DIR__."/init.php";
if(isPostRequest()){
    session_destroy();
    redirect("index.php");
    exit;
}else{
    redirect("index.php");
    exit;
}
?>