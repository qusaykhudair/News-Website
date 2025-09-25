<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$conn = getDBConnection();

// Get statistics
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM articles) as total_articles,
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM comments) as total_comments,
    (SELECT COUNT(*) FROM categories) as total_categories";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get recent articles
$recent_articles_query = "SELECT a.*, c.category_name 
                         FROM articles a 
                         LEFT JOIN categories c ON a.category_id = c.category_id 
                         ORDER BY a.published_date DESC LIMIT 5";
$recent_articles = $conn->query($recent_articles_query);

$page_title = "Admin Dashboard";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Global News Network</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="admin-wrapper">

        <input type="checkbox" id="sidebar-toggle" hidden>
        <label for="sidebar-toggle" class="sidebar-toggle-label">☰ Menu</label>

        <nav class="admin-sidebar">
            <div class="admin-logo">
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="add_article.php">Add Article</a></li>
                <li><a href="../index.php">Back to Site</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>


        <main class="admin-content">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Articles</h3>
                    <div class="stat-number"><?php echo $stats['total_articles']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="stat-number"><?php echo $stats['total_users']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Comments</h3>
                    <div class="stat-number"><?php echo $stats['total_comments']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Categories</h3>
                    <div class="stat-number"><?php echo $stats['total_categories']; ?></div>
                </div>
            </div>

            <div class="admin-section">
                <h2>Recent Articles</h2>
                <div class="admin-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Published</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($article = $recent_articles->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                                    <td><?php echo htmlspecialchars($article['category_name']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($article['published_date'])); ?></td>
                                    <td>
                                        <a href="editearticle.php?id=<?php echo $article['article_id']; ?>"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="../article.php?id=<?php echo $article['article_id']; ?>"
                                            class="btn btn-sm btn-primary">View</a>
                                        <a href="delete_article.php?id=<?php echo $article['article_id']; ?>"
                                            class="btn btn-sm btn-secondary">delete</a>

                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

<?php $conn->close(); ?>