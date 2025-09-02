<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo base_url("index.php"); ?>">CMS PDO System - Admin</a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div
            class="collapse navbar-collapse"
            id="navbarNav"
        >
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo base_url("admin.php"); ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("create-article.php"); ?>">Create Article</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("profile.php"); ?>">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("index.php"); ?>">View Site</a>
                </li>
                <li class="nav-item">
                    <form method="post" action="<?php echo base_url("logout.php"); ?>">
                        <button type="submit" class="nav-link">Logout</a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>