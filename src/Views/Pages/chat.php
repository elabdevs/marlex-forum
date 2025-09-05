<?php if(session_status() == PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?= $siteTitle ?></title>
    
    <link rel="stylesheet" href="/assets/css/css.php?file=styles.css" id="mainCSS" media="all">
    <link rel="stylesheet" href="/assets/css/css.php?file=lite.min.css" id="liteCSS" media="none">
</head>
<body>
    <!-- Animated Background -->
		<div class="background-container">
			<div class="liquid-orb" style="top: 10%; left: 20%; width: 300px; height: 300px; animation-delay: 0s;"></div>
			<div class="liquid-orb" style="top: 60%; right: 15%; width: 200px; height: 200px; animation-delay: 2s;"></div>
			<div class="liquid-orb" style="bottom: 20%; left: 10%; width: 250px; height: 250px; animation-delay: 4s;"></div>
		</div>

    <!-- Navigation -->
    <nav class="glass-nav">
      <?php include($header); ?>
    </nav>

    <div class="chat-container">
        <div class="chat-sidebar glass-card">
            <div class="chat-header">
                <h3>Direkt Mesajlar</h3>
                <button class="new-chat-btn" onclick="openNewChatModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="10" y1="11" x2="14" y2="11"/>
                    </svg>
                </button>
            </div>
            
            <div class="chat-search">
                <input type="text" placeholder="Sohbetleri ara" id="chatSearch">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="M21 21l-4.35-4.35"/>
                </svg>
            </div>

            <div class="chat-list" id="chatList">
                <?php
                use App\Controllers\ChatController;
                use App\Controllers\SiteController;
                $recentChats = ChatController::getLastMessageUsersArray($_SESSION['user_id']);
                if($recentChats):
                foreach($recentChats as $chatUser):
                    $statusClass = $chatUser['activityStatus'] === 'Çevrimiçi' ? 'online' : ($chatUser['activityStatus'] === 'AFK' ? 'away' : 'offline');
                ?> 
                <div class="chat-item" data-user="<?= $chatUser['user_id'] ?>">
                    <div class="chat-avatar">
                        <img src="<?= $chatUser['avatar'] ? htmlspecialchars($chatUser['avatar']) : 'https://placehold.co/40x40' ?>" alt="<?=$chatUser['username']?>">
                        <div class="status-indicator <?= $statusClass ?>"></div>
                    </div>
                    <div class="chat-info">
                        <div class="chat-name"><?= $chatUser['username'] ?></div>
                        <div class="chat-preview"><?= $chatUser['last_message'] ?></div>
                        <div class="message-time" data-timestamp="<?= $chatUser['last_message_at'] ?>"><?= SiteController::timeAgo($chatUser['last_message_at']) ?></div>
                    </div>
                    <!-- <div class="unread-count">2</div> -->
                </div>

            <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Chat Main -->
        <div class="chat-main">
            <div class="chat-window glass-card">
                <div class="chat-window-header" id="chatWindowHeader" style="display:none;">
                    <div class="chat-user-info">
                        <div class="chat-avatar">
                            <img src="https://placehold.co/40" alt="" id="chatHeaderAvatar">
                            <div class="status-indicator" id="chatHeaderStatusIndicator"></div>
                        </div>
                        <div class="chat-user-details">
                            <div class="chat-user-name" id="chatHeaderUsername"> </div>
                            <div class="chat-user-status" id="chatHeaderUserOnlineStatus"></div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="chat-action-btn" title="Voice Call">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </button>
                        <button class="chat-action-btn" title="Video Call">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="23 7 16 12 23 17 23 7"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            </svg>
                        </button>
                        <button class="chat-action-btn" title="More Options">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="1"/>
                                <circle cx="19" cy="12" r="1"/>
                                <circle cx="5" cy="12" r="1"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="chat-empty-state" id="chatEmptyState">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                                <rect x="8" y="16" width="48" height="32" rx="10" fill="#e0e7ff"/>
                                <path d="M16 48L12 56L24 48" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="24" cy="32" r="3" fill="#6366f1"/>
                                <circle cx="32" cy="32" r="3" fill="#6366f1"/>
                                <circle cx="40" cy="32" r="3" fill="#6366f1"/>
                            </svg>
                        </div>
                        <div class="empty-title">Arkadaşlarınla ve insanlarla konuş</div>
                        <div class="empty-desc">Sohbet başlatmak için bir kişi seç veya yeni bir konuşma başlat.</div>
                        <button class="btn btn-primary start-chat-btn" onclick="openNewChatModal()">Konuşmaya Başla</button>
                    </div>
                </div>

                <div class="chat-input-container" id="chatInputContainer" style="display: none;">
                    <div class="chat-input-wrapper">
                        <button class="attachment-btn" title="Attach File">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66L9.64 16.2a2 2 0 0 1-2.83-2.83l8.49-8.49"/>
                            </svg>
                        </button>
                        <div class="chat-input-field">
                            <textarea id="messageInput" placeholder="Type your message..." rows="1"></textarea>
                            <button class="emoji-btn" title="Add Emoji">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                    <line x1="9" y1="9" x2="9.01" y2="9"/>
                                    <line x1="15" y1="9" x2="15.01" y2="9"/>
                                </svg>
                            </button>
                        </div>
                        <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"/>
                                <polygon points="22,2 15,22 11,13 2,9 22,2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Chat Modal -->
    <div class="modal-overlay" id="newChatModal">
        <div class="modal glass-card">
            <div class="modal-header">
                <h3>Start New Conversation</h3>
                <button class="modal-close" onclick="closeNewChatModal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal-content">
                <div class="search-users">
                    <input type="text" placeholder="Kullanıcı Ara" id="userSearch">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>
                <?php
                use App\Controllers\UsersController;
                foreach(UsersController::listUsers() as $user):
                    if($user['id'] == $_SESSION['user_id']) continue;
                    $statusClass = $user['activityStatus'] === 'Çevrimiçi' ? 'online' : ($user['activityStatus'] === 'AFK' ? 'away' : 'offline');
                    if($user['avatar_path']){
                        $startChat = "startChat('{$user['id']}', '{$user['displayName']}', '{$user['avatar_path']}')";
                    } else {
                        $startChat = "startChat('{$user['id']}', '{$user['displayName']}')";
                    }
                ?> 
                <div class="user-list">
                    <div class="user-item" onclick="<?= $startChat ?>">
                        <div class="user-avatar">
                            <img src="<?= $user['avatar_path'] ? htmlspecialchars($user['avatar_path']) : 'https://placehold.co/40x40' ?>" alt="<?= htmlspecialchars($user['displayName']) ?>">
                            <div class="status-indicator <?= htmlspecialchars($statusClass) ?>"></div>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?= htmlspecialchars($user['displayName']) ?></div>
                            <div class="user-role"><?= htmlspecialchars($user['userRole']) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <script src="./assets/js/javascript.php?file=chat.js"></script>
    <script src="./assets/js/javascript.php?file=script.js"></script>
</body>
</html>
