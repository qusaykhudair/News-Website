<?php
require_once 'config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$category_id = (int)$_GET['id'];
$conn = getDBConnection();

// Get category details
$category_query = "SELECT * FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($category_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category_result = $stmt->get_result();
$category = $category_result->fetch_assoc();

if (!$category) {
    header('Location: index.php');
    exit;
}

$page_title = $category['category_name'];

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 8;
$offset = ($page - 1) * $per_page;

// Get total articles count
$count_query = "SELECT COUNT(*) as total FROM articles WHERE category_id = ?";
$stmt = $conn->prepare($count_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$count_result = $stmt->get_result();
$total_articles = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_articles / $per_page);

// Get articles for this category
$articles_query = "SELECT a.*, c.category_name, u.username as author_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  LEFT JOIN users u ON a.author_id = u.user_id 
                  WHERE a.category_id = ? 
                  ORDER BY a.published_date DESC 
                  LIMIT ? OFFSET ?";
$stmt = $conn->prepare($articles_query);
$stmt->bind_param("iii", $category_id, $per_page, $offset);
$stmt->execute();
$articles_result = $stmt->get_result();

require_once 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="category-header">
            <h1><?php echo htmlspecialchars($category['category_name']); ?> News</h1>
            <p>Latest news and articles in <?php echo htmlspecialchars($category['category_name']); ?></p>
        </div>

        <div class="articles-grid">
            <?php while($article = $articles_result->fetch_assoc()): ?>
            <article class="article-card">
                <div class="article-image">
                    <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                    <span class="category-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                </div>
                <div class="article-content">
                    <h3><a href="article.php?id=<?php echo $article['article_id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
                    <p><?php echo substr(strip_tags($article['content']), 0, 150) . '...'; ?></p>
                    <div class="article-meta">
                        <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                        <span><?php echo date('M j, Y', strtotime($article['published_date'])); ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?id=<?php echo $category_id; ?>&page=<?php echo $page-1; ?>" class="btn btn-secondary">Previous</a>
            <?php endif; ?>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?id=<?php echo $category_id; ?>&page=<?php echo $i; ?>" 
                   class="btn <?php echo $i == $page ? 'btn-primary' : 'btn-secondary'; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <a href="?id=<?php echo $category_id; ?>&page=<?php echo $page+1; ?>" class="btn btn-secondary">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php
$conn->close();
require_once 'includes/footer.php';
?>