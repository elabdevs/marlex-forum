<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriler - <?= $siteTitle ?></title>
    <link rel="stylesheet" href="/assets/css/css.php?file=styles.css" id="mainCSS" media="all">
    <link rel="stylesheet" href="/assets/css/css.php?file=lite.min.css" id="liteCSS" media="none">
</head>
<body>
    <!-- Animated background orbs -->
    <div class="liquid-orb orb-1"></div>
    <div class="liquid-orb orb-2"></div>
    <div class="liquid-orb orb-3"></div>
    <div class="liquid-orb orb-4"></div>

    <!-- Header -->

    <?php include($header); ?>

    <main class="main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Kategoriler</h1>
                <p class="page-description">Tüm konuları keşfedin ve topluluğunuzu bulun</p>
            </div>

            <div class="categories-expanded-grid">
                <?php foreach ($categories as $category): ?>
                    <a href="/categories/<?= $category['slug'] ?>" class="text-none">
                <div class="category-card-expanded liquid-glass liquid-glass-card">
                    <div class="category-header">
                        <div class="category-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="category-info">
                            <h3 class="category-title"><?= $category['name'] ?></h3>
                            <p class="category-description"><?= $category['description'] ?></p>
                        </div>
                    </div>
                    <div class="category-stats-expanded">
                        <div class="stat-item">
                            <span class="stat-number"><?= $category['topic_count'] ?></span>
                            <span class="stat-label">Konu</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">12,456</span>
                            <span class="stat-label">Posts</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">456</span>
                            <span class="stat-label">Members</span>
                        </div>
                    </div>
                    <div class="category-latest">
                        <div class="latest-post">
                            <span class="latest-title">Latest: <?= $category['latest_topic_title'] ?></span>
                            <span class="latest-author">Yazar: <?= $category['latest_topic_author'] ?></span>
                            <span class="latest-time"><?= $category['latest_topic_date'] ?></span>
                        </div>
                    </div>
                </div>
                </a>
                <?php endforeach; ?>
            </div>
                
        </div>
    </main>

    <script src="./assets/js/javascript.php?file=script.js"></script>
</body>
</html>
