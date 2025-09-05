const profileData = {
    name: "Alexandra Johnson",
    title: "Senior Community Manager",
    bio: "Passionate about building inclusive communities and fostering meaningful discussions. Love helping others and sharing knowledge about web development and design.",
    joinDate: "January 2023",
    reputation: 1247,
    posts: 342,
    likes: 1247,
    bestAnswers: 89,
    profileViews: 12400,
}

const activityData = [{
        type: "post",
        icon: "message-circle",
        text: "Posted a new topic in General Discussion",
        time: "2 hours ago",
        link: "#",
    },
    {
        type: "like",
        icon: "heart",
        text: "Received 5 likes on your post about forum etiquette",
        time: "4 hours ago",
        link: "#",
    },
    {
        type: "reply",
        icon: "reply",
        text: "Replied to 'Technical issue with image uploads'",
        time: "6 hours ago",
        link: "#",
    },
    {
        type: "achievement",
        icon: "award",
        text: "Earned the 'Helpful Community Member' badge",
        time: "1 day ago",
        link: "#",
    },
    {
        type: "post",
        icon: "message-circle",
        text: "Started a new discussion about dark mode support",
        time: "2 days ago",
        link: "#",
    },
]

const achievementsData = [{
        icon: "🏆",
        title: "Community Leader",
        description: "Helped 100+ members",
    },
    {
        icon: "💬",
        title: "Great Conversationalist",
        description: "Posted 300+ messages",
    },
    {
        icon: "❤️",
        title: "Well Liked",
        description: "Received 1000+ likes",
    },
    {
        icon: "⭐",
        title: "Best Answer",
        description: "50+ best answers",
    },
    {
        icon: "🎯",
        title: "Topic Starter",
        description: "Created 50+ topics",
    },
    {
        icon: "🔥",
        title: "Active Member",
        description: "Daily activity streak",
    },
]

const postsData = [{
        id: 1,
        title: "Welcome to the new Liquid Glass Forum!",
        excerpt: "We're excited to launch our new forum platform with a beautiful liquid glass design...",
        category: "Announcements",
        replies: 24,
        views: 156,
        likes: 18,
        createdAt: "2024-01-15T10:30:00Z",
        type: "topic",
    },
    {
        id: 2,
        title: "Re: How to customize your profile settings",
        excerpt: "Great question! Here's a step-by-step guide on how to personalize your forum experience...",
        category: "General Discussion",
        replies: 0,
        views: 45,
        likes: 7,
        createdAt: "2024-01-14T16:45:00Z",
        type: "reply",
    },
    {
        id: 3,
        title: "Best practices for forum etiquette",
        excerpt: "Let's discuss the do's and don'ts of forum participation. How can we maintain a respectful...",
        category: "General Discussion",
        replies: 31,
        views: 203,
        likes: 15,
        createdAt: "2024-01-13T14:15:00Z",
        type: "topic",
    },
]

const timelineData = [{
        title: "Posted new topic",
        description: "Started discussion about 'Welcome to the new Liquid Glass Forum!'",
        time: "2 hours ago",
    },
    {
        title: "Received achievement",
        description: "Earned the 'Helpful Community Member' badge for outstanding contributions",
        time: "1 day ago",
    },
    {
        title: "Reply posted",
        description: "Provided detailed answer to technical support question",
        time: "2 days ago",
    },
    {
        title: "Profile updated",
        description: "Updated bio and profile information",
        time: "1 week ago",
    },
    {
        title: "Joined forum",
        description: "Welcome to the Liquid Glass Forum community!",
        time: "January 2023",
    },
]

document.addEventListener("DOMContentLoaded", () => {
    renderActivity()
    renderAchievements()
    renderPosts()
    renderTimeline()
    setupEventListeners()
})

function setupEventListeners() {

    const profileForm = document.getElementById("profileForm")
    if (profileForm) {
        profileForm.addEventListener("submit", handleProfileUpdate)
    }

    const postsFilter = document.getElementById("postsFilter")
    if (postsFilter) {
        postsFilter.addEventListener("change", handlePostsFilter)
    }
}

function switchTab(tabName) {

    document.querySelectorAll(".tab-btn").forEach((btn) => btn.classList.remove("active"))
    document.querySelectorAll(".tab-pane").forEach((pane) => pane.classList.remove("active"))

    document.querySelector(`[data-tab="${tabName}"]`).classList.add("active")
    document.getElementById(tabName).classList.add("active")
}

function renderActivity() {
    const activityList = document.getElementById("activityList")
    if (!activityList) return

    activityList.innerHTML = activityData
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
    `,
        )
        .join("")
}

function renderAchievements() {
    const achievementsList = document.getElementById("achievementsList")
    if (!achievementsList) return

    achievementsList.innerHTML = achievementsData
        .map(
            (achievement) => `
        <div class="achievement-item" style="margin-bottom: 20px;">
            <div class="achievement-icon">${achievement.icon}</div>
            <div class="achievement-title">${achievement.title}</div>
            <div class="achievement-description">${achievement.description}</div>
        </div>
    `,
        )
        .join("")
}

function renderPosts(filter = "all") {
    const postsList = document.getElementById("postsList")
    if (!postsList) return

    let filteredPosts = postsData
    if (filter === "topics") {
        filteredPosts = postsData.filter((post) => post.type === "topic")
    } else if (filter === "replies") {
        filteredPosts = postsData.filter((post) => post.type === "reply")
    }

    postsList.innerHTML = filteredPosts
        .map(
            (post) => `
        <div class="post-item">
            <div class="post-header">
                <div>
                    <div class="post-title">${post.title}</div>
                    <div class="post-meta">
                        <span>${post.category}</span>
                        <span>${formatDate(post.createdAt)}</span>
                        <span>${post.type === "topic" ? "Topic" : "Reply"}</span>
                    </div>
                </div>
            </div>
            <div class="post-excerpt">${post.excerpt}</div>
            <div class="post-stats">
                <span>${post.replies} replies</span>
                <span>${post.views} views</span>
                <span>${post.likes} likes</span>
            </div>
        </div>
    `,
        )
        .join("")
}

function renderTimeline() {
    const activityTimeline = document.getElementById("activityTimeline")
    if (!activityTimeline) return

    activityTimeline.innerHTML = timelineData
        .map(
            (item) => `
        <div class="timeline-item">
            <div class="timeline-content">
                <div class="timeline-header">
                    <div class="timeline-title">${item.title}</div>
                    <div class="timeline-time">${item.time}</div>
                </div>
                <div class="timeline-description">${item.description}</div>
            </div>
        </div>
    `,
        )
        .join("")
}

function getActivityIcon(type) {
    const icons = {
        post: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
        like: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        reply: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
        achievement: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>',
    }
    return icons[type] || icons.post
}

function formatDate(dateString) {
    const date = new Date(dateString)
    const now = new Date()
    const diffInHours = Math.floor((now - date) / (1000 * 60 * 60))

    if (diffInHours < 1) {
        return "Just now"
    } else if (diffInHours < 24) {
        return `${diffInHours}h ago`
    } else if (diffInHours < 168) {
        return `${Math.floor(diffInHours / 24)}d ago`
    } else {
        return date.toLocaleDateString()
    }
}

function handlePostsFilter(event) {
    const filter = event.target.value
    renderPosts(filter)
}

function handleProfileUpdate(event) {
    event.preventDefault()

    const formData = new FormData(event.target)
    const displayName = document.getElementById("displayName").value
    const jobTitle = document.getElementById("jobTitle").value
    const userBio = document.getElementById("userBio").value

    document.getElementById("profileName").textContent = displayName
    document.getElementById("profileTitle").textContent = jobTitle
    document.getElementById("profileBio").textContent = userBio

    showNotification("Profile updated successfully!", "success")
}

function toggleEditMode() {

    switchTab("settings")
}

function editAvatar() {
    const input = document.createElement("input")
    input.type = "file"
    input.accept = "image/*"

    input.onchange = async (event) => {
        const file = event.target.files[0]
        if (!file) return

        const reader = new FileReader()
        reader.onload = (e) => {
            document.getElementById("profileAvatar").src = e.target.result
        }
        reader.readAsDataURL(file)

        const formData = new FormData()
        formData.append("avatar", file)

        const CSRF_TOKEN = window.CSRF_TOKEN || ""

        try {
            const res = await fetch("/api/uploadAvatar", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": CSRF_TOKEN
                },
                credentials: "same-origin"
            })
            const data = await res.json()

            if (data.data.url) {
                document.getElementById("profileAvatar").src = data.data.url
                showNotification(data.message, "success")
            } else {
                showNotification(data.message || "Upload failed", "error")
            }
        } catch (err) {
            console.error(err)
            showNotification("An error occurred while uploading", "error")
        }
    }

    input.click()
}

function editCover() {
    showNotification("Cover photo editing coming soon!", "info")
}

function changePassword() {
    showNotification("Password change functionality coming soon!", "info")
}

function downloadData() {
    fetch("/api/exportUserData", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": window.CSRF_TOKEN || ""
            },
            credentials: "same-origin"
        })
        .then((res) => {
            if (res.status === 200) {
                return res.blob()
            } else {
                throw new Error("Failed to export data")
            }
        })
        .then((blob) => {
            const url = window.URL.createObjectURL(blob)
            const a = document.createElement("a")
            a.href = url
            a.download = "user_data.zip"
            document.body.appendChild(a)
            a.click()
            a.remove()
            window.URL.revokeObjectURL(url)
            showNotification("Veri dışa aktarma işlemi başlatıldı. İndirilenler klasörünüzü kontrol edin.", "success")
        })
        .catch((err) => {
            console.error(err)
            showNotification("Veri dışa aktarırken bir hata oluştu", "error")
        })
}

function deleteAccount() {
    if (showConfirm("Are you sure you want to delete your account? This action cannot be undone.")) {
        showNotification("Account deletion request submitted. Please check your email.", "info")
    }
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
            document.body.removeChild(notification)
        }, 300)
    }, 3000)
}

function showConfirm(message, type = "info") {
    return new Promise((resolve) => {

        const overlay = document.createElement("div");
        overlay.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: 999;
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    `;
        document.body.appendChild(overlay);
        requestAnimationFrame(() => overlay.style.opacity = 1);

        const dialog = document.createElement("div");
        dialog.style.cssText = `
      background: white;
      padding: 1.5rem 2rem;
      border-radius: 0.5rem;
      text-align: center;
      min-width: 300px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      transform: translateY(-30px);
      opacity: 0;
      transition: transform 0.3s ease, opacity 0.3s ease;
    `;
        overlay.appendChild(dialog);
        requestAnimationFrame(() => {
            dialog.style.transform = "translateY(0)";
            dialog.style.opacity = 1;
        });

        const msg = document.createElement("p");
        msg.textContent = message;
        msg.style.marginBottom = "1rem";
        msg.style.color = type === "success" ? "#10b981" : type === "error" ? "#ef4444" : "#3b82f6";
        dialog.appendChild(msg);

        const btnContainer = document.createElement("div");
        btnContainer.style.cssText = "display: flex; justify-content: center; gap: 1rem;";

        const okBtn = document.createElement("button");
        okBtn.textContent = "Onayla";
        okBtn.style.cssText = `
      background: #10b981;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.25rem;
      cursor: pointer;
    `;

        const cancelBtn = document.createElement("button");
        cancelBtn.textContent = "İptal";
        cancelBtn.style.cssText = `
      background: #ef4444;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.25rem;
      cursor: pointer;
    `;

        btnContainer.appendChild(okBtn);
        btnContainer.appendChild(cancelBtn);
        dialog.appendChild(btnContainer);

        function closeOverlay(result) {
            dialog.style.transform = "translateY(-30px)";
            dialog.style.opacity = 0;
            overlay.style.opacity = 0;
            setTimeout(() => {
                document.body.removeChild(overlay);
                resolve(result);
            }, 300);
        }

        okBtn.addEventListener("click", () => {

            fetch('/api/deleteAccount', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => {
                    if (res.ok) {
                        showNotification("Hesap silme işlemi başarılı.", "success");
                        setInterval(() => {
                            window.location.href = "/logout";
                        }, 1000);
                        closeOverlay(true);
                    } else {
                        showNotification("Hesap silme işlemi başarısız.", "error");
                        closeOverlay(false);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification("Hata oluştu!", "error");
                    closeOverlay(false);
                });
        });

        cancelBtn.addEventListener("click", () => closeOverlay(false));
    });
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
`
document.head.appendChild(style)