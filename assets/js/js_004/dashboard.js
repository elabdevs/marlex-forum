document.addEventListener('DOMContentLoaded', () => {
    const sendButton = document.getElementById('sendButton');
    const messageInput = document.getElementById('message-input');
    const csrfToken = document.getElementById('csrfToken').value;
    const chatMessages = document.getElementById('chat-messages');
    const popoverTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="popover"]'));
    let csrfInput = document.getElementById("csrfToken");
    fetch("/api/generateCSRFToken", {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                csrfInput.value = data.data.CSRFToken;
                console.log("Yeni CSRF token alındı:", data.data.CSRFToken);
            }
        })
        .catch(err => console.log("CSRF token alınamadı:", err));
    popoverTriggerList.forEach(trigger => {
        new bootstrap.Popover(trigger, {
            trigger: 'manual',
            container: 'body',
            html: true,
            content: 'Loading...'
        });
    });
    function fetchUserProfile(userId, popover) {
        fetch(`/api/user/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const {
                        username,
                        userRole,
                        profile_picture,
                        userPoints,
                        bio
                    } = data.data;
                    popover.setContent(`
                        <div class="popover-header">
                            <strong>${username}</strong>
                            <button type='button' class='btn-close' aria-label='Close'></button>
                        </div>
                        <div class="popover-body">
                            <img src="${profile_picture || 'https://via.placeholder.com/150'}" alt="${username}'s profile picture">
                            <p>Rol: ${userRole}</p>
                            <p>Puan: ${userPoints}</p>
                            <p>${bio}</p>
                        </div>
                    `);
                    popover.show();
                } else {
                    popover.setContent('<div class="popover-body">Profil bilgileri alınamadı.</div>');
                }
            })
            .catch(() => {
                popover.setContent('<div class="popover-body">Profil bilgileri alınamadı.</div>');
            });
    }
    popoverTriggerList.forEach(popoverTriggerEl => {
        popoverTriggerEl.addEventListener('click', (event) => {
            const userId = event.currentTarget.id;
            const popover = bootstrap.Popover.getInstance(popoverTriggerEl);
            if (popover) {
                popover.hide();
                fetchUserProfile(userId, popover);
            } else {
                const newPopover = new bootstrap.Popover(popoverTriggerEl, {
                    trigger: 'manual',
                    container: 'body',
                    html: true,
                    content: 'Loading...'
                });
                fetchUserProfile(userId, newPopover);
            }
        });
    });
    document.addEventListener('click', (event) => {
        if (event.target && event.target.matches('.popover .btn-close')) {
            const popoverInstance = bootstrap.Popover.getInstance(event.target.closest('.popover'));
            if (popoverInstance) {
                popoverInstance.hide();
            }
        }
    });
    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;
        const formData = new FormData();
        formData.append("message", message);
        formData.append("csrfToken", csrfToken);
        fetch("/api/sendMessage", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status == true) {
                    const msgDiv = document.createElement("div");
                    msgDiv.classList.add("chat-message");
                    msgDiv.innerHTML = `
             <div class="chatUsername">${data.data?.username || "Ben"}:</div>
             <div class="chatMessage">${data.message || message}</div>
         `;
                    chatMessages.appendChild(msgDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    messageInput.value = "";
                } else {
                    alert(data.error || "Mesaj gönderilemedi.");
                }
            })
            .catch((e) => {
                alert("Sunucu hatası: mesaj gönderilemedi." + e);
                console.log(e);
            });
    }
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});