<!DOCTYPE html>
<html lang="tr">
<?php include($head) ?>
<body>
    <!-- Animated background orbs -->
    <div class="liquid-orb orb-1"></div>
    <div class="liquid-orb orb-2"></div>
    <div class="liquid-orb orb-3"></div>
    <div class="liquid-orb orb-4"></div>

    <!-- Header -->
    <?php include($header); ?>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="/" class="breadcrumb-link">Ana Sayfa</a>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,18 15,12 9,6"/>
                </svg>
                <a href="/categories/<?= htmlspecialchars($categoryInfo['slug']) ?>" class="breadcrumb-link"><?= htmlspecialchars($categoryInfo['name']) ?></a>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,18 15,12 9,6"/>
                </svg>
                <span class="breadcrumb-current"><?= htmlspecialchars($topic['title']) ?></span>
            </nav>

            <!-- Topic Content -->
            <div class="topic-layout">
                <!-- Main Topic -->
                <div class="topic-main">
                    <!-- Topic Header -->
                    <div class="topic-header liquid-glass liquid-glass-card">
                        <div class="topic-meta">
                            <div class="topic-category">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/>
                                </svg>
                                <?= htmlspecialchars($categoryInfo['name']) ?>
                            </div>
                            <div class="topic-tags">
                                <?php foreach($tags as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag['name']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <h1 class="topic-title"><?= htmlspecialchars($topic['title']) ?></h1>
                        <div class="topic-stats">
                            <div class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <span><?= htmlspecialchars($topic['views']) ?></span>
                            </div>
                            <div class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <span><?= htmlspecialchars($postsCount) ?> yanıt</span>
                            </div>
                            <div class="stat-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M12 1v6m0 6v6"/>
                                </svg>
                                <span> <?= htmlspecialchars($topic['created_at']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Original Post -->
                    <div class="post liquid-glass liquid-glass-card">
                        <div class="post-header">
                            <div class="post-author">
                                <div class="author-avatar">
                                    <img src="/placeholder.svg?height=48&width=48" alt="<?= htmlspecialchars($topic['username']) ?>" />
                                    <div class="status-dot status-online"></div>
                                </div>
                                <div class="author-info">
                                    <h3 class="author-name"><?= htmlspecialchars($topic['username']) ?></h3>
                                    <div class="author-meta">
                                        <span class="author-role"><?= htmlspecialchars($topic['user_roles']['data'][0]) ?></span>
                                        <!-- <?php var_dump($topic) ?> -->
                                        <span class="author-posts"><?= htmlspecialchars($postsCountByUser) ?> gönderi - <?= htmlspecialchars($topicsCountByUser) ?> konu</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn <?php if($checkLiked == true) { echo 'liked'; } ?>" <?php if($checkLiked == true) { echo 'style="color: var(--secondary);"'; } ?> data-action="like" data-id="<?= $topicId ?>" id="likeButton" title="Beğen">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                    <span id="likeCountSpan"><?= $likeCount ?></span>
                                </button>
                                <button class="action-btn <?php if($checkFavorited == true) { echo 'liked'; } ?>" <?php if($checkFavorited == true) { echo 'style="color: var(--secondary);"'; } ?> data-action="favorite" data-id="<?= $topicId ?>" id="favoriteButton" title="Favorilere Ekle">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                    </svg>
                                    <span id="favoriteCountSpan"><?= $favoriteCount ?></span>
                                </button>
                                <button class="action-btn" title="Paylaş">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="18" cy="5" r="3"/>
                                        <circle cx="6" cy="12" r="3"/>
                                        <circle cx="18" cy="19" r="3"/>
                                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                                    </svg>
                                </button>
                                <button class="action-btn" title="Raporla">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                        <line x1="4" y1="22" x2="4" y2="15"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="post-content" id="post-content"><?= htmlspecialchars($topicContent) ?></div>
                        <div class="post-footer">
                            <div class="post-timestamp">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12,6 12,12 16,14"/>
                                </svg>
                                <?= htmlspecialchars($topic['created_at']) ?>
                            </div>
                            <div class="post-edited">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Son düzenleme: <?= htmlspecialchars($topic['updated_at']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="replies-section">
                        <div class="replies-header">
                            <h2 class="replies-title"><span id="repliesCount"></span> Yanıt</h2>
                            <div class="sort-options">
                                <select class="sort-select">
                                    <option value="newest">En Yeni Önce</option>
                                    <option value="oldest">En Eski Önce</option>
                                    <option value="popular">En Popüler</option>
                                </select>
                            </div>
                        </div>
                        <div class="replies-list">
                            <!-- Dinamik JS -->
                        </div>
                        <div class="load-more-container">
                            <button class="btn btn-outline load-more-btn" style="display:none">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6,9 12,15 18,9"/>
                                </svg>
                                Daha Fazla Yanıt Yükle
                            </button>
                        </div>
                    </div>

                    <div class="reply-form-container liquid-glass liquid-glass-card">
                        <h3 class="reply-form-title">Yanıtını Ekle</h3>
                        <form class="reply-form" id="replyForm">
                            <div class="form-group">
                                <textarea 
                                    class="form-textarea" 
                                    placeholder="Bu konu hakkında düşüncelerinizi paylaşın..."
                                    rows="6"
                                    required
                                ></textarea>
                            </div>
                            <div class="reply-form-actions">
                                <div class="formatting-tips">
                                    <span class="tip">Vurgulamak için **kalın** ve *italik* kullanabilirsiniz</span>
                                </div>
                                <div class="form-buttons">
                                    <button type="button" class="btn btn-secondary">Önizleme</button>
                                    <button type="submit" class="btn btn-primary">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="22" y1="2" x2="11" y2="13"/>
                                            <polygon points="22,2 15,22 11,13 2,9 22,2"/>
                                        </svg>
                                        Yanıtı Gönder
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <aside class="topic-sidebar">
                    <div class="sidebar-card liquid-glass liquid-glass-card">
                        <h3 class="sidebar-title">Konu İşlemleri</h3>
                        <div class="action-buttons">
                            <?php
                            if(@$_SESSION['user_id']):
                             if ($userInfo['is_admin'] == 1 || $topic['user_id'] == $_SESSION['user_id']):
                              ?> 
                            <button class="action-button" id="archiveTopic" data-id="<?= $topicId ?>" title='<?php if($topic['is_active'] == 0){ echo "Arşivden Kaldır"; } else { echo "Arşive At"; } ?>'>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h5l2 3h9a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>
                                    <line x1="12" y1="10" x2="12" y2="16"/>
                                    <polyline points="9 13 12 16 15 13"/>
                                </svg>
                                <?php if($topic['is_active'] == 0){ echo "Arşivden Kaldır"; } else { echo "Arşive At"; } ?>
                            </button>
                            <button class="action-button" id="removeTopic" data-id="<?= $topicId ?>" title="Konuyu Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m5 0V4a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                                <?= $checkRemoved ? "Konuyu Geri Yükle" : "Konuyu Sil" ?>
                            </button>
                            <?php endif; ?>
                            <button class="action-button" title="Konuyu Takip Et">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                </svg>
                                <?= $checkFavorited ? "Takibi Bırak" : "Konuyu Takip Et" ?>
                            </button>
                            <button class="action-button" title="Konuyu Beğen">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7z"/>
                                </svg>
                                <?= $checkLiked ? "Beğeniyi Geri Al" : "Konuyu Beğen" ?>
                            </button>
                            <button class="action-button" title="Konuyu Paylaş">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="18" cy="5" r="3"/>
                                    <circle cx="6" cy="12" r="3"/>
                                    <circle cx="18" cy="19" r="3"/>
                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                                </svg>
                                Konuyu Paylaş
                            </button>
                            <?php else: ?>
                            <button class="action-button" title="Konuyu Paylaş">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="18" cy="5" r="3"/>
                                    <circle cx="6" cy="12" r="3"/>
                                    <circle cx="18" cy="19" r="3"/>
                                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                                </svg>
                                Konuyu Paylaş
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="sidebar-card liquid-glass liquid-glass-card">
                        <h3 class="sidebar-title">Benzer Konular</h3>
                        <div class="related-topics"></div>
                    </div>

                    <div class="sidebar-card liquid-glass liquid-glass-card">
                        <h3 class="sidebar-title">Bu Konuda Aktif Olanlar</h3>
                        <div class="participants-list">

                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
    <script>
        window.topicId = "<?= $topicId ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/js/javascript.php?file=script.js"></script>
    <script src="../assets/js/javascript.php?file=topic-view.js"></script>
</body>
</html>
