<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Konular - <?= $siteTitle ?></title>
    <link rel="stylesheet" href="/assets/css/css.php?file=styles.css" id="mainCSS" media="all">
    <link rel="stylesheet" href="/assets/css/css.php?file=lite.min.css" id="liteCSS" media="none">
</head>
<body>
    <div class="background-container">
        <div class="liquid-orb" style="top: 10%; left: 20%; width: 300px; height: 300px; animation-delay: 0s;"></div>
        <div class="liquid-orb" style="top: 60%; right: 15%; width: 200px; height: 200px; animation-delay: 2s;"></div>
        <div class="liquid-orb" style="bottom: 20%; left: 10%; width: 250px; height: 250px; animation-delay: 4s;"></div>
    </div>

    <?php include($header) ?>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1><?= $categoryName ?></h1>
                <p>Forumdaki tüm tartışmalara göz atın</p>
            </div>

            <div class="topics-controls liquid-glass">
                <div class="search-section">
                    <div class="search-container">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" placeholder="Konu ara" class="search-input" id="searchInput">
                    </div>
                </div>
                <div class="filter-section">
                    <select class="filter-select" id="sortFilter">
                        <option value="latest">Son aktiflik</option>
                        <option value="newest">En yeni</option>
                        <option value="oldest">En eski</option>
                        <option value="popular">En popüler</option>
                    </select>
                </div>
            </div>

            <div class="topics-list">
                <div class="topics-header">
                    <div class="topics-stats">
                        <span class="topic-count">Showing <span id="topicCount">24</span> topics</span>
                    </div>
                </div>

                <div class="topics-container" id="topicsContainer">
                    <!-- JS ile dinamik olarak gelecek -->
                </div>

                <div class="pagination">
                    <button class="pagination-btn" id="prevBtn" disabled>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Önceki
                    </button>
                    <div class="pagination-info">
                        <span>Sayfa <span id="currentPage">1</span> of <span id="totalPages">3</span></span>
                    </div>
                    <button class="pagination-btn" id="nextBtn">
                        Sonraki
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9,18 15,12 9,6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>
    <script>
        window.categoryName = "<?= basename($_SERVER['REQUEST_URI']) ?>";
    </script>
    <script src="../assets/js/javascript.php?file=topics.js"></script>
</body>
</html>
