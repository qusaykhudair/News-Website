<?php
require_once 'config/database.php';

$search_query = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$page_title = "Search Results";

if (empty($search_query)) {
    header('Location: index.php');
    exit;
}

$conn = getDBConnection();

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total results count
$count_query = "SELECT COUNT(*) as total FROM articles 
               WHERE title LIKE ? OR content LIKE ?";
$search_term = "%{$search_query}%";
$stmt = $conn->prepare($count_query);
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$count_result = $stmt->get_result();
$total_results = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_results / $per_page);

// Get search results
$search_results_query = "SELECT a.*, c.category_name, u.username as author_name 
                        FROM articles a 
                        LEFT JOIN categories c ON a.category_id = c.category_id 
                        LEFT JOIN users u ON a.author_id = u.user_id 
                        WHERE a.title LIKE ? OR a.content LIKE ? 
                        ORDER BY a.published_date DESC 
                        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($search_results_query);
$stmt->bind_param("ssii", $search_term, $search_term, $per_page, $offset);
$stmt->execute();
$results = $stmt->get_result();

require_once 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="search-header">
            <h1>Search Results</h1>
            <p>Found <?php echo $total_results; ?> results for "<?php echo htmlspecialchars($search_query); ?>"</p>
        </div>

        <?php if ($total_results > 0): ?>
            <div class="search-results">
                <?php while ($article = $results->fetch_assoc()): ?>
                    <article class="search-result">
                        <div class="result-image">
                            <img src="<?php echo htmlspecialchars($article['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($article['title']); ?>">
                        </div>
                        <div class="result-content">
                            <span class="category-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                            <h3><a
                                    href="article.php?id=<?php echo $article['article_id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a>
                            </h3>
                            <p><?php echo substr(strip_tags($article['content']), 0, 200) . '...'; ?></p>
                            <div class="article-meta">
                                <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                                <span><?php echo date('M j, Y', strtotime($article['published_date'])); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $page - 1; ?>"
                            class="btn btn-secondary">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>"
                            class="btn <?php echo $i == $page ? 'btn-primary' : 'btn-secondary'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $page + 1; ?>"
                            class="btn btn-secondary">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="no-results">
                <h3>No results found</h3>
                <p>Try different keywords or browse our categories.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
$conn->close();
require_once 'includes/footer.php';
?>