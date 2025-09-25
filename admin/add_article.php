<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$conn = getDBConnection();
$message = '';

// Get categories
$categories_query = "SELECT * FROM categories ORDER BY category_name";
$categories = $conn->query($categories_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $image_url = sanitizeInput($_POST['image_url']);
    $author_id = $_SESSION['user_id'];
    
    if (!empty($title) && !empty($content) && $category_id > 0) {
        $query = "INSERT INTO articles (title, content, image_url, category_id, author_id, published_date) 
                 VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssii", $title, $content, $image_url, $category_id, $author_id);
        
        if ($stmt->execute()) {
            $message = 'Article added successfully!';
        } else {
            $message = 'Error adding article.';
        }
    } else {
        $message = 'Please fill in all required fields.';
    }
}

$page_title = "Add Article";
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
        <nav class="admin-sidebar">
            <div class="admin-logo">
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <!-- <li><a href="manage_articles.php">Manage Articles</a></li> -->
                <li><a href="add_article.php" class="active">Add Article</a></li>
                <!-- <li><a href="manage_categories.php">Categories</a></li> -->
                <!-- <li><a href="manage_users.php">Users</a></li> -->
                <li><a href="../index.php">Back to Site</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Add New Article</h1>
            </header>

            <?php if ($message): ?>
                <div class="alert <?php echo strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form class="admin-form" method="POST">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php while($category = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL</label>
                    <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                </div>

                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" rows="15" required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Article</button>
                    <a href="manage_articles.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>