let isTyping = false;
let offset = 0;
const limit = 20;
let loading = false;
let reachedTop = false;
let userInfo = {
    username: "Bilinmeyen Kullanıcı",
    profilePhoto: "https://placehold.co/40"
}

const ws = new WebSocket("ws://192.168.1.7:8080");

ws.onopen = () => {
    ws.send(JSON.stringify({
        type: "identify",
        userId: currentUserId
    }));
};

const pendingSidebarUsers = new Set();

ws.onmessage = (event) => {
    try {
        const data = JSON.parse(event.data);
        console.log(data);
        if (data.type === "admin_broadcast") {
            showNotification(data.text, "info");
        }

        if ((data.type === "typing" || data.type === "message") && data.from !== currentUserId) {
            if (
                !document.querySelector(`.chat-item[data-user="${data.from}"]`) &&
                !pendingSidebarUsers.has(data.from)
            ) {
                pendingSidebarUsers.add(data.from);
                fetch('/api/user/' + data.from)
                    .then(res => res.json())
                    .then(userData => {
                        if (userData.status) {
                            addNewChatToSidebar(
                                data.from,
                                userData.data.username,
                                userData.data.profile_picture ? userData.data.profile_picture : "https://placehold.co/40x40"
                            );
                        }
                    })
                    .finally(() => {
                        pendingSidebarUsers.delete(data.from);
                    });
            }
        }

        if (data.type == "message") {
            showNotification("Yeni Mesaj: " + data.text, "info");
            if (data.from === currentChat || data.to === currentChat || data.from === currentUserId) {
                const msgType = data.from === currentUserId ? "sent" : "received";
                addMessage(data.text, msgType, getCurrentTime(), true);
            }
        }
        if (data.type == "typing") {
            if (data.from === currentChat || data.to === currentChat || data.from === currentUserId) showTypingIndicator();
        }
        if (data.type == "stop_typing" || data.to === currentChat || data.from === currentUserId) {
            if (data.from === currentChat) hideTypingIndicator();
        }
    } catch (err) {
        console.error("WS mesaj parse hatası:", err);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    initializeChat();
    setupEventListeners();
    setupMessageObserver();
});

function initializeChat() {
    const messageInput = document.getElementById("messageInput");
    if (messageInput) {
        messageInput.addEventListener("input", function() {
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";

            if (currentChat) {
                sendTyping();
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(() => {
                    ws.send(JSON.stringify({
                        type: "stop_typing",
                        from: currentUserId,
                        to: currentChat
                    }));
                }, 2000);
            }
        });

        messageInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    const chatSearch = document.getElementById("chatSearch");
    if (chatSearch) chatSearch.addEventListener("input", function() {
        filterChats(this.value);
    });

    const userSearch = document.getElementById("userSearch");
    if (userSearch) userSearch.addEventListener("input", function() {
        filterUsers(this.value);
    });

    document.querySelectorAll(".chat-item").forEach((item) => {
        item.addEventListener("click", function() {
            switchChat(this.dataset.user);
        });
    });
}

function setupEventListeners() {
    document.getElementById("newChatModal")?.addEventListener("click", function(e) {
        if (e.target === this) closeNewChatModal();
    });

    const messagesContainer = document.getElementById("chatMessages");
    if (messagesContainer) {
        messagesContainer.addEventListener("scroll", function() {
            if (this.scrollTop === 0 && !loading && !reachedTop) {
                loadChatMessages(currentChat);
            }
        });
    }
}

function setupMessageObserver() {
    const messagesContainer = document.getElementById("chatMessages");
    if (!messagesContainer) return;

    const observer = new MutationObserver(() => {
        scrollToBottomIfNeeded();
    });
    observer.observe(messagesContainer, {
        childList: true
    });
}

function scrollToBottomIfNeeded() {
    const messagesContainer = document.getElementById("chatMessages");
    if (!messagesContainer) return;

    if (messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < 50) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

function sendMessage() {
    hideTypingIndicator();
    const messageInput = document.getElementById("messageInput");
    const messageText = messageInput.value.trim();
    if (!messageText) return;

    ws.send(JSON.stringify({
        type: "stop_typing",
        from: currentUserId,
        to: currentChat
    }));

    const msgObj = {
        type: "message",
        from: currentUserId,
        to: currentChat,
        text: messageText
    };
    ws.send(JSON.stringify(msgObj));

    fetch('/api/sendMessage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(msgObj)
    });

    addMessage(messageText, "sent", getCurrentTime(), true);
    messageInput.value = "";
    messageInput.style.height = "auto";
}

function addMessage(text, type, timestamp, appendAtBottom = false) {
    const messagesContainer = document.getElementById("chatMessages");
    if (!messagesContainer) return;

    const emptyState = document.getElementById("chatEmptyState");
    if (emptyState) emptyState.style.display = "none";

    const messageGroup = document.createElement("div");
    messageGroup.className = "message-group";

    const message = document.createElement("div");
    message.className = `message ${type}`;
    var profilePhoto = userInfo.profilePhoto ? userInfo.profilePhoto : "https://placehold.co/40";
    var username = userInfo.username ? userInfo.username : "Unknown User";

    if (type === "received") {
        message.innerHTML = `
      <div class="message-avatar">
        <img src="${profilePhoto}" alt="${username}">
      </div>
      <div class="message-content">
        <div class="message-text">${text}</div>
        <div class="message-time" data-timestamp="${timestamp}">${formatDate(timestamp)}</div>
      </div>`;
    } else {
        message.innerHTML = `
      <div class="message-content">
        <div class="message-text">${text}</div>
        <div class="message-time" data-timestamp="${timestamp}">${formatDate(timestamp)}</div>
      </div>`;
    }

    messageGroup.appendChild(message);

    if (appendAtBottom) {
        messagesContainer.appendChild(messageGroup);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    } else {
        const prevScrollHeight = messagesContainer.scrollHeight;
        messagesContainer.insertBefore(messageGroup, messagesContainer.firstChild);
        messagesContainer.scrollTop = messagesContainer.scrollHeight - prevScrollHeight;
    }
}

function updateUserInfo(userId) {
    return fetch('/api/user/' + userId)
        .then(res => res.json())
        .then(data => {
            if (data.status == true) {
                userInfo = {
                    username: data.data.username,
                    profilePhoto: data.data.profile_picture ? data.data.profile_picture : "https://placehold.co/40"
                };
                console.log(userInfo);
            }
        });
}

function switchChat(userId) {
    hideTypingIndicator();
    document.querySelectorAll(".chat-item").forEach(item => item.classList.remove("active"));
    const chatItem = document.querySelector(`[data-user="${userId}"]`);
    if (chatItem) chatItem.classList.add("active");

    currentChat = userId;
    offset = 0;
    reachedTop = false;

    updateUserInfo(currentChat);
    const messagesContainer = document.getElementById("chatMessages");
    const messageInput = document.getElementById("chatInputContainer")
    const userInfoHeader = document.getElementById("chatWindowHeader");
    messagesContainer.innerHTML = "";
    messageInput.style.removeProperty("display");
    userInfoHeader.style.removeProperty("display");
    fetch("/api/user/" + userId)
        .then(res => res.json())
        .then(data => {
            if (data.status == true) {
                document.getElementById("chatHeaderUsername").textContent = data.data.username;
                document.getElementById("chatHeaderUserOnlineStatus").textContent = data.data.activityStatus;
                document.getElementById("chatHeaderAvatar").src = data.data.profile_picture ? data.data.profile_picture : "https://placehold.co/40x40";
                if (data.data.activityStatus == "Çevrimiçi") {
                    document.getElementById("chatHeaderStatusIndicator").classList.add("online");
                } else if (data.data.activityStatus == "AFK") {
                    document.getElementById("chatHeaderStatusIndicator").classList.add("away");
                } else {
                    document.getElementById("chatHeaderStatusIndicator").classList.add("offline");
                }
            }
        });

    const emptyState = document.getElementById("chatEmptyState");
    if (emptyState) {
        messagesContainer.appendChild(emptyState);
        emptyState.style.display = "flex";
    }

    loadChatMessages(userId);
}

function scrollToBottom() {
    const messagesContainer = document.getElementById("chatMessages");
    if (messagesContainer) messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function getCurrentTime() {
    const now = new Date();
    return `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}:${String(now.getSeconds()).padStart(2,'0')}`;
}

function loadChatMessages(userId) {
    if (loading || reachedTop) return;
    loading = true;

    const messagesContainer = document.getElementById("chatMessages");
    const prevScrollHeight = messagesContainer.scrollHeight;

    fetch(`/api/getMessages/${userId}?offset=${offset}&count=${limit}`)
        .then(res => res.json())
        .then(data => {
            const messages = data.data || [];
            if (messages.length === 0) {
                reachedTop = true;
                return;
            }
            offset += messages.length;

            messages.forEach(msg => {
                const msgType = msg.msg_from == userId ? "received" : "sent";
                addMessage(msg.content, msgType, msg.sended_at, false);
            });

            messagesContainer.scrollTop = messagesContainer.scrollHeight - prevScrollHeight;
        })
        .finally(() => {
            loading = false;
        });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMs = now - date;
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
    const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
    const diffInWeeks = Math.floor(diffInDays / 7);
    const diffInMonths = Math.floor(diffInDays / 30);
    const diffInYears = Math.floor(diffInDays / 365);

    if (diffInMinutes < 1) return "Az önce";
    else if (diffInMinutes < 60) return `${diffInMinutes} dakika önce`;
    else if (diffInHours < 24) return `${diffInHours} saat önce`;
    else if (diffInDays < 7) return `${diffInDays} gün önce`;
    else if (diffInWeeks < 5) return `${diffInWeeks} hafta önce`;
    else if (diffInMonths < 12) return `${diffInMonths} ay önce`;
    else return `${diffInYears} yıl önce`;
}

setInterval(() => {
    document.querySelectorAll(".message-time").forEach(el => {
        const ts = el.getAttribute("data-timestamp");
        el.textContent = formatDate(ts);
    });
}, 2000);

function filterChats(searchTerm) {
    document.querySelectorAll(".chat-item").forEach(item => {
        const name = item.querySelector(".chat-name").textContent.toLowerCase();
        const preview = item.querySelector(".chat-preview").textContent.toLowerCase();
        item.style.display = name.includes(searchTerm.toLowerCase()) || preview.includes(searchTerm.toLowerCase()) ? "flex" : "none";
    });
}

function filterUsers(searchTerm) {
    document.querySelectorAll(".user-item").forEach(item => {
        const name = item.querySelector(".user-name").textContent.toLowerCase();
        const role = item.querySelector(".user-role").textContent.toLowerCase();
        item.style.display = name.includes(searchTerm.toLowerCase()) || role.includes(searchTerm.toLowerCase()) ? "flex" : "none";
    });
}

function openNewChatModal() {
    const modal = document.getElementById("newChatModal");
    modal.classList.add("active");
}

function closeNewChatModal() {
    const modal = document.getElementById("newChatModal");
    modal.classList.remove("active");
}

function startChat(userId, username, profilePhoto = "https://placehold.co/40x40") {
    console.log(profilePhoto);
    if (!document.querySelector(`[data-user="${userId}"]`)) addNewChatToSidebar(userId, username, profilePhoto);
    switchChat(userId);
    closeNewChatModal();
}

function addNewChatToSidebar(userId, username, profilePhoto) {
    const chatList = document.getElementById("chatList");
    const chatItem = document.createElement("div");
    chatItem.className = "chat-item";
    chatItem.dataset.user = userId;
    chatItem.innerHTML = `
    <div class="chat-avatar">
      <img src="${profilePhoto}" alt="${username}">
      <div class="status-indicator online"></div>
    </div>
    <div class="chat-info">
      <div class="chat-name">${username}</div>
      <div class="chat-preview">Yeni Sohbet</div>
      <div class="chat-time">Az önce</div>
    </div>`;
    chatItem.addEventListener("click", () => switchChat(userId));
    chatList.insertBefore(chatItem, chatList.firstChild);
}

let typingTimeout;

function sendTyping() {
    ws.send(JSON.stringify({
        type: "typing",
        from: currentUserId,
        to: currentChat
    }));
}

function showTypingIndicator() {
    let messagesContainer = document.getElementById("chatMessages");
    if (!messagesContainer) return;
    if (document.getElementById("typing-indicator")) return;

    const typingDiv = document.createElement("div");
    typingDiv.className = "typing-indicator";
    typingDiv.id = "typing-indicator";
    typingDiv.innerHTML = `
    <div class="message-avatar">
      <img src="${userInfo.profilePhoto}" alt="User">
    </div>
    <div class="typing-dots">
      <span></span><span></span><span></span>
    </div>
  `;
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function hideTypingIndicator() {
    const typingDiv = document.getElementById("typing-indicator");
    if (typingDiv) typingDiv.remove();
}

function showNotification(message, type = "info") {
    const notification = document.createElement("div")
    notification.className = `notification notification-${type}`
    notification.textContent = message
    notification.style.cssText = `
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: ${type === "success" ? "#10b981" : type === "error" ? "#ef4444" : "#3b82f6"};
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    animation: slideIn 0.3s ease-out;
  `

    document.body.appendChild(notification)

    setTimeout(() => {
        notification.style.animation = "slideOut 0.3s ease-in"
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification)
            }
        }, 300)
    }, 3000)
}

const style = document.createElement("style")
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(100%);
      opacity: 0;
    }
  }

  .user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
  }

  .user-name {
    font-weight: 600;
    color: var(--text-primary);
  }

  .user-email {
    font-size: 0.875rem;
    color: var(--text-secondary);
  }

  .role-badge, .status-badge, .report-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .role-admin { background: #dc2626; color: white; }
  .role-moderator { background: #ea580c; color: white; }
  .role-member { background: #059669; color: white; }

  .status-active { background: #10b981; color: white; }
  .status-suspended { background: #f59e0b; color: white; }
  .status-banned { background: #ef4444; color: white; }
  .status-published { background: #10b981; color: white; }

  .report-spam { background: #f59e0b; color: white; }
  .report-harassment { background: #ef4444; color: white; }

  .action-buttons {
    display: flex;
    gap: 0.5rem;
  }

  .action-btn {
    padding: 0.25rem 0.5rem;
    border: 1px solid var(--border);
    border-radius: 0.25rem;
    background: var(--background);
    color: var(--text-primary);
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
  }

  .action-btn:hover {
    background: var(--secondary);
  }

  .action-btn.danger {
    border-color: #ef4444;
    color: #ef4444;
  }

  .action-btn.danger:hover {
    background: #ef4444;
    color: white;
  }

  .report-item {
    margin-bottom: 1rem;
    padding: 1.5rem;
    border-radius: 0.75rem;
  }

  .report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }

  .report-content p {
    margin-bottom: 0.5rem;
    color: var(--text-primary);
  }

  .report-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
  }

  .report-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
  }
`
document.head.appendChild(style)