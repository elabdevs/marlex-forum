function handleScroll() {
    const scrollY = window.scrollY
    const orbs = document.querySelectorAll(".liquid-orb")

    orbs.forEach((orb, index) => {
        const speed = 0.5 + index * 0.1
        const yPos = scrollY * speed
        orb.style.transform = `translateY(${yPos}px)`
    })
}

function fetchActiveUsers() {
    fetch("/api/lastActiveUsers/5")
        .then(res => res.json())
        .then(data => {
            const userList = document.getElementById("activeUsers");
            const userCountDiv = document.getElementById("activeUserCount");
            if (!userList) return;
            userList.innerHTML = "";

            if (data.status && Array.isArray(data.data) && data.data.length > 0) {
                if (userCountDiv) userCountDiv.textContent = data.data.length;

                data.data.forEach(user => {
                    let initials = user.displayName ?
                        user.displayName.split(" ").map(w => w[0]).join("").toUpperCase() :
                        (user.username ? user.username.slice(0, 2).toUpperCase() : "U");

                    let statusText = user.is_afk == 1 ? "Dışarıda" : (user.is_afk == 2 ? "Offline" : "Çevrimiçi");
                    let statusClass = user.is_afk == 1 ? "status-away" : (user.is_afk == 2 ? "status-offline" : "status-online");

                    let avatarHtml = user.profile_picture ?
                        `<img src="${user.profile_picture}" alt="avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">` :
                        initials;

                    const userDiv = document.createElement("div");
                    userDiv.className = "user-item";
                    userDiv.innerHTML = `
            <div class="user-avatar">
              ${avatarHtml}
              <div class="status-dot ${statusClass}"></div>
            </div>
            <div class="user-info">
              <h4>${user.displayName || user.username}</h4>
              <p>${statusText}</p>
            </div>
          `;
                    userList.appendChild(userDiv);
                });
            } else {
                if (userCountDiv) userCountDiv.textContent = "0";
                userList.innerHTML = `<div class="user-item"><p>Aktif kullanıcı yok.</p></div>`;
            }
        })
        .catch(() => {
            const userCountDiv = document.getElementById("activeUserCount");
            if (userCountDiv) userCountDiv.textContent = "0";
            if (userList) userList.innerHTML = `<div class="user-item"><p>Aktif kullanıcılar yüklenemedi.</p></div>`;
        });
}

function init() {

    const cards = document.querySelectorAll(".liquid-glass-card")
    cards.forEach((card) => {
        card.addEventListener("mouseenter", function() {
            this.style.transform = "translateY(-2px) scale(1.02)"
        })

        card.addEventListener("mouseleave", function() {
            this.style.transform = "translateY(0) scale(1)"
        })
    })

    if (window.location.pathname === "/") {
        fetchActiveUsers();

        setInterval(fetchActiveUsers, 5000);
    }
}
document.addEventListener("DOMContentLoaded", init)