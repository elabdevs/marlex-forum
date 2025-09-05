<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Diğer Menü Kısımları -->
                    <?php if(@$_SESSION['is_admin'] == 1){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Kategoriler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/reports">Raporlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/userLogs">Kullanıcı Hareketleri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/userBans">Yasaklar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Yönetim Paneli</a>
                    </li> 
                    <?php if(@$_SESSION['user_id']){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Çıkış Yap</a>
                    </li>
                    <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Giriş Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Kayıt Ol</a>
                    </li>
                    <?php } ?>
                    <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Kategoriler</a>
                    </li>
                    <?php if(@$_SESSION['user_id']){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Çıkış Yap</a>
                    </li>
                    <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Giriş Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Kayıt Ol</a>
                    </li>
                    <?php }} ?>
                </ul>

                <!-- Bildirimler Kısmı -->
                <?php if(@$_SESSION['user_id']): ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i> Bildirimler
                            <span class="badge bg-danger"><?= htmlspecialchars($notificationCount, ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                            <?php if($notifications): ?>
                            <?php foreach($notifications as $notification): ?>
                            <li><a class="dropdown-item" href="#"><?= htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8') ?></a></li>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <li><a class="dropdown-item" href="#">Bildirim Bulunamadı!</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Tümünü Gör</a></li>
                        </ul>
                    </li>
                </ul>
                <?php endif;?>
            </div>
        </div>
    </nav>