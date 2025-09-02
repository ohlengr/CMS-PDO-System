<?php
include 'partials/admin/header.php';
include 'partials/admin/navbar.php';
$article = new Article();
$userArticles = $article->get_articles_by_user($_SESSION['user_id']);
?>
    <!-- Main Content -->
    <main class="container my-5">
        <h2 class="mb-4">Welcome <?php echo $_SESSION['username']; ?> to your Admin Dashboard</h2>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <form class="d-flex justify-content-between align-items-center" action="<?php echo base_url('create-dummy-articles.php'); ?>" method="POST">
                <label for="artcileCount" class="form-label me-2">Number of articles</label>
                <input type="number" id="articleCount" class="form-control me-2" name="article_count" style="width:100px;" />
                <button class="btn btn-primary btn-sm" type="submit">Generate Articles</button>
            </form>
            <form action="<?php echo base_url('reorder-articles.php'); ?>" method="POST">
                <button name="reorder_articles" class="btn btn-secondary mb-3" type="submit">Reorder Article ID's</button>
            </form>
            <button id="deleteSelectedBtn" class="btn btn-danger mb-3">Delete Selected Articles</button>
        </div>

        <!-- Articles Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Published Date</th>
                        <th>Excerpt</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Ajax Delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($userArticles)):
                    foreach($userArticles as $row):
                ?>
                    <tr>
                        <td><input type="checkbox" class="articleCheckbox" value="<?php echo $row->id; ?>"></td>
                        <td><?php echo $row->id; ?></td>
                        <td><?php echo $row->title; ?></td>
                        <td><?php echo $_SESSION['username']; ?></td>
                        <td><?php echo formatCreatedAt($row->created_at); ?></td>
                        <td><?php echo $article->get_excerpt($row->content,50); ?></td>
                        <td>
                            <a href="edit-article.php?id=<?php echo $row->id; ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                        </td>
                        <td>
                            <form onsubmit="confirmDelete(<?php echo $row->id; ?>);" method="POST" action="<?php echo base_url('delete_article.php'); ?>">
                                <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                <!-- <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row->id; ?>)">Delete</button> -->
                            </form>
                        </td>
                        <td>
                            <button data-id="<?php echo $row->id; ?>" class="btn btn-sm btn-danger delete-single" style="width:100px;">Ajax Delete</button>
                        </td>
                    </tr>
                <?php
                    endforeach;
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </main>
<?php
include 'partials/admin/footer.php';
?>

<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.articleCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', function() {
        let selectedIds = [];
        let checkboxes = document.querySelectorAll('.articleCheckbox:checked');
        checkboxes.forEach(checkbox => {
            selectedIds.push(checkbox.value);
        });

        if(selectedIds.length === 0) {
            alert('Please select at least one article to delete.');
            return;
        }

        if(confirm('Are you sure you want to delete the selected articles?')) {
            sendDeleteRequest(selectedIds);
        }
    });

    document.querySelectorAll('.delete-single').forEach(button => {
        button.addEventListener('click', function() {
            let articleId = this.getAttribute('data-id');
            if(confirm('Are you sure you want to delete this article ' + articleId +' ?')) {
                sendDeleteRequest([articleId]);
            }
        });
    });

    function sendDeleteRequest(ids) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo base_url('ajax-delete-article.php'); ?>', true);
        xhr.setRequestHeader('Content-Type','aplication/json');
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if(response.success) {
                    alert('Articles deleted successfully.');
                    location.reload();
                } else {
                    alert('Error deleting articles: ' + response.message);
                }
            }
        };
        xhr.send(JSON.stringify({ids: ids}));
    }
</script>