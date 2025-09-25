<?php
$page_title = "Home";
require_once 'config/database.php';
require_once 'includes/header.php';

$conn = getDBConnection();


$breaking_query = "SELECT a.*, c.category_name, u.username as author_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  LEFT JOIN users u ON a.author_id = u.user_id 
                  ORDER BY a.published_date DESC LIMIT 1";
$breaking_result = $conn->query($breaking_query);
$breaking_news = $breaking_result->fetch_assoc();


$featured_query = "SELECT a.*, c.category_name, u.username as author_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  LEFT JOIN users u ON a.author_id = u.user_id 
                  ORDER BY a.published_date DESC LIMIT 3 OFFSET 1";
$featured_result = $conn->query($featured_query);


$latest_query = "SELECT a.*, c.category_name, u.username as author_name 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.category_id 
                LEFT JOIN users u ON a.author_id = u.user_id 
                ORDER BY a.published_date DESC LIMIT 6 OFFSET 4";
$latest_result = $conn->query($latest_query);


$trending_query = "SELECT a.*, c.category_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  ORDER BY RAND() LIMIT 5";
$trending_result = $conn->query($trending_query);
?>

<main class="main-content">
    <div class="container">

        <?php if ($breaking_news): ?>
            <section class="breaking-news">
                <div class="breaking-header">
                    <span class="breaking-label">Breaking News</span>
                </div>
                <article class="breaking-article">
                    <div class="breaking-image">
                        <img src="<?php echo htmlspecialchars($breaking_news['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($breaking_news['title']); ?>">
                    </div>
                    <div class="breaking-content">
                        <span class="category-tag"><?php echo htmlspecialchars($breaking_news['category_name']); ?></span>
                        <h2><a
                                href="article.php?id=<?php echo $breaking_news['article_id']; ?>"><?php echo htmlspecialchars($breaking_news['title']); ?></a>
                        </h2>
                        <p><?php echo substr(strip_tags($breaking_news['content']), 0, 200) . '...'; ?></p>
                        <div class="article-meta">
                            <span>By <?php echo htmlspecialchars($breaking_news['author_name'] ?? 'Admin'); ?></span>
                            <span><?php echo date('M j, Y', strtotime($breaking_news['published_date'])); ?></span>
                        </div>
                    </div>
                </article>
            </section>
        <?php endif; ?>

        <div class="content-wrapper">
            <div class="main-column">

                <section class="featured-section">
                    <h2>Featured Articles</h2>
                    <div class="featured-grid">
                        <?php while ($article = $featured_result->fetch_assoc()): ?>
                            <article class="featured-article">
                                <div class="article-image">
                                    <img src="<?php echo htmlspecialchars($article['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <span
                                        class="category-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                                </div>
                                <div class="article-content">
                                    <h3><a
                                            href="article.php?id=<?php echo $article['article_id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a>
                                    </h3>
                                    <p><?php echo substr(strip_tags($article['content']), 0, 120) . '...'; ?></p>
                                    <div class="article-meta">
                                        <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                                        <span><?php echo date('M j, Y', strtotime($article['published_date'])); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>


                <section class="latest-section">
                    <h2>Latest News</h2>
                    <div class="articles-grid">
                        <?php while ($article = $latest_result->fetch_assoc()): ?>
                            <article class="article-card">
                                <div class="article-image">
                                    <img src="<?php echo htmlspecialchars($article['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <span
                                        class="category-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                                </div>
                                <div class="article-content">
                                    <h3><a
                                            href="article.php?id=<?php echo $article['article_id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a>
                                    </h3>
                                    <p><?php echo substr(strip_tags($article['content']), 0, 100) . '...'; ?></p>
                                    <div class="article-meta">
                                        <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                                        <span><?php echo date('M j, Y', strtotime($article['published_date'])); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>
            </div>


            <aside class="sidebar">
                <div class="sidebar-widget">
                    <h3>Trending News</h3>
                    <div class="trending-list">
                        <?php while ($trending = $trending_result->fetch_assoc()): ?>
                            <article class="trending-item">
                                <div class="trending-image">
                                    <img src="<?php echo htmlspecialchars($trending['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($trending['title']); ?>">
                                </div>
                                <div class="trending-content">
                                    <span
                                        class="category-tag small"><?php echo htmlspecialchars($trending['category_name']); ?></span>
                                    <h4><a
                                            href="article.php?id=<?php echo $trending['article_id']; ?>"><?php echo htmlspecialchars($trending['title']); ?></a>
                                    </h4>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <h3>Newsletter</h3>
                    <p>Stay updated with our latest news and articles.</p>
                    <form class="newsletter-form" action="subscribe.php" method="POST"><br>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>

                <div class="sidebar-widget">
                    <h3>Advertisement</h3>
                    <div class="ad-placeholder">
                        <p>Advertisement Space</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php
$conn->close();
require_once 'includes/footer.php';
?>