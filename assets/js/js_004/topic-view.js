function renderMarkdown(text) {
    text = text.replace(/`([^`]+)`/g, '<code>$1</code>')
    text = text.replace(/\*\*([^\*]+)\*\*/g, '<strong>$1</strong>')
    text = text.replace(/\*([^\*]+)\*/g, '<em>$1</em>')
    text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
    return text.replace(/\n/g, "<br>")
}

document.addEventListener("DOMContentLoaded", () => {
    const replyForm = document.getElementById("replyForm")
    const loadMoreBtn = document.querySelector(".load-more-btn")
    const actionBtns = document.querySelectorAll(".action-btn")
    const replyBtns = document.querySelectorAll(".reply-btn")
    const actionButtons = document.querySelectorAll(".action-button")
    const topicId = typeof window.topicId !== "undefined" ? window.topicId : null

    const topicContentDiv = document.getElementById("post-content")
    if (topicContentDiv) {
        const rawContent = topicContentDiv.textContent
        topicContentDiv.innerHTML = renderMarkdown(rawContent)
    }

    if (replyForm) {
        replyForm.addEventListener("submit", (e) => {
            e.preventDefault()
            const textarea = replyForm.querySelector("textarea")
            const content = textarea.value.trim()

            if (!content) {
                alert("Please enter your reply content.")
                return
            }

            console.log("Submitting reply:", content)
            textarea.value = ""

            fetch('/api/replyContent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    content: content,
                    topicId: topicId
                })
            });
        })
    }

    actionBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault()
            const action = btn.title.toLowerCase()

            if (action.includes("beğen")) {
                const countSpan = btn.querySelector("span")
                const currentCount = Number.parseInt(countSpan.textContent)
                if (countSpan) {

                    let formData = new URLSearchParams();
                    formData.append('topic_id', topicId);

                    fetch('/api/likeTopic', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: formData.toString()
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == true) {
                                if (data.message == "Konu Beğenildi") {
                                    countSpan.textContent = currentCount + 1
                                    btn.classList.add("liked")
                                    btn.style.color = "var(--secondary)"
                                } else {
                                    countSpan.textContent = currentCount - 1
                                    btn.classList.remove("liked")
                                    btn.style.color = ""
                                }
                            } else {
                                Swal.fire({
                                    title: 'Hata',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Giriş Yap',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "/login";
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Hata',
                                text: "Bir hata oluştu",
                                icon: 'error',
                            });
                        });
                }
            } else if (action.includes("favorilere ekle")) {
                const countSpan = btn.querySelector("span")
                const currentCount = Number.parseInt(countSpan.textContent)
                if (countSpan) {

                    let formData = new URLSearchParams();
                    formData.append('topic_id', topicId);

                    fetch('/api/favoriteTopic', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: formData.toString()
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == true) {
                                if (data.message == "Konu Favorilere Eklendi") {
                                    countSpan.textContent = currentCount + 1
                                    btn.classList.add("liked")
                                    btn.style.color = "var(--secondary)"
                                } else {
                                    countSpan.textContent = currentCount - 1
                                    btn.classList.remove("favorited")
                                    btn.style.color = ""
                                }
                            } else {
                                Swal.fire({
                                    title: 'Hata',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Giriş Yap',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "/login";
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Hata', 'Bir hata oluştu.', 'error');
                        });
                }
            } else if (action.includes("konuyu paylaş")) {
                let isSharing = false;

                const shareBtn = document.querySelector("#shareBtn");

                shareBtn.addEventListener("click", async () => {
                    if (isSharing) return;

                    if (navigator.share) {
                        try {
                            isSharing = true;
                            await navigator.share({
                                title: document.title,
                                url: window.location.href,
                            });
                        } catch (err) {
                            console.error("Paylaşım iptal edildi veya hata:", err);
                        } finally {
                            isSharing = false;
                        }
                    } else {
                        navigator.clipboard.writeText(window.location.href);
                        alert("Link panoya kopyalandı!");
                    }
                });

            } else if (action.includes("raporla")) {

            }
        })
    })

    replyBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault()
            const post = btn.closest(".post")
            const authorName = post.querySelector(".author-name").textContent
            const textarea = document.querySelector("#replyForm textarea")

            if (textarea) {
                textarea.focus()
                textarea.value = `@${authorName} `
                textarea.setSelectionRange(textarea.value.length, textarea.value.length)
            }
        })
    })

    actionButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault()
            const action = btn.title.toLowerCase()

            if (action.includes("arşive at") || action.includes("arşivden kaldır")) {

                let formData = new URLSearchParams();
                formData.append('topic_id', topicId);

                fetch('/api/archiveTopic', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: formData.toString()
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == true) {
                            btn.title = data.message.includes("Arşivlendi") ? "Arşivden Kaldır" : "Arşive At";
                            btn.innerHTML = data.message.includes("Arşivlendi") ?
                                `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" 
							         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							           <path d="M4 4h5l2 3h9a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>
							           <line x1="12" y1="10" x2="12" y2="16"/>
							           <polyline points="9 13 12 16 15 13"/>
							       </svg> Arşivden Kaldır` :
                                `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" 
							         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							           <path d="M4 4h5l2 3h9a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>
							           <line x1="12" y1="8" x2="12" y2="14"/>
							           <polyline points="9 11 12 8 15 11"/>
							       </svg> Arşive At`;
                            Swal.fire({
                                title: 'Başarılı',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'Tamam',
                            })
                        } else {
                            Swal.fire({
                                title: 'Hata',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'Giriş Yap',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/login";
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Hata', 'Bir hata oluştu.', 'error');
                    });
            } else if (action.includes("konuyu sil")) {

                let formData = new URLSearchParams();
                formData.append('topic_id', topicId);

                fetch('/api/removeTopic', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: formData.toString()
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == true) {
                            if (data.message == "Konu Silindi.") {
                                btn.innerHTML = `
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m5 0V4a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
								Konuyu Geri Yükle`;

                                Swal.fire({
                                    title: 'Başarılı',
                                    text: data.message,
                                    icon: 'success'
                                })
                                setInterval(() => {
                                    window.location.href = "/";
                                }, 2000);
                            } else {
                                btn.innerHTML = `
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m5 0V4a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
								Konuyu Sil`;
                                Swal.fire({
                                    title: 'Başarılı',
                                    text: data.message,
                                    icon: 'success'
                                })
                            }
                        } else {
                            Swal.fire({
                                title: 'Hata',
                                text: data.message,
                                icon: 'error'
                            })
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Hata', 'Bir hata oluştu.', 'error');
                    });
            } else if (action.includes("konuyu takip et")) {

                let formData = new URLSearchParams();
                formData.append('topic_id', topicId);

                fetch('/api/favoriteTopic', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: formData.toString()
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == true) {
                            if (data.message == "Konu Favorilere Eklendi") {
                                btn.innerHTML = `
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                	<polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                </svg>
                                Takibi Bırak`;
                                Swal.fire({
                                    title: 'Başarılı',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Tamam',
                                })
                                let favoriteButton = document.getElementById("favoriteButton")
                                let favoriteCountSpan = document.getElementById("favoriteCountSpan")
                                let favoriteCurrentCount = Number.parseInt(favoriteCountSpan.textContent)
                                favoriteCountSpan.textContent = favoriteCurrentCount + 1
                                favoriteButton.classList.add("liked")
                                favoriteButton.style.color = "var(--secondary)"
                            } else {
                                btn.innerHTML = `
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                	<polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                </svg>
                                Konuyu Takip Et`;
                                Swal.fire({
                                    title: 'Başarılı',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Tamam',
                                })
                                let favoriteButton = document.getElementById("favoriteButton")
                                let favoriteCountSpan = document.getElementById("favoriteCountSpan")
                                let favoriteCurrentCount = Number.parseInt(favoriteCountSpan.textContent)
                                favoriteCountSpan.textContent = favoriteCurrentCount - 1
                                favoriteButton.classList.remove("liked")
                                favoriteButton.style.color = ""
                            }
                        } else {
                            Swal.fire({
                                title: 'Hata',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'Giriş Yap',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/login";
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Hata', 'Bir hata oluştu.', 'error');
                    });
            } else if (action.includes("konuyu beğen")) {

                let formData = new URLSearchParams();
                formData.append('topic_id', topicId);

                fetch('/api/likeTopic', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: formData.toString()
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == true) {
                            if (data.message == "Konu Beğenildi") {
                                btn.innerHTML = `
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7z"/>
                                </svg>
								Beğeniyi Geri Al`;
                                Swal.fire({
                                    title: 'Başarılı',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Tamam',
                                })
                                let likeButton = document.getElementById("likeButton")
                                let likeCountSpan = document.getElementById("likeCountSpan")
                                let likeCurrentCount = Number.parseInt(likeCountSpan.textContent)
                                likeCountSpan.textContent = likeCurrentCount + 1
                                likeButton.classList.add("liked")
                                likeButton.style.color = "var(--secondary)"
                            } else {
                                btn.innerHTML = `
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7z"/>
                                </svg>
                                Konuyu Beğen`;
                                Swal.fire({
                                    title: 'Başarılı',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'Tamam',
                                })
                                let likeButton = document.getElementById("likeButton")
                                let likeCountSpan = document.getElementById("likeCountSpan")
                                let likeCurrentCount = Number.parseInt(likeCountSpan.textContent)
                                likeCountSpan.textContent = likeCurrentCount - 1
                                likeButton.classList.remove("liked")
                                likeButton.style.color = ""
                            }
                        } else {
                            Swal.fire({
                                title: 'Hata',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'Giriş Yap',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/login";
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Hata', 'Bir hata oluştu.', 'error');
                    });
            } else if (action.includes("konuyu paylaş")) {
                if (navigator.share) {
                    navigator.share({
                        title: document.title,
                        url: window.location.href,
                    })
                } else {
                    navigator.clipboard.writeText(window.location.href)
                    alert("Link panoya kopyalandı!")
                }
            }
        })
    })

    const textarea = document.querySelector("#replyForm textarea")
    if (textarea) {
        textarea.addEventListener("input", function() {
            this.style.height = "auto"
            this.style.height = this.scrollHeight + "px"
        })
    }

    replyBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            setTimeout(() => {
                document.querySelector(".reply-form-container").scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                })
            }, 100)
        })
    })

    const relatedTopicsContainer = document.querySelector(".related-topics")

    const participantsList = document.querySelector('.participants-list')
    if (participantsList && topicId) {
        function renderParticipants(users) {
            if (!users.length) {
                participantsList.innerHTML = `<div style="color:var(--muted-foreground);padding:1rem;">Aktif kullanıcı yok.</div>`;
                return;
            }
            participantsList.innerHTML = users.map(user => `
                <div class="participant">
                    <div class="participant-avatar">
                        <img src="${user.profile_picture}" alt="User" />
                        <div class="status-dot status-online"></div>
                    </div>
                    <div class="participant-info">
                        <span class="participant-name">${user.username ? user.username : 'Ziyaretçi'}</span>
                        <span class="participant-posts">${user.last_active ? 'Aktif' : ''}</span>
                    </div>
                </div>
            `).join('');
        }

        function fetchParticipants() {
            fetch(`/api/getTopicOnlines/${topicId}`)
                .then(res => res.json())
                .then(json => {
                    if (json.status && Array.isArray(json.data)) {
                        renderParticipants(json.data);
                    } else {
                        renderParticipants([]);
                    }
                })
                .catch(() => {
                    participantsList.innerHTML = `<div style="color:red;padding:1rem;">Aktif kullanıcılar yüklenemedi.</div>`;
                });
        }

        fetchParticipants();
        setInterval(fetchParticipants, 30000);
    }

    function renderRelatedTopics(topics) {
        relatedTopicsContainer.innerHTML = ""
        if (!topics || topics.length === 0) {
            relatedTopicsContainer.innerHTML = "<div>Benzer konu bulunamadı.</div>"
            return
        }
        topics.forEach(topic => {
            const topicEl = document.createElement("a")
            topicEl.className = "related-topic"
            topicEl.href = `/topics/${topic.slug}`
            topicEl.innerHTML = `
        <h4 class="related-title">${topic.title}</h4>
        <div class="related-meta">
            <span>${topic.post_count} yanıt</span>
            <span>•</span>
            <span>${topic.created_at}</span>
        </div>
      `
            relatedTopicsContainer.appendChild(topicEl)
        })
    }

    if (relatedTopicsContainer && topicId) {
        fetch(`/api/getRelatedTopics/${topicId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status && Array.isArray(data.data)) {
                    renderRelatedTopics(data.data)
                } else {
                    relatedTopicsContainer.innerHTML = "<div>Benzer konu bulunamadı.</div>"
                }
            })
            .catch(() => {
                relatedTopicsContainer.innerHTML = "<div>Benzer konular yüklenemedi.</div>"
            })
    }

    const repliesSection = document.querySelector(".replies-section")
    let repliesList = document.querySelector(".replies-list")

    let repliesPage = 1
    const repliesPerPage = 2
    let totalReplies = 0
    let isLoadingReplies = false

    if (!repliesList && repliesSection) {
        repliesList = document.createElement("div")
        repliesList.className = "replies-list"
        repliesSection.insertBefore(repliesList, repliesSection.querySelector(".load-more-container"))
    }

    if (!loadMoreBtn && repliesSection) {
        const loadMoreContainer = document.createElement("div")
        loadMoreContainer.className = "load-more-container"
        loadMoreBtn = document.createElement("button")
        loadMoreBtn.className = "btn btn-outline load-more-btn"
        loadMoreBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6,9 12,15 18,9"/></svg> Daha Fazla Yanıt Yükle`
        loadMoreContainer.appendChild(loadMoreBtn)
        repliesSection.appendChild(loadMoreContainer)
    }

    function createExpandableContent(html, maxLength = 350) {

        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = html;
        const plainText = tempDiv.textContent || tempDiv.innerText || "";

        if (plainText.length <= maxLength) {
            return html;
        }

        const shortText = plainText.slice(0, maxLength) + "...";

        return `
        <div class="expandable-content">
            <div class="content-short">${shortText}</div>
            <div class="content-full" style="display:none">${html}</div>
            <button class="expand-btn btn btn-outline" type="button">Devamını Görüntüle</button>
        </div>
    `;
    }

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("expand-btn")) {
            const expandable = e.target.closest(".expandable-content");
            if (!expandable) return;
            const shortDiv = expandable.querySelector(".content-short");
            const fullDiv = expandable.querySelector(".content-full");
            if (shortDiv.style.display !== "none") {
                shortDiv.style.display = "none";
                fullDiv.style.display = "";
                e.target.textContent = "Daha Az Göster";
            } else {
                shortDiv.style.display = "";
                fullDiv.style.display = "none";
                e.target.textContent = "Devamını Görüntüle";
            }
        }
    });

    function renderReply(reply) {

        const btnId = `button_${reply.id}`;
        return `
            <div class="post reply liquid-glass liquid-glass-card">
                <div class="post-header">
                    <div class="post-author">
                        <div class="author-avatar">
                            <img src="${reply.profile_picture || '/placeholder.svg?height=40&width=40'}" alt="${reply.username || 'Kullanıcı'}" />
                            <div class="status-dot status-away"></div>
                        </div>
                        <div class="author-info">
                            <h4 class="author-name">${reply.username || 'Kullanıcı'}</h4>
                            <div class="author-meta">
                                <span class="author-role">${reply.role || ''}</span>
                                <span class="author-posts"></span>
                            </div>
                        </div>
                    </div>
                    <div class="post-actions">
                        <button class="action-btn" id="${btnId}" title="Yanıtı beğen" onclick="likePost(${reply.id})">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span>${reply.like_count || 0}</span>
                        </button>
                        <button class="action-btn reply-btn" title="Yanıtla" onclick="replyToPost(${reply.id})">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9,17 4,12 9,7"/>
                                <path d="M20,18v-2a4,4 0 0,0-4-4H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="post-content">${createExpandableContent(renderMarkdown(reply.content), 350)}</div>
                <div class="post-footer">
                    <div class="post-timestamp">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        ${formatDate(reply.created_at)}
                    </div>
                </div>
            </div>
        `
    }

    function checkRepliesLiked(replies) {
        replies.forEach(reply => {
            fetch(`/api/checkPostLiked/${reply.id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status && data.message === "Beğenildi") {
                        const btn = document.getElementById(`button_${reply.id}`);
                        if (btn) {
                            btn.classList.add("liked");
                            btn.style.color = "var(--secondary)";
                        }
                    }
                });
        });
    }

    function renderReplies(replies, append = false) {
        if (!repliesList) return
        if (!append) repliesList.innerHTML = ""
        replies.forEach(reply => {
            repliesList.insertAdjacentHTML("beforeend", renderReply(reply))
        })
        checkRepliesLiked(replies)
    }

    function fetchReplies(page = 1, append = false) {
        if (isLoadingReplies) return
        isLoadingReplies = true
        if (loadMoreBtn) loadMoreBtn.textContent = "Yükleniyor..."

        fetch(`/api/getPostsByTopic/${topicId}?page=${page}&per_page=${repliesPerPage}`)
            .then(res => res.json())
            .then(json => {
                const posts = json.data && Array.isArray(json.data.posts) ? json.data.posts : []
                totalReplies = json.data && typeof json.data.total === "number" ? json.data.total : 0

                if (json.status && posts.length > 0) {
                    renderReplies(posts, append)
                    const loadedReplies = repliesList ? repliesList.children.length : 0
                    if (loadMoreBtn) {
                        if (loadedReplies < totalReplies) {
                            loadMoreBtn.style.display = ""
                            loadMoreBtn.textContent = `Daha fazla yanıt yükle (${totalReplies - loadedReplies} kaldı)`
                        } else {
                            loadMoreBtn.style.display = "none"
                        }
                    }
                } else {
                    if (!append && repliesList) {
                        repliesList.innerHTML = "<div style='padding:1rem;color:var(--muted-foreground)'>Yanıt bulunamadı.</div>"
                    }
                    if (loadMoreBtn) loadMoreBtn.style.display = "none"
                }
                const repliesCountSpan = document.getElementById("repliesCount")
                if (repliesCountSpan) repliesCountSpan.textContent = totalReplies
            })
            .catch(() => {
                if (repliesList) repliesList.innerHTML = "<div style='padding:1rem;color:red'>Yanıtlar yüklenemedi.</div>"
                if (loadMoreBtn) loadMoreBtn.style.display = "none"
            })
            .finally(() => {
                isLoadingReplies = false
            })
    }

    if (repliesList && topicId) {
        fetchReplies(repliesPage)
    }

    if (loadMoreBtn && repliesList && topicId) {
        loadMoreBtn.addEventListener("click", () => {
            repliesPage++
            fetchReplies(repliesPage, true)
        })
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

        if (diffInMinutes < 1) {
            return "Az önce";
        } else if (diffInMinutes < 60) {
            return `${diffInMinutes} dakika önce`;
        } else if (diffInHours < 24) {
            return `${diffInHours} saat önce`;
        } else if (diffInDays < 7) {
            return `${diffInDays} gün önce`;
        } else if (diffInWeeks < 5) {
            return `${diffInWeeks} hafta önce`;
        } else if (diffInMonths < 12) {
            return `${diffInMonths} ay önce`;
        } else {
            return `${diffInYears} yıl önce`;
        }
    }

})

function likePost(postId) {
    let formData = new URLSearchParams();
    let btn = document.getElementById(`button_${postId}`);
    formData.append('post_id', postId);

    fetch('/api/likePost', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == true) {
                if (data.message == "Yanıt Beğenildi") {
                    btn.innerHTML = `
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <span>${data.data.like_count || 0}</span>`;
                    Swal.fire({
                        title: 'Başarılı',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Tamam',
                    })
                    btn.classList.add("liked")
                    btn.style.color = "var(--secondary)"
                } else {
                    btn.innerHTML = `
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <span>${data.data.like_count || 0}</span>`;
                    Swal.fire({
                        title: 'Başarılı',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Tamam',
                    })
                    btn.classList.remove("liked")
                    btn.style.color = ""
                }
            } else {
                Swal.fire({
                    title: 'Hata',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Giriş Yap',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/login";
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Hata', 'Bir hata oluştu.', 'error');
        });
}