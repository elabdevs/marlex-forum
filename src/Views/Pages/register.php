<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol | <?= $siteTitle ?> </title>
    <link rel="stylesheet" href="/assets/css/css.php?file=styles.css" id="mainCSS" media="all">
    <link rel="stylesheet" href="/assets/css/css.php?file=lite.min.css" id="liteCSS" media="none">
</head>
<body>
    <!-- Animasyonlu arka plan orbları -->
    <div class="liquid-orb orb-1"></div>
    <div class="liquid-orb orb-2"></div>
    <div class="liquid-orb orb-3"></div>
    <div class="liquid-orb orb-4"></div>

    <!-- Header -->
    <?php include($header) ?>

    <!-- Ana İçerik -->
    <main class="main">
        <div class="container">
            <div class="auth-container">
                <!-- Kayıt Formu -->
                <div class="auth-card liquid-glass liquid-glass-card">
                    <div class="auth-header">
                        <div class="auth-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                        </div>
                        <h1 class="auth-title">Hesap Oluştur</h1>
                        <p class="auth-description">Topluluğumuza katıl ve tartışmalara başlamaya başla</p>
                    </div>

                    <form class="auth-form" id="registerForm">
                        <!-- Tam İsim Alanı -->
                        <div class="form-group">
                            <label for="fullName" class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Tam İsim
                            </label>
                            <input 
                                type="text" 
                                id="fullName" 
                                name="fullName" 
                                class="form-input" 
                                placeholder="Tam isminizi girin"
                                required
                                autocomplete="name"
                            >
                            <div class="field-error" id="fullNameError"></div>
                        </div>

                        <!-- Kullanıcı Adı Alanı -->
                        <div class="form-group">
                            <label for="username" class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="8.5" cy="7" r="4"/>
                                    <line x1="20" y1="8" x2="20" y2="14"/>
                                    <line x1="23" y1="11" x2="17" y2="11"/>
                                </svg>
                                Kullanıcı Adı
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                placeholder="Benzersiz bir kullanıcı adı seçin"
                                required
                                autocomplete="username"
                            >
                            <div class="field-hint">Kullanıcı adı 3-20 karakter olmalı; sadece harf, rakam ve alt çizgi</div>
                            <div class="field-error" id="usernameError"></div>
                        </div>

                        <!-- E-posta Alanı -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                E-posta
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="E-posta adresinizi girin"
                                required
                                autocomplete="email"
                            >
                            <div class="field-error" id="emailError"></div>
                        </div>

                        <!-- Şifre Alanı -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <circle cx="12" cy="16" r="1"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Şifre
                            </label>
                            <div class="password-field">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input" 
                                    placeholder="Güçlü bir şifre oluşturun"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>
                            <!-- Şifre Gücü Göstergesi -->
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <div class="strength-text" id="strengthText">Şifre gücü</div>
                            </div>
                            <div class="field-error" id="passwordError"></div>
                        </div>

                        <!-- Şifre Onay Alanı -->
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <circle cx="12" cy="16" r="1"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Şifreyi Onayla
                            </label>
                            <div class="password-field">
                                <input 
                                    type="password" 
                                    id="confirmPassword" 
                                    name="confirmPassword" 
                                    class="form-input" 
                                    placeholder="Şifrenizi tekrar girin"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" id="confirmPasswordToggle">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                        <line x1="1" y1="1" x2="23" y2="23"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="field-error" id="confirmPasswordError"></div>
                        </div>

                        <!-- Kullanım Şartları ve Gizlilik -->
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="agreeTerms" name="agreeTerms" class="checkbox-input" required>
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">
                                    <a href="#" class="terms-link">Hizmet Şartları</a> ve <a href="#" class="terms-link">Gizlilik Politikası</a>'nı kabul ediyorum
                                </span>
                            </label>
                            <div class="field-error" id="agreeTermsError"></div>
                        </div>

                        <!-- Bülten Aboneliği -->
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="newsletter" name="newsletter" class="checkbox-input">
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">
                                    Güncellemeler ve topluluk haberleri için bültene abone ol
                                </span>
                            </label>
                        </div>

                        <!-- Gönder Butonu -->
                        <button type="submit" class="btn btn-primary btn-full" id="registerButton">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                            <span class="button-text">Hesap Oluştur</span>
                            <div class="loading-spinner" style="display: none;">
                                <div class="spinner"></div>
                            </div>
                        </button>

                        <!-- Bölücü -->
                        <div class="auth-divider">
                            <span>veya şunlarla kaydol</span>
                        </div>

                        <!-- Sosyal Giriş -->
                        <div class="social-login">
                            <button type="button" class="social-btn google-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Google
                            </button>
                            <button type="button" class="social-btn github-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                GitHub
                            </button>
                        </div>
                    </form>

                    <!-- Giriş Linki -->
                    <div class="auth-footer">
                        <p>Zaten hesabınız var mı? <a href="login.html" class="auth-link">Giriş yap</a></p>
                    </div>
                </div>

                <!-- Özellikler Sidebar -->
                <div class="features-sidebar">
                    <div class="feature-card liquid-glass liquid-glass-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                <line x1="9" y1="9" x2="9.01" y2="9"/>
                                <line x1="15" y1="9" x2="15.01" y2="9"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Her Zaman Ücretsiz</h3>
                        <p class="feature-description">Topluluğumuza ücretsiz katılın ve tüm özelliklerden gizli ücret olmadan yararlanın</p>
                    </div>

                    <div class="feature-card liquid-glass liquid-glass-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Güvenli & Gizli</h3>
                        <p class="feature-description">Verileriniz endüstri standartlarında güvenlik ve gizlilik önlemleriyle korunur</p>
                    </div>

                    <div class="feature-card liquid-glass liquid-glass-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Hemen Erişim</h3>
                        <p class="feature-description">Hesap oluşturduktan hemen sonra tartışmalara katılmaya başlayın</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/js/javascript.php?file=script.js"></script>
    <script src="/assets/js/javascript.php?file=register.js"></script>
</body>
</html>
