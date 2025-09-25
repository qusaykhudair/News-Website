<?php
require_once 'config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$article_id = (int) $_GET['id'];
$conn = getDBConnection();

// Get article details
$article_query = "SELECT a.*, c.category_name, u.username as author_name 
                 FROM articles a 
                 LEFT JOIN categories c ON a.category_id = c.category_id 
                 LEFT JOIN users u ON a.author_id = u.user_id 
                 WHERE a.article_id = ?";
$stmt = $conn->prepare($article_query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    header('Location: index.php');
    exit;
}

$page_title = $article['title'];

// Get comments
$comments_query = "SELECT c.*, u.username 
                  FROM comments c 
                  LEFT JOIN users u ON c.user_id = u.user_id 
                  WHERE c.article_id = ? 
                  ORDER BY c.timestamp DESC";
$stmt = $conn->prepare($comments_query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$comments_result = $stmt->get_result();

// Get related articles
$related_query = "SELECT a.*, c.category_name 
                 FROM articles a 
                 LEFT JOIN categories c ON a.category_id = c.category_id 
                 WHERE a.category_id = ? AND a.article_id != ? 
                 ORDER BY a.published_date DESC LIMIT 4";
$stmt = $conn->prepare($related_query);
$stmt->bind_param("ii", $article['category_id'], $article_id);
$stmt->execute();
$related_result = $stmt->get_result();

require_once 'includes/header.php';
?>

<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    <main class="main-content">
        <div class="container">
            <div class="article-wrapper">
                <article class="single-article">
                    <header class="article-header">
                        <span class="category-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                        <div class="article-meta">
                            <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                            <span><?php echo date('F j, Y \a\t g:i A', strtotime($article['published_date'])); ?></span>
                        </div>
                    </header>

                    <div class="article-image">
                        <img src="<?php echo htmlspecialchars($article['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($article['title']); ?>">
                    </div>

                    <div class="article-content">
                        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                    </div>

                    <div class="article-footer">
                        <div class="share-buttons">
                            <span>Share this article:</span>
                            <a href="#" class="share-btn whatsapp" aria-label="Share on Facebook">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="#" class="share-btn twitter" aria-label="Share on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="share-btn linkedin" aria-label="Share on LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>

                </article>


                <section class="comments-section">
                    <h3>Comments (<?php echo $comments_result->num_rows; ?>)</h3>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form class="comment-form" action="add_comment.php" method="POST">
                            <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                            <textarea name="comment_text" placeholder="Write your comment..." required></textarea>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    <?php else: ?>
                        <p class="login-prompt">
                            <a href="login.php">Login</a> to post a comment.
                        </p>
                    <?php endif; ?>

                    <div class="comments-list">
                        <?php while ($comment = $comments_result->fetch_assoc()): ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <strong><?php echo htmlspecialchars($comment['username'] ?? 'Anonymous'); ?></strong>
                                    <span
                                        class="comment-date"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['timestamp'])); ?></span>
                                </div>
                                <div class="comment-content">
                                    <?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>


                <?php if ($related_result->num_rows > 0): ?>
                    <section class="related-articles">
                        <h3>Related Articles</h3>
                        <div class="related-grid">
                            <?php while ($related = $related_result->fetch_assoc()): ?>
                                <article class="related-article">
                                    <div class="article-image">
                                        <img src="<?php echo htmlspecialchars($related['image_url']); ?>"
                                            alt="<?php echo htmlspecialchars($related['title']); ?>">
                                    </div>
                                    <div class="article-content">
                                        <span
                                            class="category-tag small"><?php echo htmlspecialchars($related['category_name']); ?></span>
                                        <h4><a
                                                href="article.php?id=<?php echo $related['article_id']; ?>"><?php echo htmlspecialchars($related['title']); ?></a>
                                        </h4>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>


<?php
$conn->close();
require_once 'includes/footer.php';
?>