<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konu Oluştur - <?= $siteTitle ?></title>
    
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
    <header class="header">
        <div class="container">
            <div class="liquid-glass header-content">
                <div class="header-left">
                    <div class="logo-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h1 class="logo-text">Forum</h1>
                </div>
                <nav class="nav">
                    <a href="index.html" class="nav-link">Ana Sayfa</a>
                    <a href="categories.html" class="nav-link">Kategoriler</a>
                    <a href="#" class="nav-link">Üyeler</a>
                    <a href="#" class="nav-link">Hakkında</a>
                </nav>


            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Yeni Konu Oluştur</h1>
                <p class="page-description">Düşüncelerinizi paylaşın ve bir tartışma başlatın</p>
            </div>

            <!-- Create Topic Form -->
            <div class="create-topic-container">
                <form class="create-topic-form liquid-glass liquid-glass-card" id="createTopicForm">
                    <!-- Category Selection -->
                    <div class="form-group">
                        <label for="category" class="form-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            </svg>
                            Kategori
                        </label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="" disabled selected>Bir kategori seçin</option>
                            <?php foreach($categories as $category): ?> 
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>

                    <!-- Topic Title -->
                    <div class="form-group">
                        <label for="title" class="form-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                <line x1="4" y1="22" x2="4" y2="15"/>
                            </svg>
                            Konu Başlığı
                        </label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            class="form-input" 
                            placeholder="Konu için açıklayıcı bir başlık girin"
                            maxlength="100"
                            required
                        >
                        <div class="character-count">
                            <span id="titleCount">0</span>/100 karakter
                        </div>
                    </div>

                    <!-- Topic Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20h9"/>
                                <path d="M12 4h9"/>
                                <rect x="3" y="8" width="18" height="8" rx="2"/>
                            </svg>
                            Konu Açıklaması
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea"
                            placeholder="Konu hakkında kısa bir açıklama girin (maksimum 300 karakter)"
                            maxlength="300"
                            rows="3"
                            required
                        ></textarea>
                        <div class="character-count">
                            <span id="descriptionCount">0</span>/300 karakter
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="form-group">
                        <label for="tags" class="form-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                            Etiketler (İsteğe Bağlı)
                        </label>
                        <input 
                            type="text" 
                            id="tags" 
                            name="tags" 
                            class="form-input" 
                            placeholder="Etiketleri virgülle ayırarak ekleyin (örn: javascript, yardım, yeni)"
                        >
                        <div class="form-hint">Etiketler, diğer kullanıcıların konunuzu daha kolay bulmasını sağlar</div>
                    </div>

                    <!-- Content Editor -->
                    <div class="form-group">
                        <label for="content" class="form-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                            İçerik
                        </label>
                        <div class="editor-toolbar">
                            <button type="button" class="toolbar-btn" data-action="bold" title="Kalın">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>
                                    <path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>
                                </svg>
                            </button>
                            <button type="button" class="toolbar-btn" data-action="italic" title="İtalik">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="19" y1="4" x2="10" y2="4"/>
                                    <line x1="14" y1="20" x2="5" y2="20"/>
                                    <line x1="15" y1="4" x2="9" y2="20"/>
                                </svg>
                            </button>
                            <button type="button" class="toolbar-btn" data-action="link" title="Bağlantı Ekle">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                </svg>
                            </button>
                            <button type="button" class="toolbar-btn" data-action="code" title="Kod">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="16,18 22,12 16,6"/>
                                    <polyline points="8,6 2,12 8,18"/>
                                </svg>
                            </button>
                        </div>
                        <textarea 
                            id="content" 
                            name="content" 
                            class="form-textarea" 
                            placeholder="Konu içeriğinizi buraya yazın... Markdown biçimlendirmesi kullanabilirsiniz."
                            rows="12"
                            maxlength="<?= $maxPostLength ?>"
                            required
                        ></textarea>
                        <div class="character-count">
                            <span id="contentCount">0</span>/<?= $maxPostLength ?> karakter
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="form-group">
                        <div class="preview-header">
                            <label class="form-label">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Önizleme
                            </label>
                            <button type="button" class="preview-toggle" id="previewToggle">
                                Önizlemeyi Göster
                            </button>
                        </div>
                        <div class="preview-content" id="previewContent" style="display: none;">
                            <div class="preview-box">
                                <div class="preview-title" id="previewTitle">Başlığınız burada görünecek</div>
                                <div class="preview-meta">
                                    <span class="preview-category" id="previewCategory">Kategori</span>
                                    <span class="preview-tags" id="previewTags"></span>
                                </div>
                                <div class="preview-text" id="previewText">İçeriğiniz burada görünecek</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15,18 9,12 15,6"/>
                            </svg>
                            İptal
                        </button>
                        <button type="button" class="btn btn-outline" id="saveDraft">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17,21 17,13 7,13 7,21"/>
                                <polyline points="7,3 7,8 15,8"/>
                            </svg>
                            Taslağı Kaydet
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"/>
                                <polygon points="22,2 15,22 11,13 2,9 22,2"/>
                            </svg>
                            Konu Oluştur
                        </button>
                    </div>
                </form>

                <!-- Guidelines Sidebar -->
                <aside class="guidelines-sidebar liquid-glass liquid-glass-card">
                    <h3 class="sidebar-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        Paylaşım Kuralları
                    </h3>
                    <ul class="guidelines-list">
                        <li>Açık ve açıklayıcı bir başlık seçin</li>
                        <li>En uygun kategoriyi seçin</li>
                        <li>Konunuza yardımcı etiketler ekleyin</li>
                        <li>Saygılı ve yapıcı olun</li>
                        <li>Yeni konu açmadan önce mevcut konuları arayın</li>
                        <li>İlgili detayları ve bağlamı ekleyin</li>
                    </ul>

                    <div class="formatting-help">
                        <h4 class="help-title">Biçimlendirme Yardımı</h4>
                        <div class="help-item">
                            <code>**kalın metin**</code>
                            <span>→ <strong>kalın metin</strong></span>
                        </div>
                        <div class="help-item">
                            <code>*italik metin*</code>
                            <span>→ <em>italik metin</em></span>
                        </div>
                        <div class="help-item">
                            <code>`kod`</code>
                            <span>→ <code>satır içi kod</code></span>
                        </div>
                        <div class="help-item">
                            <code>[bağlantı](url)</code>
                            <span>→ bağlantı için</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <script src="./assets/js/javascript.php?file=script.js"></script>
    <script src="./assets/js/javascript.php?file=create-topic.js"></script>
</body>
</html>
