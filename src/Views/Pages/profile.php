<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if($own) { echo "Profil"; } else { echo htmlspecialchars($userInfo['username']); } ?> - <?= $siteTitle ?></title>
    
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
            <div class="profile-layout">
                <div class="profile-header liquid-glass">
                    <div class="profile-cover">
                        <div class="cover-image"><img src="https://placehold.co/1170x140" alt="Profile Banner" id="profileBanner"></div>
                        
                            <?php if($own): ?>
                        <button class="edit-cover-btn" onclick="editCover()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                            </button>
                            <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <div class="profile-avatar-container">
                            <div class="profile-avatar">
                                <img src="<?= $userInfo['avatar_path'] ?? 'https://placehold.co/120x120' ?>" alt="Profile Avatar" id="profileAvatar">
                            </div>
                            <?php if($own): ?>
                            <button class="edit-avatar-btn" onclick="editAvatar()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                    <circle cx="12" cy="13" r="4"></circle>
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="profile-details">
                            <div class="profile-name-section">
                                <h1 class="profile-name" id="profileName"><?= $userInfo['username'] ?></h1>
                                <div class="user-roles">
                                    <?php use App\Controllers\UsersController; foreach($userRoles as $userRole): ?> 
                                        <span class="user-role-badge user-role" style="<?= UsersController::getRoleCss($userRole) ?>">
                                            <?= $userRole ?> 
                                        </span>
                                    <?php endforeach; ?> 
                                </div>
                            </div>
                            
                            <p class="profile-title" id="profileTitle"><?= htmlspecialchars($highestRole) ?></p>
                            
                            <p class="profile-bio" id="profileBio"><?= htmlspecialchars($bio) ?></p>
                            
                            <div class="profile-meta">
                                <div class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span><?= htmlspecialchars($joinedAt) ?> katıldı</span>
                                </div>
                                <div class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                    <span><?= htmlspecialchars($userInfo['userPoints']) ?> İtibar Puanı</span>
                                </div>
                                <div class="meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span><?= htmlspecialchars($postsCount) ?> Gönderi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-content">
                    <div class="profile-tabs liquid-glass">
                        <button class="tab-btn active" data-tab="overview" onclick="switchTab('overview')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                            Genel Bakış
                        </button>
                        <button class="tab-btn" data-tab="posts" onclick="switchTab('posts')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Gönderiler
                        </button>
                        <button class="tab-btn" data-tab="activity" onclick="switchTab('activity')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"></polyline>
                            </svg>
                            Aktiflik
                        </button>
                            <?php if($own): ?>
                        <button class="tab-btn" data-tab="settings" onclick="switchTab('settings')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            Ayarlar
                        </button>
                            <?php endif; ?>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="overview">
                            <div class="overview-grid">
                                <div class="stats-grid">
                                    <div class="stat-card liquid-glass">
                                        <div class="stat-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number"><?= htmlspecialchars($postsCount) ?></div>
                                            <div class="stat-label">Toplam Gönderi</div>
                                        </div>
                                    </div>
                                    <div class="stat-card liquid-glass">
                                        <div class="stat-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number"><?= htmlspecialchars($userInfo['userPoints']) ?></div>
                                            <div class="stat-label">İtibar Puanı</div>
                                        </div>
                                    </div>
                                    <div class="stat-card liquid-glass">
                                        <div class="stat-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                            </svg>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number">1</div>
                                            <div class="stat-label">Arkadaş</div>
                                        </div>
                                    </div>
                                    <div class="stat-card liquid-glass">
                                        <div class="stat-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number"><?= htmlspecialchars($profileViews) ?></div>
                                            <div class="stat-label">Profil Görüntülenmesi</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="recent-activity liquid-glass">
                                    <h3 class="section-title">Son Aktivite</h3>
                                    <div class="activity-list" id="activityList">
                                    </div>
                                </div>

                                <div class="achievements liquid-glass" style="margin-bottom: 20px;">
                                    <h3 class="section-title">Başarımlar</h3>
                                    <div class="achievements-grid" id="achievementsList" style="margin: 0 1.5rem;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="activity">
                            <div class="activity-section">
                                <h3 class="section-title">Aktivite Zaman Çizelgesi</h3>
                                <div class="activity-timeline" id="activityTimeline">
                                </div>
                            </div>
                        </div>

                         
                        <?php if($own): ?>
                        <div class="tab-pane" id="settings">
                            <div class="settings-section">
                                <div class="settings-grid">
                                    <!-- Profile Settings -->
                                    <div class="settings-card liquid-glass">
                                        <h3 class="settings-title">Profil Bilgileri</h3>
                                        <form class="settings-form" id="profileForm">
                                            <div class="form-group">
                                                <label class="form-label">Görünen Ad</label>
                                                <input type="text" class="form-input" value="<?= htmlspecialchars($userInfo['displayName']) ?>" id="displayName">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Biyografi</label>
                                                <textarea class="form-textarea" rows="4" id="userBio"><?= htmlspecialchars($bio) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Konum</label>
                                                <input type="text" class="form-input" value="<?= htmlspecialchars($userInfo['location']) ?>" id="location">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Web Sitesi</label>
                                                <input type="url" class="form-input" value="<?= htmlspecialchars($userInfo['website']) ?>" id="website">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                                        </form>
                                    </div>

                                    <!-- Notification Settings -->
                                    <div class="settings-card liquid-glass">
                                        <h3 class="settings-title">Bildirimler</h3>
                                        <div class="settings-form">
                                            <div class="form-group">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" class="checkbox-input" checked>
                                                    <span class="checkbox-custom"></span>
                                                    Gönderi yanıtları için E-Mail bildirimi
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" class="checkbox-input" checked>
                                                    <span class="checkbox-custom"></span>
                                                    Etiketler için E-Mail bildirimi
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" class="checkbox-input" checked>
                                                    <span class="checkbox-custom"></span>
                                                    Tarayıcı bildirimleri
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Privacy Settings -->
                                    <div class="settings-card liquid-glass">
                                        <h3 class="settings-title">Gizlilik Ayarları</h3>
                                        <div class="settings-form">
                                            <div class="form-group">
                                                <label class="form-label">Profil Görünürlüğü</label>
                                                <select class="form-select">
                                                    <option value="public">Herkese Açık</option>
                                                    <option value="members">Üyelere Özel</option>
                                                    <option value="private">Gizli</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" class="checkbox-input" checked>
                                                    <span class="checkbox-custom"></span>
                                                    Aktiflik Durumumu Göster
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" class="checkbox-input" checked>
                                                    <span class="checkbox-custom"></span>
                                                    Direkt mesajlara izin ver
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Settings -->
                                    <div class="settings-card liquid-glass">
                                        <h3 class="settings-title">Hesap Ayarları</h3>
                                        <div class="settings-form">
                                            <div class="form-group">
                                                <label class="form-label">Email Adresi</label>
                                                <input type="email" class="form-input" value="alexandra@example.com" readonly>
                                                <p class="form-hint">Email adresinizi değiştirmek için destek ile iletişime geçin</p>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline" onclick="changePassword()">Şifre Değiştir</button>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-outline" onclick="downloadData()">Verilerimi İndir</button>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-secondary" onclick="deleteAccount()" style="background: #ef4444; color: white;">Hesabı Sil</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        window.CSRF_TOKEN = "<?php echo $_SESSION['csrf_token']; ?>";
    </script>
    <script src="/assets/js/javascript.php?file=profile.js"></script>
</body>
</html>

