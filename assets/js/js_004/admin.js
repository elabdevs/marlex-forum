const adminData = {
    stats: {
        totalUsers: 0,
        totalPosts: 0,
        activeTopics: 0,
        pendingReports: 0,
    },
    users: [],
    recentActivity: [

        {
            type: "user_join",
            text: "Yeni kullanıcı kaydoldu: reality1111",
            time: "5 dakika önce",
        },
        {
            type: "topic_created",
            text: "Yeni konu açıldı: Test Konusu 3",
            time: "10 dakika önce",
        },
        {
            type: "user_join",
            text: "Yeni kullanıcı kaydoldu: asd",
            time: "15 dakika önce",
        },
    ],
    reports: [],
}


async function fetchDashboardData() {
    const res = await fetch("/api/admin/getDashboardData")
    const json = await res.json()
    if (json.status && json.data) {
        adminData.stats.totalUsers = json.data.totalUsers
        adminData.stats.totalPosts = json.data.totalPosts
        adminData.stats.activeTopics = json.data.totalTopics
        adminData.stats.pendingReports = json.data.pendingReports
    }
}

async function fetchUsers() {
    const res = await fetch("/api/admin/getUsers")
    const json = await res.json()
    if (json.status && Array.isArray(json.data)) {
        adminData.users = json.data.map(user => ({
            id: user.id,
            name: user.username,
            displayName: user.displayName,
            email: user.email,
            role: user.userRole,
            userPoints: user.userPoints,
            lastLogin: user.last_login,
            joinDate: user.created_at,
            status: user.is_active,
            avatar: user.profile_picture || "https://placehold.co/40x40",
        }))
    }
}

async function fetchReports() {
    const res = await fetch("/api/admin/getReports")
    const json = await res.json()
    if (json.status && Array.isArray(json.data)) {
        adminData.reports = json.data.map(report => ({
            id: report.id,
            reporter: report.reported_username,
            reported: report.user_id,
            post_id: report.post_id,
            post_message: report.post_message,
            time: report.reported_at,
            status: report.status,
        }))
    }
}

async function fetchSystemInfo() {
    const res = await fetch("/api/admin/getSystemInfo")
    const json = await res.json()
    if (json.status && json.data) {
        adminData.systemInfo = json.data
    }
}


document.addEventListener("DOMContentLoaded", async () => {
    await fetchDashboardData()
    await fetchUsers()
    await fetchReports()
    await fetchSystemInfo()
    applyRolePermissions(window.USER_ROLE);
    renderDashboard()
    renderUsers()
    renderReports()
    renderSystemInfo()
    setupEventListeners()
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get("section");
    if(section){
        switchSection(section);
    }
})

function applyRolePermissions(role) {


    const navDashboard = document.querySelector('[data-section="dashboard"]');
    const navUsers = document.querySelector('[data-section="users"]');
    const navContent = document.querySelector('[data-section="content"]');
    const navModeration = document.querySelector('[data-section="moderation"]');
    const navAnalytics = document.querySelector('[data-section="analytics"]');
    const navSettings = document.querySelector('[data-section="settings"]');

    const sectionDashboard = document.getElementById('dashboard');
    const sectionUsers = document.getElementById('users');
    const sectionContent = document.getElementById('content');
    const sectionModeration = document.getElementById('moderation');
    const sectionAnalytics = document.getElementById('analytics');
    const sectionSettings = document.getElementById('settings');


    if (role == 1) {

        show(navDashboard, sectionDashboard);
        show(navUsers, sectionUsers);
        show(navContent, sectionContent);
        show(navModeration, sectionModeration);
        show(navAnalytics, sectionAnalytics);
        show(navSettings, sectionSettings);
    } else if (role == 2) {

        show(navDashboard, sectionDashboard);
        show(navUsers, sectionUsers);
        show(navContent, sectionContent);
        show(navModeration, sectionModeration);
        show(navAnalytics, sectionAnalytics);
        show(navSettings, sectionSettings);

        hideSettingsCards(["Security Settings", "Backup & Restore", "Advanced"]);
    } else if (role == 3) {

        show(navDashboard, sectionDashboard);
        hide(navUsers, sectionUsers);
        show(navContent, sectionContent);
        show(navModeration, sectionModeration);
        hide(navAnalytics, sectionAnalytics);
        hide(navSettings, sectionSettings);
    } else if (role == 4) {

        show(navDashboard, sectionDashboard);
        hide(navUsers, sectionUsers);
        hide(navContent, sectionContent);
        show(navModeration, sectionModeration);
        hide(navAnalytics, sectionAnalytics);
        hide(navSettings, sectionSettings);

    }
}


function show(navBtn, section) {
    if (navBtn) navBtn.style.display = "";
    if (section) section.style.display = "";
}

function hide(navBtn, section) {
    if (navBtn) navBtn.style.display = "none";
    if (section) section.style.display = "none";
}

function hideSettingsCards(cardTitles) {
    document.querySelectorAll('.settings-card').forEach(card => {
        const title = card.querySelector('.settings-title');
        if (title && cardTitles.includes(title.textContent.trim())) {
            card.style.display = "none";
        }
    });
}

function setupEventListeners() {

    const userSearch = document.getElementById("userSearch")
    if (userSearch) {
        userSearch.addEventListener("input", handleUserSearch)
    }


    const roleFilter = document.getElementById("userRoleFilter")
    const statusFilter = document.getElementById("userStatusFilter")
    if (roleFilter) roleFilter.addEventListener("change", handleUserFilter)
    if (statusFilter) statusFilter.addEventListener("change", handleUserFilter)
}

function switchSection(sectionName) {
    history.pushState(null, '', `?section=${sectionName}`);

    document.querySelectorAll(".nav-item").forEach((item) => item.classList.remove("active"))
    document.querySelectorAll(".admin-section").forEach((section) => section.classList.remove("active"))


    document.querySelector(`[data-section="${sectionName}"]`).classList.add("active")
    document.getElementById(sectionName).classList.add("active")


    switch (sectionName) {
        case "users":
            renderUsers()
            break
        case "content":
            renderContent()
            break
        case "moderation":
            renderReports()
            break
    }
}

async function reloadAllData() {
    await fetchDashboardData();
    await fetchUsers();
    await fetchReports();
    await fetchSystemInfo();
    renderDashboard();
    renderUsers();
    renderReports();
    renderSystemInfo();
    renderContent();
}

function renderDashboard() {
    const statCards = document.querySelectorAll(".stat-card .stat-number");
    if (statCards.length >= 4) {
        statCards[0].textContent = adminData.stats.totalUsers;
        statCards[1].textContent = adminData.stats.totalPosts;
        statCards[2].textContent = adminData.stats.activeTopics;
        statCards[3].textContent = adminData.stats.pendingReports;
    }

    const activityContainer = document.getElementById("dashboardActivity");
    if (activityContainer) {
        activityContainer.innerHTML = adminData.recentActivity
            .map(
                (activity) => `
      <div class="activity-item">
        <div class="activity-icon">
          ${getActivityIcon(activity.type)}
        </div>
        <div class="activity-content">
          <div class="activity-text">${activity.text}</div>
          <div class="activity-time">${activity.time}</div>
        </div>
      </div>
    `
            )
            .join("");
    }

    renderUsers();
    renderReports();
    renderSystemInfo();
    renderContent();
}

function refreshDashboard() {
    reloadAllData().then(() => {
        showNotification("Panel yenilendi", "success");
    });
}

function convertUserStatus(userStatus){
    switch(userStatus) {
        case 1:
            return "Aktif";
        case 0:
            return "Pasif";
        default:
            return "Bilinmiyor";
    }
}

function convertUserStatusBadge(userStatus){
    switch(userStatus) {
        case 1:
            return "active";
        case 0:
            return "inactive";
        default:
            return "unknown";
    }
}

function renderUsers() {
    const usersTableBody = document.getElementById("usersTableBody");
    if (!usersTableBody) return;

    usersTableBody.innerHTML = adminData.users
        .map(
            (user) => `
                  <tr>
                    <td>${user.id}</td>
                    <td>
                      <div class="user-info">
                        <img src="${user.avatar}" alt="${user.name}" class="user-avatar">
                        <div>
                          <div class="user-displayName"><b>${user.displayName}</b></div>
                          <div class="user-username">${user.name}</div>
                        </div>
                      </div>
                    </td>
                    <td>${user.email}</td>
                    <td>
                      <span class="role-badge role-${user.role}">${user.role}</span>
                    </td>
                    <td>${user.userPoints}</td>
                    <td>${formatDate(user.lastLogin)}</td>
                    <td>${formatDate(user.joinDate)}</td>
                    <td>
                      <span class="status-badge status-${convertUserStatusBadge(user.status)}">${convertUserStatus(user.status)}</span>
                    </td>
                    <td>
                      <div class="action-buttons">
                        <button class="action-btn" onclick="editUser(${user.id})">Düzenle</button>
                        <button class="action-btn danger" onclick="suspendUser(${user.id})">Askıya Al</button>
                      </div>
                    </td>
                  </tr>
                `
        )
        .join("");
}

function renderReports() {
    const reportsList = document.getElementById("reportsList");
    if (!reportsList) return;

    reportsList.innerHTML = adminData.reports
        .map(
            (report) => `
    <div class="report-item liquid-glass">
      <div class="report-header">
        <div class="report-type">
          <span class="report-badge">${report.status}</span>
        </div>
        <div class="report-time">${report.time}</div>
      </div>
      <div class="report-content">
        <p>${report.post_message}</p>
        <div class="report-meta">
          <span>Reported by: <strong>${report.reporter}</strong></span>
          <span>Against: <strong>${report.reported}</strong></span>
        </div>
      </div>
      <div class="report-actions">
        <button class="btn btn-primary" onclick="approveReport(${report.id})">Approve</button>
        <button class="btn btn-outline" onclick="dismissReport(${report.id})">Dismiss</button>
        <button class="btn btn-secondary" onclick="viewDetails(${report.id})">View Details</button>
      </div>
    </div>
  `
        )
        .join("");
}

function renderContent() {
    const contentTableHead = document.getElementById("contentTableHead")
    const contentTableBody = document.getElementById("contentTableBody")

    if (!contentTableHead || !contentTableBody) return

    contentTableHead.innerHTML = `
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Category</th>
      <th>Replies</th>
      <th>Created</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  `

    const samplePosts = [{
            id: 1,
            title: "Welcome to the forum",
            author: "Admin",
            category: "Announcements",
            replies: 24,
            created: "2024-01-15",
            status: "published",
        },
        {
            id: 2,
            title: "How to use the forum",
            author: "ModeratorMike",
            category: "General",
            replies: 12,
            created: "2024-01-14",
            status: "published",
        },
    ]

    contentTableBody.innerHTML = samplePosts
        .map(
            (post) => `
    <tr>
      <td>${post.title}</td>
      <td>${post.author}</td>
      <td>${post.category}</td>
      <td>${post.replies}</td>
      <td>${formatDate(post.created)}</td>
      <td><span class="status-badge status-${post.status}">${post.status}</span></td>
      <td>
        <div class="action-buttons">
          <button class="action-btn" onclick="editContent(${post.id})">Edit</button>
          <button class="action-btn danger" onclick="deleteContent(${post.id})">Delete</button>
        </div>
      </td>
    </tr>
  `,
        )
        .join("")
}

function renderSystemInfo() {
    const systemInfoContainer = document.getElementById("systemStatusList")
    if (!systemInfoContainer || !adminData.systemInfo) return

    systemInfoContainer.innerHTML = `
    <ul>
      <li>Sistem Durumu: <span class="badge">${adminData.systemInfo["Sistem Durumu"]}</span></li>
      <li>Sunucu Durumu: <span class="badge">${adminData.systemInfo["Sunucu Durumu"]}</span></li>
      <li>Son Yedekleme: <span>${adminData.systemInfo["Son Yedekleme"]}</span></li>
      <li>Disk Kullanımı: <span>${adminData.systemInfo["Disk Kullanımı"]}</span></li>
      <li>CPU Kullanımı: <span>${adminData.systemInfo["CPU Kullanımı"]}</span></li>
      <li>RAM Kullanımı: <span>${adminData.systemInfo["RAM Kullanımı"]}</span></li>
    </ul>
  `
}

function getActivityIcon(type) {
    const icons = {
        user_join: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        post_reported: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        user_banned: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="m4.9 4.9 14.2 14.2"></path></svg>',
        topic_created: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
    }
    return icons[type] || icons.topic_created
}

function formatDate(dateString) {
    const date = new Date(dateString)
    return date.toLocaleDateString()
}


function refreshDashboard() {
    renderDashboard()
    showNotification("Panel yenilendi", "success")
}

function createAnnouncement() {
    showNotification("Duyuru oluşturma yakında!", "info")
}

function moderateContent() {
    switchSection("moderation")
}

function manageUsers() {
    switchSection("users")
}

function viewAnalytics() {
    switchSection("analytics")
}

function exportUsers() {
    showNotification("User export initiated", "success")
}

function inviteUser() {
    showNotification("User invitation feature coming soon!", "info")
}

function editUser(userId) {
    openEditUserModal(userId);
}

function suspendUser(userId) {
    if (confirm("Are you sure you want to suspend this user?")) {
        showNotification(`User ${userId} suspended`, "success")
    }
}

function approveReport(reportId) {
    showNotification(`Report ${reportId} approved`, "success")

    adminData.reports = adminData.reports.filter((r) => r.id !== reportId)
    renderReports()
}

function dismissReport(reportId) {
    showNotification(`Report ${reportId} dismissed`, "info")

    adminData.reports = adminData.reports.filter((r) => r.id !== reportId)
    renderReports()
}

function viewDetails(reportId) {
    showNotification(`Viewing details for report ${reportId}`, "info")
}

function clearQueue() {
    if (confirm("Clear all resolved reports?")) {
        showNotification("Queue cleared", "success")
    }
}

function bulkActions() {
    showNotification("Bulk actions feature coming soon!", "info")
}

function switchContentTab(tabName) {

    document.querySelectorAll(".tab-btn").forEach((btn) => btn.classList.remove("active"))

    document.querySelector(`[data-tab="${tabName}"]`).classList.add("active")


    renderContent()
}

function editContent(contentId) {
    showNotification(`Editing content ${contentId}`, "info")
}

function deleteContent(contentId) {
    if (confirm("Are you sure you want to delete this content?")) {
        showNotification(`Content ${contentId} deleted`, "success")
    }
}

function saveSettings() {
    console.log(document.querySelector('[name="activatePremiumAccountSystem"]').value)

    const settingKeys = [
        "siteInfo",
        "defaultSiteDescription",
        "chat_active",
        "captchaExpiration",
        "viewTopicIsLoggedCheck",
        "dashboardAnnounceLimit",
        "registirationActive",
        "forceEmailVerification",
        "postsRequireAccept",
        "maxPostLength",
        "bannedWordsTable",
        "smtpServer",
        "smtpPort",
        "sendWelcomeMail",
        "sendNotificationMails",
        "sessionExpiration",
        "require2FA",
        "logAdminActions",
        "minPasswordLength",
        "minUsernameLength",
        "defaultUserRole",
        "allowModeratorBans",
        "usersRemoveOwnPosts",
        "darkModeToggle",
        "customCssTable",
        "instantMailNotifications",
        "dailySummaryMail",
        "googleAnalyticsId",
        "maintenceMode",
        "allowApiUsage",
        "welcomeMessage",
        "forceUploadProcilePicture",
        "allowChangeUsername",
        "requireCaptchaRegistiration",
        "maxPostTitleLength",
        "maxImagePerPost",
        "maxTagPerPosts",
        "maxPostsPerMinute",
        "captchaType",
        "enableRankSystem",
        "ranksTable",
        "automaticallyAssignBadges",
        "customPagesTable",
        "adsSnippet",
        "sponsoredTopicsTable",
        "activatePremiumAccountSystem",
        "kvkkText",
        "cookiePolicy",
        "adminLogsTable",
        "moderationLogsTable",
        "sessionLogsTable",
        "defaultLanguage",
        "translateTable",
        "activatePWA",
        "allowInstantMobileNotificatons",
        "pageDataTable"
    ];

    const settingsData = {};

    settingKeys.forEach(key => {

        const input = document.querySelector(`[name="${key}"]`);
        if (input) {
            if (input.type === "checkbox") {
                settingsData[key] = input.checked ? "true" : "false";
            } else if (input.type === "radio") {
                if (input.checked) settingsData[key] = input.value;
            } else {
                settingsData[key] = input.value;
            }
        }
    });


    if (settingsData.siteInfo === undefined) {
        const siteName = document.querySelector('[name="siteName"]')?.value || "";
        const defaultSiteDescription = document.querySelector('[name="defaultSiteDescription"]')?.value || "";
        const defaultSiteTitle = document.querySelector('[name="defaultSiteTitle"]')?.value || "";
        settingsData.siteInfo = JSON.stringify({
            data: {
                siteName,
                defaultSiteDescription,
                defaultSiteTitle
            }
        });
    }

    fetch("/api/admin/saveSiteSettings", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(settingsData),
        })
        .then(res => res.json())
        .then(data => {
            showNotification("Ayarlar başarıyla kaydedildi!", "success");
            console.log("Gönderilen ayarlar:", settingsData);
            console.log("API cevabı:", data);
        })
        .catch(() => {
            showNotification("Ayarlar gönderilemedi!", "error");
        });
}

function handleUserSearch(event) {
    const searchTerm = event.target.value.toLowerCase()


    console.log("Searching users:", searchTerm)
}

function handleUserFilter() {
    const roleFilter = document.getElementById("userRoleFilter").value
    const statusFilter = document.getElementById("userStatusFilter").value

    console.log("Filtering users:", {
        role: roleFilter,
        status: statusFilter
    })
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

  #backupProgressContainer {
    display: none;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  #progress-bar {
    width: 0;
    height: 0.5rem;
    background: #10b981;
    border-radius: 0.25rem;
    transition: width 0.2s;
  }

  #log {
    white-space: pre-wrap;
    background: #f3f4f6;
    padding: 0.5rem;
    border-radius: 0.375rem;
    font-family: monospace;
    font-size: 0.875rem;
    color: #111827;
  }
`
document.head.appendChild(style)
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");
const overlay = document.getElementById("modalOverlay");
openBtn.onclick = () => overlay.classList.add("active");
closeBtn.onclick = () => overlay.classList.remove("active");
overlay.onclick = (e) => {
    if (e.target === overlay) overlay.classList.remove("active");
}
document.getElementById('duyuruForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const title = document.getElementById('annTitle').value;
    const message = document.getElementById('annContent').value;
    const dtInput = document.getElementById('announcementDate');
    let formattedDate = '';

    if (dtInput && dtInput.value) {
        let val = dtInput.value;
        let [date, time] = val.split('T');
        if (date && time) {
            if (time.length === 5) time += ':00';
            formattedDate = date + ' ' + time;
        }
    }

    console.log(message);
    console.log(title);
    console.log(formattedDate);

    if (title && message && formattedDate) {
        try {
            const res = await fetch('/api/admin/addAnnouncement', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title,
                    message,
                    dtInput: formattedDate
                })
            });
            const json = await res.json();
            if (json.status === true) {
                showNotification('Duyuru başarıyla eklendi!', 'success');
                dtInput.form.reset();
            } else {
                showNotification('Duyuru eklenemedi!', 'error');
            }
        } catch (e) {
            showNotification('API bağlantı hatası! ' + e.message, 'error');
        }
    } else {
        console.log("Veriler eksik gönderildi");
    }

});

const backupModal = document.getElementById("backupModalOverlay");
const openBackupBtn = document.querySelector('button[onclick="demoBackup()"]');
const closeBackupBtn = document.getElementById("closeBackupModal");
if (openBackupBtn && backupModal && closeBackupBtn) {
    openBackupBtn.onclick = () => backupModal.classList.add("active");
    closeBackupBtn.onclick = () => backupModal.classList.remove("active");
    backupModal.onclick = (e) => {
        if (e.target === backupModal) backupModal.classList.remove("active");
    };
}

function startBackup(type) {
    const progressBar = document.getElementById("progress-bar");
    const log = document.getElementById("log");
    const progressContainer = document.getElementById("backupProgressContainer");
    progressContainer.style.display = "none";
    progressBar.style.width = "0%";
    progressBar.textContent = "0%";
    log.textContent = "";

    fetch("/api/admin/backup/" + encodeURIComponent(type))
        .then(response => {
            if (!response.body) throw new Error("Stream yok!");
            const reader = response.body.getReader();
            const decoder = new TextDecoder();

            function readChunk() {
                return reader.read().then(({
                    done,
                    value
                }) => {
                    if (done) return;
                    const text = decoder.decode(value);
                    text.split("\n").forEach(line => {
                        line = line.trim();
                        if (!line) return;
                        try {
                            const obj = JSON.parse(line.replace(/^data:\s*/, ""));
                            if (obj.percent !== undefined) {
                                progressBar.style.width = obj.percent + "%";
                                progressBar.textContent = obj.percent + "%";
                            }
                            if (obj.msg) {
                                log.textContent += obj.msg + "\n";
                            }
                            if (obj.status === "success" && obj.file) {
                                backupModal.classList.remove("active")
                                showNotification("Yedekleme tamamlandı, indirme başlatılıyor.", "success");
                                setTimeout(() => window.open(obj.file, "_blank"), 800);
                            }
                        } catch (e) {
                            log.textContent += line + "\n";
                        }
                    });
                    return readChunk();
                });
            }
            return readChunk();
        })
        .catch(err => {
            showNotification("Yedekleme sırasında hata: " + err.message, "error");
            log.textContent += "\nHata: " + err.message;
        });
}


document.getElementById("fullBackupBtn").onclick = () => startBackup("full");
document.getElementById("criticalBackupBtn").onclick = () => startBackup("critical");
if (fullBackupBtn) {
    fullBackupBtn.onclick = () => startBackup("full");
}
if (criticalBackupBtn) {
    criticalBackupBtn.onclick = () => startBackup("critical");
}


const restoreModal = document.getElementById("restoreModalOverlay");
const openRestoreBtn = document.getElementById("openRestoreModal");
const closeRestoreBtn = document.getElementById("closeRestoreModal");
if (openRestoreBtn && restoreModal && closeRestoreBtn) {
    openRestoreBtn.onclick = () => restoreModal.classList.add("active");
    closeRestoreBtn.onclick = () => restoreModal.classList.remove("active");
    restoreModal.onclick = (e) => {
        if (e.target === restoreModal) restoreModal.classList.remove("active");
    };
}


const restoreForm = document.getElementById("restoreForm");
if (restoreForm) {
    restoreForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const fileInput = document.getElementById("restoreZipInput");
        if (!fileInput.files.length) {
            showNotification("Lütfen bir ZIP dosyası seçin!", "error");
            return;
        }
        const formData = new FormData();
        formData.append("backup_zip", fileInput.files[0]);


        const progressBar = document.getElementById("restore-progress-bar");
        const log = document.getElementById("restore-log");
        const progressContainer = document.getElementById("restoreProgressContainer");
        progressContainer.style.display = "block";
        progressBar.style.width = "0%";
        progressBar.textContent = "0%";
        log.textContent = "";


        fetch("/api/admin/restoreBackup", {
            method: "POST",
            body: formData,
        }).then(response => {
            if (!response.body) throw new Error("Stream yok!");
            const reader = response.body.getReader();
            const decoder = new TextDecoder();

            function readChunk() {
                return reader.read().then(({
                    done,
                    value
                }) => {
                    if (done) return;
                    const text = decoder.decode(value);
                    text.split("\n").forEach(line => {
                        line = line.trim();
                        if (!line) return;
                        try {
                            const obj = JSON.parse(line.replace(/^data:\s*/, ""));
                            if (obj.percent !== undefined) {
                                progressBar.style.width = obj.percent + "%";
                                progressBar.textContent = obj.percent + "%";
                            }
                            if (obj.msg) {
                                log.textContent += obj.msg + "\n";
                            }
                            if (obj.percent === 100) {
                                showNotification("Yedek başarıyla geri yüklendi!", "success");
                            }
                        } catch (e) {
                            log.textContent += line + "\n";
                        }
                    });
                    return readChunk();
                });
            }
            return readChunk();
        }).catch(err => {
            showNotification("Geri yükleme sırasında hata: " + err.message, "error");
            log.textContent += "\nHata: " + err.message;
        });
    });
}

document.addEventListener("DOMContentLoaded", function() {

    const restoreModal = document.getElementById("restoreModalOverlay");
    const openRestoreBtn = document.getElementById("openRestoreModal");
    const closeRestoreBtn = document.getElementById("closeRestoreModal");
    if (openRestoreBtn && restoreModal && closeRestoreBtn) {
        openRestoreBtn.onclick = () => restoreModal.classList.add("active");
        closeRestoreBtn.onclick = () => restoreModal.classList.remove("active");
        restoreModal.onclick = (e) => {
            if (e.target === restoreModal) restoreModal.classList.remove("active");
        };
    }
});

function openEditUserModal(userId) {
    const overlay = document.getElementById("editUserModalOverlay");
    overlay.style.display = "flex";
    overlay.classList.add("active");
    document.getElementById("editUserForm").reset();
    fetch(`/api/user/${userId}`)
        .then(res => res.json())
        .then(json => {
            console.log(json);
            if (json.status && json.data) {
                document.getElementById("editUserId").value = json.data.id;
                document.getElementById("editUsername").value = json.data.username || "";
                document.getElementById("editUserEmail").value = json.data.email || "";
                document.getElementById("editUserRole").value = json.data.role || "member";
                document.getElementById("editUserStatus").value = json.data.is_active ? "1" : "0";
            }
        });
}
function closeEditUserModal() {
    const overlay = document.getElementById("editUserModalOverlay");
    overlay.style.display = "none";
    overlay.classList.remove("active");
}

document.getElementById("closeEditUserModal").onclick = closeEditUserModal;
document.getElementById("editUserModalOverlay").onclick = function(e) {
    if (e.target === this) closeEditUserModal();
};

document.getElementById("editUserForm").onsubmit = async function(e) {
    e.preventDefault();
    const userId = document.getElementById("editUserId").value;
    const data = {
        username: document.getElementById("editUsername").value,
        email: document.getElementById("editUserEmail").value,
        userRole: document.getElementById("editUserRole").value,
        is_active: document.getElementById("editUserStatus").value
    };
    try {
        const res = await fetch(`/api/user/${userId}/save`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.status) {
            showNotification("Kullanıcı başarıyla güncellendi!", "success");
            closeEditUserModal();
            await fetchUsers();
            renderUsers();
        } else {
            showNotification("Kullanıcı güncellenemedi!", "error");
        }
    } catch (err) {
        showNotification("API hatası: " + err.message, "error");
    }
};