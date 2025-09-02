<?php
require_once 'partials/header.php';
include 'partials/navbar.php';
include 'partials/hero.php';
$article = new Article();
$articles = $article->get_all();
?>
    <!-- Main Content -->
    <main class="container my-5">
    <?php
    if(!empty($articles)):
        foreach($articles as $item):
    ?>
        <div class="row mb-4">
            <div class="col-md-4">
                <?php if(!empty($item->image)): ?>
                <a href="article.php?id=<?php echo $item->id ?>">
                    <img
                        src="<?php echo htmlspecialchars($item->image); ?>"
                        class="img-fluid"
                        alt="Blog Post Image"
                        style="width: 350px; height: 200px;"
                    >
                </a>
                <?php else: ?>
                <a href="article.php?id=<?php echo $item->id ?>">
                    <img
                        src="https://picsum.photos/500/300"
                        class="img-fluid"
                        alt="Blog Post Image"
                        style="width: 350px; height: 200px;"
                    >
                </a>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($item->title); ?></h2>
                <?php echo htmlspecialchars($article->get_excerpt($item->content,50)); ?>
                <div>
                    <a href="article.php?id=<?php echo $item->id ?>" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    <?php
        endforeach;
    endif;
    ?>
    </main>
<?php
include 'partials/footer.php';
?>