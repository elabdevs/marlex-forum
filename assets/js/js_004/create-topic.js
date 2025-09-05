document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("createTopicForm")
    const titleInput = document.getElementById("title")
    const descriptionInput = document.getElementById("description")
    const contentTextarea = document.getElementById("content")
    const categorySelect = document.getElementById("category")
    const tagsInput = document.getElementById("tags")
    const previewToggle = document.getElementById("previewToggle")
    const previewContent = document.getElementById("previewContent")
    const saveDraftBtn = document.getElementById("saveDraft")

    const titleCount = document.getElementById("titleCount")
    const descriptionCount = document.getElementById("descriptionCount")
    const contentCount = document.getElementById("contentCount")

    const previewTitle = document.getElementById("previewTitle")
    const previewCategory = document.getElementById("previewCategory")
    const previewTags = document.getElementById("previewTags")
    const previewText = document.getElementById("previewText")

    const toolbarBtns = document.querySelectorAll(".toolbar-btn")

    titleInput.addEventListener("input", function() {
        titleCount.textContent = this.value.length
        updatePreview()
    })

    descriptionInput.addEventListener("input", function() {
        descriptionCount.textContent = this.value.length
        updatePreview()
    })

    contentTextarea.addEventListener("input", function() {
        contentCount.textContent = this.value.length
        updatePreview()
    })

    categorySelect.addEventListener("change", updatePreview)
    tagsInput.addEventListener("input", updatePreview)

    let previewVisible = false
    previewToggle.addEventListener("click", () => {
        previewVisible = !previewVisible
        if (previewVisible) {
            previewContent.style.display = "block"
            previewToggle.textContent = "Önizlemeyi Gizle"
            updatePreview()
        } else {
            previewContent.style.display = "none"
            previewToggle.textContent = "Önizlemeyi Göster"
        }
    })

    toolbarBtns.forEach((btn) => {
        btn.addEventListener("click", function() {
            const action = this.dataset.action
            handleToolbarAction(action)
        })
    })

    form.addEventListener("submit", (e) => {
        e.preventDefault()

        const formData = new FormData()
        formData.append("category", categorySelect.value)
        formData.append("title", titleInput.value)
        formData.append("description", descriptionInput.value)
        formData.append("tags", tagsInput.value)
        formData.append("content", contentTextarea.value)

        if (
            !categorySelect.value ||
            !titleInput.value ||
            !descriptionInput.value ||
            !contentTextarea.value
        ) {
            alert("Lütfen tüm gerekli alanları doldurun.")
            return
        }

        fetch("/api/createTopic", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === true) {
                    alert("Konu başarıyla oluşturuldu!")
                    window.location.href = "/topics/" + data.data.slug
                } else {
                    alert(data.error || "Konu oluşturulamadı.")
                }
            })
            .catch((e) => {
                alert("Sunucu hatası: konu oluşturulamadı." + e)
                console.log(e)
            })
    })

    saveDraftBtn.addEventListener("click", () => {
        const draftData = {
            category: categorySelect.value,
            title: titleInput.value,
            description: descriptionInput.value,
            tags: tagsInput.value,
            content: contentTextarea.value,
            timestamp: new Date().toISOString(),
        }

        localStorage.setItem("forumDraft", JSON.stringify(draftData))
        alert("Taslak başarıyla kaydedildi!")
    })

    const savedDraft = localStorage.getItem("forumDraft")
    if (savedDraft) {
        const draft = JSON.parse(savedDraft)
        if (confirm("Kayıtlı bir taslağınız var. Yüklemek ister misiniz?")) {
            categorySelect.value = draft.category || ""
            titleInput.value = draft.title || ""
            descriptionInput.value = draft.description || ""
            tagsInput.value = draft.tags || ""
            contentTextarea.value = draft.content || ""

            titleCount.textContent = titleInput.value.length
            descriptionCount.textContent = descriptionInput.value.length
            contentCount.textContent = contentTextarea.value.length
        }
    }

    function updatePreview() {
        if (!previewVisible) return

        previewTitle.textContent = titleInput.value || "Başlığınız burada görünecek"

        const selectedCategory = categorySelect.options[categorySelect.selectedIndex]
        previewCategory.textContent = selectedCategory ? selectedCategory.text : "Kategori"

        const tags = tagsInput.value
            .split(",")
            .map((tag) => tag.trim())
            .filter((tag) => tag)
        previewTags.textContent = tags.length > 0 ? `Etiketler: ${tags.join(", ")}` : ""

        let content = contentTextarea.value || "İçeriğiniz burada görünecek"
        content = content
            .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
            .replace(/\*(.*?)\*/g, "<em>$1</em>")
            .replace(/`(.*?)`/g, "<code>$1</code>")
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')

        previewText.innerHTML = content
    }

    function handleToolbarAction(action) {
        const start = contentTextarea.selectionStart
        const end = contentTextarea.selectionEnd
        const selectedText = contentTextarea.value.substring(start, end)
        let replacement = ""

        switch (action) {
            case "bold":
                replacement = `**${selectedText || "kalın metin"}**`
                break
            case "italic":
                replacement = `*${selectedText || "italik metin"}*`
                break
            case "code":
                replacement = `\`${selectedText || "kod"}\``
                break
            case "link":
                const url = prompt("URL girin:")
                if (url) {
                    replacement = `[${selectedText || "bağlantı metni"}](${url})`
                }
                break
        }

        if (replacement) {
            const newValue = contentTextarea.value.substring(0, start) + replacement + contentTextarea.value.substring(end)
            contentTextarea.value = newValue
            contentTextarea.focus()

            contentCount.textContent = newValue.length
            updatePreview()
        }
    }
})