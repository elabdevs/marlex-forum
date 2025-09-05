<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Robot Doğrulaması - <?= $siteTitle ?></title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] ?>./assets/css/css.php?file=styles.css">
</head>
<body>
    <!-- Animated background orbs -->
    <div class="liquid-orb orb-1"></div>
    <div class="liquid-orb orb-2"></div>
    <div class="liquid-orb orb-3"></div>
    <div class="liquid-orb orb-4"></div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="liquid-glass header-content">
                <div class="header-left">
                    <div class="logo-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h1 class="logo-text"><?= $siteTitle ?></h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <div class="captcha-container">
                <!-- Captcha Card -->
                <div class="captcha-card liquid-glass liquid-glass-card">
                    <div class="captcha-header">
                        <div class="captcha-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <h1 class="captcha-title">Güvenlik Doğrulaması</h1>
                        <p class="captcha-description">Lütfen devam etmek için doğrulamayı tamamlayın</p>
                    </div>

                    <!-- Captcha Types Tabs -->
                    <div class="captcha-tabs">
                        <button class="captcha-tab active" data-tab="recaptcha">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21,15 16,10 5,21"/>
                            </svg>
                            reCAPTCHA
                        </button>
                        <button class="captcha-tab" disabled data-tab="puzzle">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19.439 7.85c-.049.322-.059.648-.026.975.056.506.194.999.416 1.461.218.462.518.886.897 1.26.379.374.803.674 1.265.892.462.218.955.356 1.461.412.327.033.653.023.975-.026.322-.049.636-.145.926-.283.29-.138.557-.317.787-.547.23-.23.409-.497.547-.787.138-.29.234-.604.283-.926.048-.322.059-.648.026-.975-.056-.506-.194-.999-.416-1.461-.218-.462-.518-.886-.897-1.26-.379-.374-.803-.674-1.265-.892-.462-.218-.955-.356-1.461-.412-.327-.033-.653-.023-.975.026-.322.049-.636.145-.926.283-.29.138-.557.317-.787.547-.23.23-.409.497-.547.787-.138.29-.234.604-.283.926z"/>
                                <path d="M11.25 8.25l.825.825m0 0l.825.825M12.075 9.075l.825-.825m-.825.825L11.25 9.9"/>
                            </svg>
                            Görsel Robot Doğrulaması
                        </button>
                        <button class="captcha-tab" disabled data-tab="math">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Matematiksel Robot Doğrulaması
                        </button>
                    </div>

                    <!-- Image Captcha -->
                    <div class="captcha-content active" id="recaptcha">
                        <div class="captcha-challenge">
                            <h3 class="challenge-title">Robot doğrulamasını tamamlayın.</span></h3>
                            <div class="image-grid" id="imageGrid">
                                <form id="captchaForm" action="/verifyCaptcha" method="POST">
                                    <div id="captcha" class="g-recaptcha" data-callback="submitForm" data-sitekey="6LettK8rAAAAACk38P-IpQSHqveI4Sf5kmiUDkev"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <div class="captcha-success" id="captchaSuccess" style="display: none;">
                        <div class="success-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22,4 12,14.01 9,11.01"/>
                            </svg>
                        </div>
                        <h3 class="success-title">Doğrulama Başarılı</h3>
                        <p class="success-message">Güvenlik doğrulamasını başarıyla tamamladınız. Yönlendiriliyorsunuz...</p>
                    </div>
                </div>

                <!-- Help Sidebar -->
                <div class="help-sidebar">
                    <div class="help-card liquid-glass liquid-glass-card">
                        <div class="help-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </div>
                        <h3 class="help-title">Yardıma ihtiyacım var?</h3>
                        <div class="help-content">
                            <div class="help-item">
                              <h4>Görsel Doğrulama (reCAPTCHA)</h4>
                              <p>Belirtilen nesneyi içeren görselleri seçin. Botları engellemek için kullanılır.</p>
                            </div>
                            <div class="help-item">
                              <h4>Yapboz Doğrulaması</h4>
                              <p>Parçayı doğru yere sürükleyin. Bu işlem, insan hareketlerini doğrular.</p>
                            </div>
                            <div class="help-item">
                              <h4>Matematik Doğrulaması</h4>
                              <p>Basit işlemi çözün ve sonucu yazın. Botlara karşı hızlı bir yöntemdir.</p>
                            </div>
                        </div>
                    </div>

                    <div class="help-card liquid-glass liquid-glass-card">
                        <div class="help-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <h3 class="help-title">Neden bu sayfayı görüyorum?</h3>
                        <p class="help-description">Bu doğrulama, topluluğumuzu otomatik spam'den korumaya yardımcı olur ve tüm kullanıcılar için güvenli bir ortam sağlar.</p>
                    </div>

                    <div class="help-card liquid-glass liquid-glass-card">
                        <div class="help-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14.828 14.828a4 4 0 0 1-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </svg>
                        </div>
                        <h3 class="help-title">Zorluk mu Yaşıyorsunuz?</h3>
                        <p class="help-description">Eğer doğrulama ile ilgili zorluk yaşıyorsanız, lütfen meydan okumayı yenileyin veya farklı bir doğrulama yöntemine geçmeyi deneyin.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] ?>/assets/js/javascript.php?file=script.js"></script>
    <script src="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] ?>/assets/js/javascript.php?file=captcha.js"></script>
</body>
</html>
