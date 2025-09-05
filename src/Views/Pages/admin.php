<!DOCTYPE html>
<html lang="tr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Yönetici Paneli - <?= $siteTitle ?></title>
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
			<div class="admin-container">
				<div class="admin-layout">
					<div class="admin-sidebar liquid-glass">
						<div class="sidebar-header">
							<div class="admin-avatar">
								<img src="<?= isset($userInfo['avatar_path']) ? $userInfo['avatar_path'] : 'https://placehold.co/60x60' ?>" alt="Yönetici Avatarı">
							</div>
							<div class="admin-info">
								<h3><?= $userInfo['username'] ?></h3>
								<p><?= $role ?></p>
							</div>
						</div>
						<nav class="admin-nav">
							<button class="nav-item active" data-section="dashboard" onclick="switchSection('dashboard')">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<rect x="3" y="3" width="7" height="7"></rect>
									<rect x="14" y="3" width="7" height="7"></rect>
									<rect x="14" y="14" width="7" height="7"></rect>
									<rect x="3" y="14" width="7" height="7"></rect>
								</svg>
								Panel
							</button>
							<button class="nav-item" data-section="users" onclick="switchSection('users')">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
									<circle cx="12" cy="7" r="4"></circle>
								</svg>
								Üyeler
							</button>
							<button class="nav-item" data-section="content" onclick="switchSection('content')">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
								</svg>
								İçerik
							</button>
							<button class="nav-item" data-section="moderation" onclick="switchSection('moderation')">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
								</svg>
								Moderasyon
							</button>
							<button class="nav-item" data-section="analytics" onclick="switchSection('analytics')">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M3 3v18h18"></path>
									<path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path>
								</svg>
								Analizler
							</button>
							<button class="nav-item" data-section="settings" onclick="switchSection('settings')">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<circle cx="12" cy="12" r="3"></circle>
									<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
								</svg>
								Ayarlar
							</button>
						</nav>
					</div>
					<!-- Yönetici İçeriği -->
					<div class="admin-content">
						<!-- Panel Bölümü -->
						<div class="admin-section active" id="dashboard">
							<div class="section-header">
								<h2>Panel Genel Bakış</h2>
								<div class="header-actions">
									<button class="btn btn-outline" style="margin-top:10px; margin-bottom:10px;" onclick="refreshDashboard()">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 30 30" fill="currentColor">
    <path d="M15 3C12.03 3 9.30 4.08 7.21 5.88A1 1 0 0 0 8.50 7.39C10.25 5.90 12.52 5 15 5C20.20 5 24.45 8.94 24.95 14L22 14L26 20L30 14L26.95 14C26.44 7.85 21.28 3 15 3zM4 10L0 16L3.05 16C3.56 22.15 8.72 27 15 27C17.97 27 20.70 25.92 22.79 24.13A1 1 0 0 0 21.49 22.61C19.74 24.10 17.48 25 15 25C9.80 25 5.55 21.06 5.05 16L8 16L4 10z"></path>
  </svg>
  Yenile
</button>

								</div>
							</div>
							<div class="stats-grid">
								<!-- Ana istatistikler -->
								<div class="stat-card liquid-glass">
									<div class="stat-icon users">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										  <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
										  <path d="M7 21v-2a4 4 0 0 1 3-3.87"></path>
										  <circle cx="12" cy="7" r="4"></circle>
										</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Toplam Üye</div>
										<!-- <div class="stat-change positive">%12 artış bu ay</div> -->
									</div>
								</div>
								<div class="stat-card liquid-glass">
									<div class="stat-icon posts">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Toplam Gönderi</div>
										<!-- <div class="stat-change positive">%8 artış bu hafta</div> -->
									</div>
								</div>
								<div class="stat-card liquid-glass">
									<div class="stat-icon topics">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7"></path>
  <path d="M3 7l9 6 9-6"></path>
</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Aktif Konu</div>
										<!-- <div class="stat-change positive">%15 artış bu hafta</div> -->
									</div>
								</div>
								<div class="stat-card liquid-glass">
									<div class="stat-icon reports">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <circle cx="12" cy="12" r="10"/>
  <line x1="12" y1="8" x2="12" y2="12"/>
  <circle cx="12" cy="16" r="1"/>
</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Bekleyen Rapor</div>
										<!-- <div class="stat-change negative">+3 bugün</div> -->
									</div>
								</div>
								<!-- Ek istatistikler -->
								<div class="stat-card liquid-glass">
									<div class="stat-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <circle cx="12" cy="7" r="4"/>
  <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
  <circle cx="19" cy="4" r="2" fill="green"/>
</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Çevrimiçi Üye</div>
										<!-- <div class="stat-change positive">+5 şimdi</div> -->
									</div>
								</div>
								<div class="stat-card liquid-glass">
									<div class="stat-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M20 6L9 17l-5-5"/>
</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Otomatik İşaretli</div>
										<!-- <div class="stat-change negative">+2 bugün</div> -->
									</div>
								</div>
								<div class="stat-card liquid-glass">
									<div class="stat-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <circle cx="12" cy="12" r="10"/>
  <polyline points="12 6 12 12 16 14"/>
</svg>

									</div>
									<div class="stat-info">
										<div class="stat-number">0</div>
										<div class="stat-label">Bekleyen Onay</div>
										<!-- <div class="stat-change negative">-1 bugün</div> -->
									</div>
								</div>
							</div>
							<div class="dashboard-grid">
								<div class="dashboard-card liquid-glass">
									<h3 class="card-title">Son Etkinlikler</h3>
									<div class="activity-feed" id="dashboardActivity" >
									</div>
								</div>
								<div class="dashboard-card liquid-glass">
									<h3 class="card-title">Hızlı İşlemler</h3>
									<div class="quick-actions">
										<button class="action-btn" id="openModal">Duyuru Ekle</button>
										<button class="action-btn" onclick="moderateContent()">Raporları İncele</button>
										<button class="action-btn" onclick="manageUsers()">Üyeleri Yönet</button>
										<button class="action-btn" onclick="viewAnalytics()">Analizleri Görüntüle</button>
										<button class="action-btn" onclick="demoBackup()">Forum Yedekle</button>
										<button class="action-btn" id="openRestoreModal">Yedeği Geri Yükle</button>
										<button class="action-btn" onclick="demoMaintenance()">Bakım Modunu Aç/Kapat</button>
									</div>
								</div>
								<div class="dashboard-card liquid-glass">
									<h3 class="card-title">Sistem Durumu</h3>
									<div id="systemStatusList"></div>
								</div>
								<div class="dashboard-card liquid-glass">
									<h3 class="card-title">Yönetici Notları</h3>
									<textarea class="form-textarea" rows="4" placeholder="Diğer yöneticiler için not ekleyin..."></textarea>
								</div>
							</div>
						</div>
						<!-- Üyeler Bölümü -->
						<div class="admin-section" id="users">
							<div class="section-header">
								<h2>Kullanıcı Yönetimi</h2>
								<div class="header-actions" style="margin-bottom: 10px; margin-top: 10px;">
									<button class="btn btn-outline" onclick="exportUsers()">Dışa Aktar</button>
									<button class="btn btn-primary" onclick="inviteUser()">Kullanıcı Davet Et</button>
									<button class="btn btn-outline" onclick="bulkActions()">Toplu İşlemler</button>
								</div>
							</div>
							<div class="filters-bar liquid-glass">
								<div class="search-container">
									<svg width="20" height="20">
										<circle cx="11" cy="11" r="8"></circle>
									</svg>
									<input type="text" placeholder="Kullanıcıları ara..." class="search-input" id="userSearch">
								</div>
								<select class="filter-select" id="userRoleFilter">
									<option value="">Tüm Rolleri Göster</option>
									<option value="admin">Yönetici</option>
									<option value="moderator">Moderatör</option>
									<option value="member">Üye</option>
								</select>
								<select class="filter-select" id="userStatusFilter">
									<option value="">Tüm Durumları Göster</option>
									<option value="active">Aktif</option>
									<option value="suspended">Askıya Alınmış</option>
									<option value="banned">Yasaklı</option>
								</select>
								<select class="filter-select" id="userSortFilter">
									<option value="recent">Son Üye Olanlar</option>
									<option value="posts">En Fazla Gönderi Yapanlar</option>
									<option value="reputation">En Yüksek İtibar</option>
								</select>
							</div>
							<div class="data-table liquid-glass">
								<table class="users-table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Kullanıcı</th>
											<th>Email</th>
											<th>Rol</th>
											<th>İtibar</th>
											<th>Son Giriş</th>
											<th>Katılma Tarihi</th>
											<th>Durum</th>
											<th>İşlemler</th>
										</tr>
									</thead>
									<tbody id="usersTableBody">
									</tbody>
								</table>
							</div>
							<div class="user-actions" style="display: flex; flex-direction: row; margin: 50px;">
								<button class="btn btn-secondary" onclick="demoViewLogs()">Yönetici Günlüklerini Görüntüle</button>
								<button class="btn btn-secondary" onclick="demoViewSessions()">Oturum Günlüklerini Görüntüle</button>
								<button class="btn btn-secondary" onclick="demoViewModerationHistory()">Moderasyon Geçmişini Görüntüle</button>
							</div>
						</div>
						<!-- İçerik Bölümü -->
						<div class="admin-section" id="content">
							<div class="section-header">
								<h2>İçerik Yönetimi</h2>
								<div class="header-actions">
									<button class="btn btn-outline" onclick="bulkActions()">Toplu İşlemler</button>
									<button class="btn btn-primary" onclick="createAnnouncement()">Duyuru Oluştur</button>
									<button class="btn btn-outline" onclick="moderateContent()">İçeriği Moderasyon</button>
								</div>
							</div>
							<div class="content-tabs">
								<button class="tab-btn active" data-tab="posts" onclick="switchContentTab('posts')">Gönderiler</button>
								<button class="tab-btn" data-tab="topics" onclick="switchContentTab('topics')">Konular</button>
								<button class="tab-btn" data-tab="categories" onclick="switchContentTab('categories')">Kategoriler</button>
								<button class="tab-btn" data-tab="tags" onclick="switchContentTab('tags')">Etiketler</button>
								<button class="tab-btn" data-tab="attachments" onclick="switchContentTab('attachments')">Ekler</button>
							</div>
							<div class="filters-bar liquid-glass">
								<input type="text" class="search-input" placeholder="İçeriği ara...">
								<select class="filter-select">
									<option value="">Tüm Durumlar</option>
									<option value="published">Yayınlandı</option>
									<option value="draft">Taslak</option>
									<option value="flagged">Bayraklı</option>
								</select>
								<select class="filter-select">
									<option value="">Sırala</option>
									<option value="date">Tarih</option>
									<option value="views">Görüntülenme</option>
									<option value="likes">Beğeni</option>
								</select>
							</div>
							<div class="data-table liquid-glass">
								<table class="content-table">
									<thead id="contentTableHead">
										<!-- JS ile doldurulacak -->
									</thead>
									<tbody id="contentTableBody">
										<!-- JS ile doldurulacak -->
									</tbody>
								</table>
							</div>
							<div class="content-actions">
								<button class="btn btn-secondary" onclick="editContent()">Seçiliyi Düzenle</button>
								<button class="btn btn-secondary" onclick="deleteContent()">Seçiliyi Sil</button>
								<button class="btn btn-secondary" onclick="moderateContent()">Seçiliyi Moderasyon</button>
							</div>
						</div>
						<!-- Moderasyon Bölümü -->
						<div class="admin-section" id="moderation">
							<div class="section-header">
								<h2>Moderasyon Bekleme Listesi</h2>
								<div class="header-actions">
									<button class="btn btn-outline" onclick="clearQueue()">Çözülenleri Temizle</button>
									<button class="btn btn-primary" onclick="bulkActions()">Toplu İşlemler</button>
								</div>
							</div>
							<div class="moderation-stats">
								<div class="mod-stat-card liquid-glass">
									<div class="mod-stat-number">23</div>
									<div class="mod-stat-label">Bekleyen Raporlar</div>
								</div>
								<div class="mod-stat-card liquid-glass">
									<div class="mod-stat-number">156</div>
									<div class="mod-stat-label">Bugün Çözülenler</div>
								</div>
								<div class="mod-stat-card liquid-glass">
									<div class="mod-stat-number">8</div>
									<div class="mod-stat-label">Otomatik İşaretli</div>
								</div>
								<div class="mod-stat-card liquid-glass">
									<div class="mod-stat-number">4</div>
									<div class="mod-stat-label">Bekleyen Onaylar</div>
								</div>
								<div class="mod-stat-card liquid-glass">
									<div class="mod-stat-number">2</div>
									<div class="mod-stat-label">Yasaklı Kullanıcılar</div>
								</div>
							</div>
							<div class="filters-bar liquid-glass">
								<select class="filter-select">
									<option value="">Tüm Türler</option>
									<option value="spam">Spam</option>
									<option value="harassment">Taciz</option>
									<option value="other">Diğer</option>
								</select>
								<select class="filter-select">
									<option value="">Tüm Durumlar</option>
									<option value="pending">Bekleyen</option>
									<option value="resolved">Çözülen</option>
									<option value="dismissed">Görmezden Gelinen</option>
								</select>
							</div>
							<div class="reports-list" id="reportsList">
								<!-- JS ile doldurulacak -->
							</div>
							<div class="moderation-actions">
								<button class="btn btn-secondary" onclick="approveReport()">Seçiliyi Onayla</button>
								<button class="btn btn-secondary" onclick="dismissReport()">Seçiliyi Görmezden Gel</button>
								<button class="btn btn-secondary" onclick="bulkActions()">Toplu İşlemler</button>
							</div>
						</div>
						<!-- Analizler Bölümü -->
						<div class="admin-section" id="analytics">
							<div class="section-header">
								<h2>Analizler ve İçgörüler</h2>
								<div class="header-actions">
									<select class="filter-select">
										<option value="7">Son 7 gün</option>
										<option value="30">Son 30 gün</option>
										<option value="90">Son 90 gün</option>
										<option value="365">Son 1 yıl</option>
									</select>
								</div>
							</div>
							<div class="analytics-grid">
								<div class="chart-card liquid-glass">
									<h3 class="chart-title">Kullanıcı Büyümesi</h3>
									<div class="chart-bars">
										<div class="bar" style="height: 60%"></div>
										<div class="bar" style="height: 80%"></div>
										<div class="bar" style="height: 45%"></div>
										<div class="bar" style="height: 90%"></div>
										<div class="bar" style="height: 70%"></div>
										<div class="bar" style="height: 95%"></div>
										<div class="bar" style="height: 85%"></div>
									</div>
								</div>
								<div class="chart-card liquid-glass">
									<h3 class="chart-title">Gönderi Etkinliği</h3>
									<div class="chart-line">
										<svg viewBox="0 0 300 150" class="line-chart">
											<polyline points="0,120 50,100 100,80 150,60 200,40 250,30 300,20" fill="none" stroke="var(--secondary)" stroke-width="3"/>
										</svg>
									</div>
								</div>
								<div class="chart-card liquid-glass">
									<h3 class="chart-title">En Popüler Kategoriler</h3>
									<div class="category-stats">
										<div class="category-stat">
											<span class="category-name">Genel Tartışma</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 85%"></div>
											</div>
											<span class="category-count">342</span>
										</div>
										<div class="category-stat">
											<span class="category-name">Teknik Destek</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 65%"></div>
											</div>
											<span class="category-count">234</span>
										</div>
										<div class="category-stat">
											<span class="category-name">Duyurular</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 45%"></div>
											</div>
											<span class="category-count">156</span>
										</div>
									</div>
								</div>
								<div class="chart-card liquid-glass">
									<h3 class="chart-title">Saatlik Aktif Kullanıcılar</h3>
									<div class="chart-bars">
										<div class="bar" style="height: 30%"></div>
										<div class="bar" style="height: 50%"></div>
										<div class="bar" style="height: 80%"></div>
										<div class="bar" style="height: 60%"></div>
										<div class="bar" style="height: 90%"></div>
										<div class="bar" style="height: 40%"></div>
										<div class="bar" style="height: 70%"></div>
									</div>
								</div>
								<div class="chart-card liquid-glass">
									<h3 class="chart-title">Cihaz Dağılımı</h3>
									<div class="category-stats">
										<div class="category-stat">
											<span class="category-name">Masaüstü</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 60%"></div>
											</div>
											<span class="category-count">60%</span>
										</div>
										<div class="category-stat">
											<span class="category-name">Mobil</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 35%"></div>
											</div>
											<span class="category-count">35%</span>
										</div>
										<div class="category-stat">
											<span class="category-name">Tablet</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 5%"></div>
											</div>
											<span class="category-count">5%</span>
										</div>
									</div>
								</div>
								<div class="chart-card liquid-glass">
									<h3 class="chart-title">En İyi Yönlendirenler</h3>
									<div class="category-stats">
										<div class="category-stat">
											<span class="category-name">Google</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 70%"></div>
											</div>
											<span class="category-count">1,200</span>
										</div>
										<div class="category-stat">
											<span class="category-name">Twitter</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 20%"></div>
											</div>
											<span class="category-count">340</span>
										</div>
										<div class="category-stat">
											<span class="category-name">Discord</span>
											<div class="category-bar">
												<div class="category-fill" style="width: 10%"></div>
											</div>
											<span class="category-count">120</span>
										</div>
									</div>
								</div>
							</div>
							<div class="analytics-actions">
								<button class="btn btn-secondary" onclick="exportUsers()">Kullanıcıları Dışa Aktar</button>
								<button class="btn btn-secondary" onclick="bulkActions()">Gönderileri Dışa Aktar</button>
								<button class="btn btn-secondary" onclick="viewAnalytics()">Gelişmiş Analizler</button>
							</div>
						</div>
						<!-- Ayarlar Bölümü -->
						<div class="admin-section" id="settings">
							<div class="section-header">
								<h2>Forum Ayarları</h2>
								<div class="header-actions">
									<button class="btn btn-primary" onclick="saveSettings()">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
											<polyline points="17,21 17,13 7,13 7,21"></polyline>
											<polyline points="7,3 7,8 15,8"></polyline>
										</svg>
										Değişiklikleri Kaydet
									</button>
								</div>
							</div>
							<form class="settings-form" id="settingsForm">
								<div class="settings-grid">
									<!-- GENEL AYARLAR -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Genel Ayarlar</h3>
										<div class="form-group">
											<label class="form-label">Forum Adı</label>
											<input type="text" class="form-input" name="siteName" value="<?= htmlspecialchars($settings['siteInfo']['data']['siteName'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Forum Açıklaması</label>
											<textarea class="form-textarea" name="defaultSiteDescription" rows="3"><?= htmlspecialchars($settings['defaultSiteDescription'] ?? '') ?></textarea>
										</div>
										<div class="form-group">
											<label class="form-label">Hoş Geldin Mesajı</label>
											<input type="text" class="form-input" name="welcomeMessage" value="<?= htmlspecialchars($settings['welcomeMessage'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Anasayfa Duyuru Limiti</label>
											<input type="number" class="form-input" name="dashboardAnnounceLimit" value="<?= htmlspecialchars($settings['dashboardAnnounceLimit'] ?? '') ?>">
										</div>
									</div>
									<!-- MODERASYON -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Moderasyon Ayarları</h3>
										<div class="form-group">
											<label class="form-label">Gönderi Onayı Gerekli mi?</label>
											<select class="form-select" name="postsRequireAccept">
												<option value="true" <?= ($settings['postsRequireAccept'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['postsRequireAccept'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Yasaklı Kelimeler Tablosu</label>
											<input type="text" class="form-input" name="bannedWordsTable" value="<?= htmlspecialchars($settings['bannedWordsTable'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Maksimum Gönderi Uzunluğu</label>
											<input type="number" class="form-input" name="maxPostLength" value="<?= htmlspecialchars($settings['maxPostLength'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Maksimum Başlık Uzunluğu</label>
											<input type="number" class="form-input" name="maxPostTitleLength" value="<?= htmlspecialchars($settings['maxPostTitleLength'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Gönderi Başına Maksimum Resim</label>
											<input type="number" class="form-input" name="maxImagePerPost" value="<?= htmlspecialchars($settings['maxImagePerPost'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Gönderi Başına Maksimum Etiket</label>
											<input type="number" class="form-input" name="maxTagPerPosts" value="<?= htmlspecialchars($settings['maxTagPerPosts'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Dakikada Maksimum Gönderi</label>
											<input type="number" class="form-input" name="maxPostsPerMinute" value="<?= htmlspecialchars($settings['maxPostsPerMinute'] ?? '') ?>">
										</div>
									</div>
									<!-- E-POSTA -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">E-posta Ayarları</h3>
										<div class="form-group">
											<label class="form-label">SMTP Sunucu</label>
											<input type="text" class="form-input" name="smtpServer" value="<?= htmlspecialchars($settings['smtpServer'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">SMTP Port</label>
											<input type="number" class="form-input" name="smtpPort" value="<?= htmlspecialchars($settings['smtpPort'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Hoş Geldin Maili Gönder</label>
											<select class="form-select" name="sendWelcomeMail">
												<option value="true" <?= ($settings['sendWelcomeMail'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['sendWelcomeMail'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Bildirim Mailleri Gönder</label>
											<select class="form-select" name="sendNotificationMails">
												<option value="true" <?= ($settings['sendNotificationMails'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['sendNotificationMails'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- GÜVENLİK -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Güvenlik Ayarları</h3>
										<div class="form-group">
											<label class="form-label">Oturum Zaman Aşımı (dk)</label>
											<input type="number" class="form-input" name="sessionExpiration" value="<?= htmlspecialchars($settings['sessionExpiration'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Captcha Süresi (dk)</label>
											<input type="number" class="form-input" name="captchaExpiration" value="<?= htmlspecialchars($settings['captchaExpiration'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">2FA Zorunlu mu?</label>
											<select class="form-select" name="require2FA">
												<option value="true" <?= ($settings['require2FA'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['require2FA'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">WAF Koruması</label>
											<select class="form-select" name="requireCaptchaRegistiration">
												<option value="true" <?= ($settings['requireCaptchaRegistiration'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['requireCaptchaRegistiration'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Admin İşlemleri Loglansın mı?</label>
											<select class="form-select" name="logAdminActions">
												<option value="true" <?= ($settings['logAdminActions'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['logAdminActions'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Minimum Şifre Uzunluğu</label>
											<input type="number" class="form-input" name="minPasswordLength" value="<?= htmlspecialchars($settings['minPasswordLength'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Minimum Kullanıcı Adı Uzunluğu</label>
											<input type="number" class="form-input" name="minUsernameLength" value="<?= htmlspecialchars($settings['minUsernameLength'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">reCAPTCHA Site Key</label>
											<input type="text" class="form-input" name="recaptchaSiteKey" value="<?= htmlspecialchars($settings['recaptchaSiteKey'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">reCAPTCHA Secret Key</label>
											<input type="text" class="form-input" name="recaptchaSecretKey" value="<?= htmlspecialchars($settings['recaptchaSecretKey'] ?? '') ?>">
										</div>
									</div>
									<!-- KULLANICI İZİNLERİ -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Kullanıcı İzinleri</h3>
										<div class="form-group">
											<label class="form-label">Varsayılan Kullanıcı Rolü</label>
											<input type="number" class="form-input" name="defaultUserRole" value="<?= htmlspecialchars($settings['defaultUserRole'] ?? '') ?>">
											
											<!-- <select class="form-select" name="defaultUserRole">
												<option value="1" <?= ($settings['defaultUserRole'] ?? '') == '1' ? 'selected' : '' ?>>Kullanıcı</option> TODO: kullanıcı rollerini dinamik hale getir
											</select> -->
										</div>
										<div class="form-group">
											<label class="form-label">Moderatör Ban Yetkisi</label>
											<select class="form-select" name="allowModeratorBans">
												<option value="true" <?= ($settings['allowModeratorBans'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['allowModeratorBans'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Kullanıcı Kendi Gönderisini Silebilsin mi?</label>
											<select class="form-select" name="usersRemoveOwnPosts">
												<option value="true" <?= ($settings['usersRemoveOwnPosts'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['usersRemoveOwnPosts'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- GÖRÜNÜM VE TEMA -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Görünüm ve Tema</h3>
										<div class="form-group">
											<label class="form-label">Karanlık/Aydınlık Tema switchi</label>
											<select class="form-select" name="darkModeToggle">
												<option value="1" <?= ($settings['darkModeToggle'] ?? '') == '1' ? 'selected' : '' ?>>Açık</option>
												<option value="0" <?= ($settings['darkModeToggle'] ?? '') == '0' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Özel CSS Tablosu</label>
											<input type="text" class="form-input" name="customCssTable" value="<?= htmlspecialchars($settings['customCssTable'] ?? '') ?>">
										</div>
									</div>
									<!-- BİLDİRİMLER -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Bildirimler ve Uyarılar</h3>
										<div class="form-group">
											<label class="form-label">Anlık Mail Bildirimleri</label>
											<select class="form-select" name="instantMailNotifications">
												<option value="true" <?= ($settings['instantMailNotifications'] ?? '') == 'true' ? 'selected' : '' ?>>Açık</option>
												<option value="false" <?= ($settings['instantMailNotifications'] ?? '') == 'false' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Günlük Özet Mail</label>
											<select class="form-select" name="dailySummaryMail">
												<option value="true" <?= ($settings['dailySummaryMail'] ?? '') == 'true' ? 'selected' : '' ?>>Açık</option>
												<option value="false" <?= ($settings['dailySummaryMail'] ?? '') == 'false' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
									</div>
									<!-- ENTEGRASYONLAR -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Entegrasyonlar</h3>
										<div class="form-group">
											<label class="form-label">Google Analytics ID</label>
											<input type="text" class="form-input" name="googleAnalyticsId" value="<?= htmlspecialchars($settings['googleAnalyticsId'] ?? '') ?>">
										</div>
									</div>
									<!-- GELİŞMİŞ -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Gelişmiş</h3>
										<div class="form-group">
											<label class="form-label">API Kullanımı</label>
											<select class="form-select" name="allowApiUsage">
												<option value="true" <?= ($settings['allowApiUsage'] ?? '') == 'true' ? 'selected' : '' ?>>Açık</option>
												<option value="false" <?= ($settings['allowApiUsage'] ?? '') == 'false' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Bakım Modu</label>
											<select class="form-select" name="maintenceMode">
												<option value="true" <?= ($settings['maintenceMode'] ?? '') == 'true' ? 'selected' : '' ?>>Açık</option>
												<option value="false" <?= ($settings['maintenceMode'] ?? '') == 'false' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
									</div>
									<!-- KAYIT VE İLK AYAR -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Kayıt ve İlk Ayar</h3>
										<div class="form-group">
											<label class="form-label">Kayıt Sistemi Aktif</label>
											<select class="form-select" name="registirationActive">
												<option value="true" <?= ($settings['registirationActive'] ?? '') == 'true' ? 'selected' : '' ?>>Açık</option>
												<option value="false" <?= ($settings['registirationActive'] ?? '') == 'false' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Zorunlu Profil Fotoğrafı</label>
											<select class="form-select" name="forceUploadProcilePicture">
												<option value="true" <?= ($settings['forceUploadProcilePicture'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['forceUploadProcilePicture'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Kullanıcı Adı Değiştirilebilir mi?</label>
											<select class="form-select" name="allowChangeUsername">
												<option value="true" <?= ($settings['allowChangeUsername'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['allowChangeUsername'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- PROFİL ÖZELLEŞTİRME -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Profil Özelleştirme</h3>
										<div class="form-group">
											<label class="form-label">Avatar yüklemesini zorunlu kıl</label>
											<select class="form-select" name="forceUploadProcilePicture">
												<option value="true" <?= ($settings['forceUploadProcilePicture'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['forceUploadProcilePicture'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- GÖNDERİ VE KONU -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Gönderi ve Konu Ayarları</h3>
										<div class="form-group">
											<label class="form-label">Otomatik başlık önerilerini etkinleştir</label>
											<select class="form-select" name="automaticallyAssignBadges">
												<option value="true" <?= ($settings['automaticallyAssignBadges'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['automaticallyAssignBadges'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Kullanıcılar giriş yapmadan konuları görüntüleyebilir mi?</label>
											<select class="form-select" name="viewTopicIsLoggedCheck">
												<option value="false" <?= ($settings['viewTopicIsLoggedCheck'] ?? '') == 'false' ? 'selected' : '' ?>>Evet</option>
												<option value="true" <?= ($settings['viewTopicIsLoggedCheck'] ?? '') == 'true' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- HIZ SINIRLAMA -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Hız Sınırlama ve Anti-Spam</h3>
										<div class="form-group">
											<label class="form-label">Flood koruma özelliğini etkinleştir</label>
											<select class="form-select" name="captchaType">
												<option value="1" <?= ($settings['captchaType'] ?? '') == '1' ? 'selected' : '' ?>>reCAPTCHA</option>
												<option value="2" <?= ($settings['captchaType'] ?? '') == '2' ? 'selected' : '' ?>>hCaptcha</option>
											</select>
										</div>
									</div>
									<!-- İTİBAR VE ROZETLER -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">İtibar ve Rozetler</h3>
										<div class="form-group">
											<label class="form-label">Rank Sistemi Aktif mi?</label>
											<select class="form-select" name="enableRankSystem">
												<option value="true" <?= ($settings['enableRankSystem'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['enableRankSystem'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Rank Tablosu</label>
											<input type="text" class="form-input" name="ranksTable" value="<?= htmlspecialchars($settings['ranksTable'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Rozetler Otomatik Atansın mı?</label>
											<select class="form-select" name="automaticallyAssignBadges">
												<option value="true" <?= ($settings['automaticallyAssignBadges'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['automaticallyAssignBadges'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- ÖZEL SAYFALAR VE MENÜLER -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Özel Sayfalar ve Menüler</h3>
										<div class="form-group">
											<label class="form-label">Özel Sayfalar Tablosu</label>
											<input type="text" class="form-input" name="customPagesTable" value="<?= htmlspecialchars($settings['customPagesTable'] ?? '') ?>">
										</div>
									</div>
									<!-- REKLAM VE GELİR -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Reklamlar ve Gelir</h3>
										<div class="form-group">
											<label class="form-label">Reklam Kodu</label>
											<textarea class="form-textarea" name="adsSnippet" rows="2"><?= htmlspecialchars($settings['adsSnippet'] ?? '') ?></textarea>
										</div>
										<div class="form-group">
											<label class="form-label">Premium Hesap Sistemi Aktif mi?</label>
											<select class="form-select" name="activatePremiumAccountSystem">
												<option value="true" <?= ($settings['activatePremiumAccountSystem'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['activatePremiumAccountSystem'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Sponsorlu Konular Tablosu</label>
											<input type="text" class="form-input" name="sponsoredTopicsTable" value="<?= htmlspecialchars($settings['sponsoredTopicsTable'] ?? '') ?>">
										</div>
									</div>
									<!-- GİZLİLİK VE KVKK -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Gizlilik ve KVKK</h3>
										<div class="form-group">
											<label class="form-label">KVKK Metni</label>
											<textarea class="form-textarea" name="kvkkText" rows="2"><?= htmlspecialchars($settings['kvkkText'] ?? '') ?></textarea>
										</div>
										<div class="form-group">
											<label class="form-label">Çerez Politikası</label>
											<textarea class="form-textarea" name="cookiePolicy" rows="2"><?= htmlspecialchars($settings['cookiePolicy'] ?? '') ?></textarea>
										</div>
									</div>
									<!-- API VE WEBHOOKLAR -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">API ve Webhooklar</h3>
										<div class="form-group">
											<label class="form-label">Admin Log Tablosu</label>
											<input type="text" class="form-input" name="adminLogsTable" value="<?= htmlspecialchars($settings['adminLogsTable'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Moderasyon Log Tablosu</label>
											<input type="text" class="form-input" name="moderationLogsTable" value="<?= htmlspecialchars($settings['moderationLogsTable'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Session Log Tablosu</label>
											<input type="text" class="form-input" name="sessionLogsTable" value="<?= htmlspecialchars($settings['sessionLogsTable'] ?? '') ?>">
										</div>
									</div>
									<!-- DİL VE YERELLEŞTİRME -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Dil ve Yerelleştirme</h3>
										<div class="form-group">
											<label class="form-label">Varsayılan Dil</label>
											<input type="text" class="form-input" name="defaultLanguage" value="<?= htmlspecialchars($settings['defaultLanguage'] ?? '') ?>">
										</div>
										<div class="form-group">
											<label class="form-label">Çeviri Tablosu</label>
											<input type="text" class="form-input" name="translateTable" value="<?= htmlspecialchars($settings['translateTable'] ?? '') ?>">
										</div>
									</div>
									<!-- MOBİL VE PWA -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Mobil ve PWA</h3>
										<div class="form-group">
											<label class="form-label">Mobil Anlık Bildirimler</label>
											<select class="form-select" name="allowInstantMobileNotificatons">
												<option value="true" <?= ($settings['allowInstantMobileNotificatons'] ?? '') == 'true' ? 'selected' : '' ?>>Açık</option>
												<option value="false" <?= ($settings['allowInstantMobileNotificatons'] ?? '') == 'false' ? 'selected' : '' ?>>Kapalı</option>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">PWA Aktif mi?</label>
											<select class="form-select" name="activatePWA">
												<option value="true" <?= ($settings['activatePWA'] ?? '') == 'true' ? 'selected' : '' ?>>Evet</option>
												<option value="false" <?= ($settings['activatePWA'] ?? '') == 'false' ? 'selected' : '' ?>>Hayır</option>
											</select>
										</div>
									</div>
									<!-- ÖZEL WIDGETLAR -->
									<div class="settings-card liquid-glass">
										<h3 class="settings-title">Özel Widgetlar</h3>
										<div class="form-group">
											<label class="form-label">Anasayfa Widgetları</label>
											<input type="text" class="form-input" name="pageDataTable" value="<?= htmlspecialchars($settings['pageDataTable'] ?? '') ?>">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</main>
		<div class="modal-overlay" id="modalOverlay">
			<div class="modal">
				<div class="modal-header">
					<h2>Duyuru Ekle</h2>
					<button class="close-btn" id="closeModal">&times;</button>
				</div>
				<div class="modal-body">
					<form id="duyuruForm">
						<div class="form-group liquid-glass" style="
							position: relative;
							padding: 20px;
							border-radius: 18px;
							margin-bottom: 22px;
							background: rgba(255,255,255,0.18);
							box-shadow: 0 8px 32px rgba(31, 38, 135, 0.18);
							backdrop-filter: blur(12px);
							border: 1.5px solid rgba(255,255,255,0.22);
							">
							<input type="text" id="annTitle" name="title" placeholder="Başlık" required>
							<textarea id="annContent" name="content" placeholder="İçerik" rows="4" required></textarea>
							<label class="form-label" for="announcementDate" style="font-weight:600;color:#222;letter-spacing:0.02em;">Duyuru Tarihi ve Saati</label>
							<input 
								type="datetime-local" 
								class="form-input"
								id="announcementDate"
								name="announcementDate"
								style="
								width:100%;
								border-radius:10px;
								border:1.5px solid #e0e7ef;
								background:rgba(255,255,255,0.22);
								color:#222;
								padding:12px 14px 12px 44px;
								font-size:1.08rem;
								box-shadow:0 2px 8px rgba(0,0,0,0.09);
								backdrop-filter: blur(8px);
								transition: border 0.2s, box-shadow 0.2s;
								"
								onfocus="this.style.borderColor='#60a5fa';this.style.boxShadow='0 0 0 2px #60a5fa44';"
								onblur="this.style.borderColor='#e0e7ef';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.09)';"
								>
						</div>
						<button type="submit" class="save-btn">Kaydet</button>
					</form>
				</div>
			</div>
		</div>
		<div class="modal-overlay" id="backupModalOverlay">
		  <div class="modal">
		    <div class="modal-header">
		      <h2>Forum Yedekle</h2>
		      <button class="close-btn" id="closeBackupModal">&times;</button>
		    </div>
		    <div class="modal-body">
		      <div class="backup-options">
		        <div class="backup-option">
		          <h3>Tam Yedekleme</h3>
		          <p>Forumun tüm veritabanı, yüklenen dosyalar, ayarlar, kullanıcı içerikleri ve sistem dosyaları dahil olmak üzere <b>her şeyin</b> yedeğini alır. (yüksek boyut)</p>
		          <button class="btn btn-primary" id="fullBackupBtn">Tam Yedekle</button>
		        </div>
		        <div class="backup-option" style="margin-top:2rem;">
		          <h3>Kritik Yedekleme</h3>
		          <p>Sadece <b>kritik dosyalar</b> yedeklenir (düşük boyut):<br>
		            - Veritabanı (SQL dump)<br>
		            - Upload edilen dosyalar<br>
		            - CSS ve JS dosyaları<br>
		            - <code>.env</code> yapılandırma dosyası<br>
		            - SSL sertifikaları<br>
		            - Diğer önemli sistem dosyaları<br>
					- <code>config.php</code> dosyası<br>
					- Sunucu konfigurasyonları<br>
		            <small>Forumun çalışması için gerekli temel dosyaları içerir.</small>
		          </p>
		          <button class="btn btn-outline" id="criticalBackupBtn">Kritik Yedekle</button>
		        </div>
		      </div>
		    </div>
			<div id="backupProgressContainer" style="display:none; margin-top:2rem;">
			  <div style="margin-bottom:8px;font-weight:600;">Yedekleme İlerleme Durumu</div>
			  <div style="background:#e5e7eb;border-radius:8px;overflow:hidden;height:28px;">
			    <div id="progress-bar" style="background:#6366f1;color:#fff;width:0%;height:100%;display:flex;align-items:center;justify-content:center;font-weight:600;transition:width 0.3s;"></div>
			  </div>
			  <pre id="log" style="background:#f3f4f6;border-radius:8px;padding:12px;margin-top:12px;max-height:120px;overflow:auto;font-size:0.95em;"></pre>
			</div>
		  </div>
		</div>
		<div class="modal-overlay" id="restoreModalOverlay">
		  <div class="modal">
		    <div class="modal-header">
		      <h2>Yedeği Geri Yükle</h2>
		      <button class="close-btn" id="closeRestoreModal">&times;</button>
		    </div>
		    <div class="modal-body">
		      <form id="restoreForm" enctype="multipart/form-data">
		        <div class="form-group liquid-glass" style="padding:20px;border-radius:18px;margin-bottom:22px;">
		          <input type="file" id="restoreZipInput" name="backup_zip" accept=".zip" required>
		        </div>
		        <button type="submit" class="btn btn-primary">Yedeği Yükle</button>
		      </form>
		      <div id="restoreProgressContainer" style="display:none; margin-top:2rem;">
		        <div style="margin-bottom:8px;font-weight:600;">Geri Yükleme İlerleme Durumu</div>
		        <div style="background:#e5e7eb;border-radius:8px;overflow:hidden;height:28px;">
		          <div id="restore-progress-bar" style="background:#6366f1;color:#fff;width:0%;height:100%;display:flex;align-items:center;justify-content:center;font-weight:600;transition:width 0.3s;"></div>
		        </div>
		        <pre id="restore-log" style="background:#f3f4f6;border-radius:8px;padding:12px;margin-top:12px;max-height:120px;overflow:auto;font-size:0.95em;"></pre>
		      </div>
		    </div>
		  </div>
		</div>
		<div class="modal-overlay" id="editUserModalOverlay" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <h2>Kullanıcıyı Düzenle</h2>
      <button class="close-btn" id="closeEditUserModal">&times;</button>
    </div>
    <div class="modal-body">
      <form id="editUserForm">
        <input type="hidden" name="userId" id="editUserId">
        <div class="form-group">
          <label>Kullanıcı Adı</label>
          <input type="text" class="form-input" name="username" id="editUsername" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-input" name="email" id="editUserEmail" required>
        </div>
        <div class="form-group">
          <label>Rol</label>
          <select class="form-select" name="role" id="editUserRole">
			<?php foreach($roles as $role): ?>
            <option value="<?= $role ?>"><?= $role ?></option>
			<?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Durum</label>
          <select class="form-select" name="status" id="editUserStatus">
            <option value="1">Aktif</option>
            <option value="0">Pasif</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
      </form>
    </div>
  </div>
</div>
		<script>
			window.USER_ROLE = "1";
		</script>
		</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="../assets/js/javascript.php?file=admin.js"></script>
	</body>
</html>