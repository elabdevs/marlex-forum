if (window.location.pathname !== '/chat') {

    const ws = new WebSocket("ws://192.168.1.7:8080");

    ws.onopen = () => {
        console.log("Websocket sunucusuna bağlanıldı");
        ws.send(JSON.stringify({
            type: "identify",
            userId: currentUserId
        }));
    };

    ws.onmessage = (event) => {
        try {
            const data = JSON.parse(event.data);
            if (data.type === "admin_broadcast") {
                showNotification(data.text, "info");
            }
            if (data.type === "message") {
                showNotification("Yeni Mesaj: " + data.text, "info");
            }
        } catch (err) {
            console.error("WS mesaj parse hatası:", err);
        }
    };

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
}