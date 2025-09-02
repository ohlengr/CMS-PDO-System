<?php
require_once 'partials/header.php';
include 'partials/navbar.php';
$articleData = [];
$articleId = isset($_GET['id']) ? $_GET['id'] : 0;
if($articleId>0){
    $article = new Article();
    $articleData = $article->get_article_by_id($articleId);
}else{
    echo "Article not found";
    exit;
}

if($articleData):
?>
    <!-- Article Header -->
    <header class="bg-dark text-white py-5">
        <div class="container">
            <h1 class="display-4"><?php echo $articleData->title; ?></h1>
            <p class="lead">
                
            </p>
            <p>
                <small>
                    By <a href="#" class="text-white text-decoration-underline"><?php echo htmlspecialchars($articleData->auther); ?></a> <!-- Add Author Name Here -->
                    |
                    <span>Published on <?php echo formatCreatedAt($articleData->created_at); ?></span> <!-- Date Published -->
                </small>
            </p>
        </div>
    </header>


    <!-- Main Content -->
    <main class="container my-5">
        <!-- Featured Image -->
        <div class="mb-4">
            <?php if(!empty($articleData->image)): ?>
            <img
                src="<?= htmlspecialchars($articleData->image); ?>"
                class="img-fluid w-100"
                alt="Featured Image"
                style="max-height:600px;"
            >
            <?php else: ?>
            <img
                src="https://picsum.photos/1200/600"
                class="img-fluid w-100"
                alt="Featured Image"
                style="max-height:600px;"
            >
            <?php endif; ?>
        </div>
        <!-- Article Content -->
        <article>
            <?php echo $articleData->content; ?>
        </article>

        <!-- Comments Section Placeholder -->
        <section class="mt-5">
            <h3>Comments</h3>
            <p>
                <!-- Placeholder for comments -->
                Comments functionality will be implemented here.
            </p>
        </section>

        <!-- Back to Home Button -->
        <div class="mt-4">
            <a href="<?php echo base_url("index.php"); ?>" class="btn btn-secondary">← Back to Home</a>
        </div>
    </main>
<?php
endif;

include 'partials/footer.php';
?>
