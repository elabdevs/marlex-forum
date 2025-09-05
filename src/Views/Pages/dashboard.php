<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include($head); ?>
   </head>
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
            <!-- Duyuru Bölümü -->
            <section class="announcement-section liquid-glass" style="margin-bottom: 32px;">
               <div class="announcement-header" style="display: flex; align-items: center;">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 10px;">
                     <path d="M3 10v2a6 6 0 0 0 6 6h6a6 6 0 0 0 6-6v-2"></path>
                     <path d="M8 10V5a4 4 0 0 1 8 0v5"></path>
                  </svg>
                  <h2 class="announcement-title" style="margin: 0;">Duyurular</h2>
               </div>
               <div class="announcement-content" style="margin-top: 10px;">
                  <?php
                   $announcements = file_get_contents(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] . '/api/getAnnounces');
                   $announcements = json_decode($announcements, true);
                   foreach ($announcements['data'] as $announcement): ?>
                  <div class="announcement-item" style="margin-bottom: 12px;">
                     <strong><?= htmlspecialchars($announcement['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                     <p><?= htmlspecialchars($announcement['content'], ENT_QUOTES, 'UTF-8') ?></p>
                     <span style="font-size: 12px; color: #888;"><?php $expiresAt=new DateTime($announcement['expires_at']);$now=new DateTime();$diff=$now->diff($expiresAt);if($diff->invert){echo "Süresi dolmuş";}else{if($diff->d>0){echo $diff->d." gün kaldı";}elseif($diff->h>0){echo $diff->h." saat kaldı";}else{echo "Birkaç dakika kaldı";}} ?></span>
                  </div>
                  <?php endforeach; ?>
               </div>
            </section>
            <!-- Forum Categories -->
            <section class="categories-section">
               <h2 class="section-title"><?= $siteTitle ?>'a Hoşgeldiniz.</h2>
               <div class="categories-grid">
                  <?php foreach ($categories as $category): ?> 
                  <a href="/categories/<?= htmlspecialchars($category['slug'], ENT_QUOTES, 'UTF-8') ?>" style="text-decoration: none;">
                     <div class="category-card liquid-glass liquid-glass-card">
                        <div class="category-icon">
                           <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                              <circle cx="9" cy="7" r="4"/>
                              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                           </svg>
                        </div>
                        <h3 class="category-title"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="category-description"><?= htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="category-stats">
                           <span>1.2k posts</span>
                           <span>•</span>
                           <span>456 members</span>
                        </div>
                     </div>
                  </a>
                  <?php endforeach; ?> 
               </div>
            </section>
            <!-- Content Grid -->
            <div class="content-grid">
               <!-- Active Users -->
               <div class="content-card liquid-glass liquid-glass-card">
                  <div class="card-header">
                     <div class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                           <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                           <circle cx="9" cy="7" r="4"/>
                           <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                           <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Aktif Kullanıcılar</span>
                     </div>
                     <div id="activeUserCount" class="badge">0</div>
                  </div>
                  <div class="card-content">
                     <div class="user-list" id="activeUsers">
                     </div>
                  </div>
               </div>
               <!-- Chat -->
               <div class="content-card liquid-glass liquid-glass-card">
                  <div class="card-header">
                     <div class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                           <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <span>Canlı Sohbet</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <div class="chat-messages" id="chatMessages">
                        <?php foreach($messages as $message): ?>
                        <div class="chat-message">
                           <div class="message-header">
                              <span class="message-user"><?= htmlspecialchars($message['user']['username'], ENT_QUOTES, 'UTF-8') ?></span>
                              <span class="message-time"><?= htmlspecialchars($message['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
                           </div>
                           <p class="message-text"><?= htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <?php endforeach; ?>
                     </div>
                     <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <span>3 users typing...</span>
                     </div>
                  </div>
               </div>
               <!-- New Forums -->
               <div class="content-card liquid-glass liquid-glass-card">
                  <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                     <div class="card-title" style="display: flex; align-items: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                           <line x1="12" y1="5" x2="12" y2="19"/>
                           <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <span style="margin-left: 8px;">Son konular</span>
                     </div>
                     <a href="/create-topic" class="liquid-glass create-topic-btn btn" style="padding: 6px 18px; border-radius: 12px; background: rgba(255,255,255,0.18); box-shadow: 0 2px 8px rgba(0,0,0,0.08); color: #222; font-weight: 500; text-decoration: none; transition: background 0.2s;">
                     Konu Aç
                     </a>
                  </div>
                  <div class="card-content">
                     <div class="forum-list" id="newForums">
                        <?php foreach ($topics as $topic): ?>
                        <a  href="/topics/<?= htmlspecialchars($topic['slug'], ENT_QUOTES, 'UTF-8') ?>" style="text-decoration: none;">
                           <div class="forum-item">
                              <h4 class="forum-title"><?= htmlspecialchars($topic['title'], ENT_QUOTES, 'UTF-8') ?></h4>
                              <p class="forum-description"><?= htmlspecialchars($topic['description'], ENT_QUOTES, 'UTF-8') ?></p>
                              <div class="forum-stats">
                                 <div class="forum-meta">
                                    <span>Yazar: <?= htmlspecialchars($topic['username'], ENT_QUOTES, 'UTF-8') ?></span>
                                 </div>
                                 <span><?= htmlspecialchars($topic['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
                              </div>
                           </div>
                        </a>
                        <?php endforeach; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </main>
      <script src="./assets/js/javascript.php?file=script.js"></script>
      <!-- Footer -->
      <footer class="footer liquid-glass">
         <div class="container" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 0;">
            <div class="footer-left">
               <span>&copy; <?= date('Y') ?> <?= $siteTitle ?>. Tüm hakları saklıdır.</span>
            </div>
            <div class="footer-right">
               <a href="/about" class="footer-link">Hakkımızda</a>
               <a href="/contact" class="footer-link">İletişim</a>
               <a href="/privacy" class="footer-link">Gizlilik</a>
            </div>
         </div>
      </footer>
   </body>

</html>