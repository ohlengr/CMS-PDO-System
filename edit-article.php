<?php
include 'partials/admin/header.php';
include 'partials/admin/navbar.php';

$articleId = isset($_GET['id']) ? (int)$_GET['id'] : null;

$article = new Article();
$articleData = $article->get_article_by_id($articleId);

if(isPostRequest()){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $created_at = $_POST['date'];
    $imagePath = $article->uploadImage($_FILES['featured_image']);
    if(strpos($imagePath,'error') == false){
        $title = $_POST['title'];
        $created_at = $_POST['date'];
        $content = $_POST['content'];
        if($article->update($articleId, $title, $content, $created_at, $imagePath)){
            redirect("admin.php");
        }else{
            echo "Failed Creating Article";
        }
    }
}

?>
    <!-- Main Content -->
    <main class="container my-5">
        <h2>Update Article</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Article Title *</label>
                <input type="text" name="title" class="form-control" id="title" value="<?php echo $articleData->title; ?>" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Published Date *</label>
                <input type="date" name="date" class="form-control" id="date" value="<?php echo htmlspecialchars(date('Y-m-d',strtotime($articleData->created_at))); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content *</label>
                <textarea class="form-control" name="content" id="content" rows="10" required><?php echo $articleData->content; ?></textarea>
            </div>
            <?php
            if(!empty($articleData->image)):
            ?>
            <div class="mb-3">
                <label for="image" class="form-label">Current Featured Image</label><br>
                <img src="<?php echo htmlspecialchars($articleData->image); ?>" class="img-fluid" style="width:100px;" alt="<?php echo htmlspecialchars(strtolower($articleData->title)); ?>" />
            </div>
            <?php
            endif;
            ?>
            <div class="mb-3">
                <label for="image" class="form-label">Featured Image URL</label>
                <input type="file" name="featured_image" class="form-control" id="image">
            </div>
            <button type="submit" class="btn btn-primary">Update Article</button>
            <a href="admin.php" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </main>
<?php
include 'partials/admin/footer.php';
?>