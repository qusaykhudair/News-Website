<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$conn = getDBConnection();
$message = '';
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch article to confirm existence
$article = null;
if ($article_id > 0) {
    $stmt = $conn->prepare("SELECT title FROM articles WHERE article_id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
    $stmt->close();

    if (!$article) {
        die("Article not found.");
    }
} else {
    die("Invalid article ID.");
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $stmt = $conn->prepare("DELETE FROM articles WHERE article_id = ?");
    $stmt->bind_param("i", $article_id);
    
    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        $message = "Failed to delete article: " . $stmt->error;
    }
    $stmt->close();
}

$page_title = "Delete Article";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - Global News Network</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="admin-wrapper">
    <nav class="admin-sidebar">
        <div class="admin-logo"><h2>Admin Panel</h2></div>
        <ul class="admin-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <!-- <li><a href="manage_articles.php">Manage Articles</a></li> -->
            <li><a href="add_article.php">Add Article</a></li>
            <!-- <li><a href="manage_categories.php">Categories</a></li> -->
            <!-- <li><a href="manage_users.php">Users</a></li> -->
            <li><a href="../index.php">Back to Site</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Delete Article</h1>
        </header>

        <?php if ($message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php else: ?>
            <div class="alert alert-warning">
                <p>Are you sure you want to permanently delete the article titled: 
                    <strong><?php echo htmlspecialchars($article['title']); ?></strong>?</p>
                <form method="post">
                    <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>

<?php $conn->close(); ?>
