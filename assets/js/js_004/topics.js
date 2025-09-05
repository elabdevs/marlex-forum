let topicsData = []
let currentPage = 1
const topicsPerPage = 8
let filteredTopics = []


const topicsContainer = document.getElementById("topicsContainer")
const searchInput = document.getElementById("searchInput")
const categoryFilter = window.categoryName;
const sortFilter = document.getElementById("sortFilter")
const topicCount = document.getElementById("topicCount")
const currentPageSpan = document.getElementById("currentPage")
const totalPagesSpan = document.getElementById("totalPages")
const prevBtn = document.getElementById("prevBtn")
const nextBtn = document.getElementById("nextBtn")


document.addEventListener("DOMContentLoaded", () => {
  fetchTopics(categoryFilter || "general")
  setupEventListeners()
})

function setupEventListeners() {
  searchInput.addEventListener("input", handleSearch)
  sortFilter.addEventListener("change", handleSort)
  prevBtn.addEventListener("click", () => changePage(-1))
  nextBtn.addEventListener("click", () => changePage(1))
}

function handleCategoryChange() {
  fetchTopics(categoryFilter || "general")
}

function fetchTopics(category) {
  topicsContainer.innerHTML = `<div style="padding:2rem;text-align:center;">Loading...</div>`
  fetch(`/api/getCategoryTopics/${category}`)
    .then((res) => res.json())
    .then((json) => {
      if (json.status && Array.isArray(json.data)) {
        topicsData = json.data.map((item) => ({
          id: item.id,
          title: item.title,
          excerpt: item.excerpt,
          author: item.author,
          category: category,
          replies: item.replies,
          views: item.views,
          likes: item.likes,
          slug: item.slug,
          createdAt: item.created_at,
          lastActivity: item.updated_at,
          isPinned: !!item.is_pinned,
        }))
        filteredTopics = [...topicsData]
        currentPage = 1
        handleSort()
      } else {
        topicsContainer.innerHTML = `<div style="padding:2rem;text-align:center;">Konu bulunamadı.</div>`
        filteredTopics = []
        updatePagination()
        updateTopicCount()
      }
    })
    .catch(() => {
      topicsContainer.innerHTML = `<div style="padding:2rem;text-align:center;color:red;">Konular yüklenirken bir sorun oluştu.</div>`
      filteredTopics = []
      updatePagination()
      updateTopicCount()
    })
}

function handleSearch() {
  const searchTerm = searchInput.value.toLowerCase()
  filteredTopics = topicsData.filter(
    (topic) =>
      topic.title.toLowerCase().includes(searchTerm) ||
      topic.excerpt.toLowerCase().includes(searchTerm) ||
      topic.author.toLowerCase().includes(searchTerm),
  )
  applyFiltersAndSort()
}

function handleSort() {
  const sortBy = sortFilter.value
  filteredTopics.sort((a, b) => {
    switch (sortBy) {
      case "latest":
        return new Date(b.lastActivity) - new Date(a.lastActivity)
      case "newest":
        return new Date(b.createdAt) - new Date(a.createdAt)
      case "oldest":
        return new Date(a.createdAt) - new Date(b.createdAt)
      case "popular":
        return b.replies + b.likes - (a.replies + a.likes)
      default:
        return 0
    }
  })
  renderTopics()
}

function applyFiltersAndSort() {
  currentPage = 1
  handleSort()
}

function renderTopics() {
  const startIndex = (currentPage - 1) * topicsPerPage
  const endIndex = startIndex + topicsPerPage
  const topicsToShow = filteredTopics.slice(startIndex, endIndex)

  if (topicsToShow.length === 0) {
    topicsContainer.innerHTML = `<div style="padding:2rem;text-align:center;">No topics found.</div>`
  } else {
    topicsContainer.innerHTML = topicsToShow
      .map(
        (topic) => `
        <div class="topic-item liquid-glass liquid-glass-card" onclick="viewTopic('${topic.slug}')">
        <a href="/topics/${topic.slug}" style="display:none;"></a>
            <div class="topic-header">
                <div class="topic-avatar">
                    ${topic.author.charAt(0).toUpperCase()}
                </div>
                <div class="topic-content">
                    <h3 class="topic-title">
                        ${topic.isPinned ? "📌 " : ""}${topic.title}
                    </h3>
                    <div class="topic-meta">
                        <span>by ${topic.author}</span>
                        <span>${formatDate(topic.createdAt)}</span>
                    </div>
                    <p class="topic-excerpt">${topic.excerpt}</p>
                </div>
            </div>
            <div class="topic-stats">
                <div class="topic-engagement">
                    <div class="engagement-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>${topic.replies}</span>
                    </div>
                    <div class="engagement-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>${topic.views}</span>
                    </div>
                    <div class="engagement-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span>${topic.likes}</span>
                    </div>
                </div>
                <div class="topic-last-activity">
                    <div>Son hareket</div>
                    <div>${formatDate(topic.lastActivity)}</div>
                </div>
            </div>
        </div>
      `,
      )
      .join("")
  }

  updatePagination()
  updateTopicCount()
}

function updatePagination() {
  const totalPages = Math.ceil(filteredTopics.length / topicsPerPage)
  currentPageSpan.textContent = currentPage
  totalPagesSpan.textContent = totalPages
  prevBtn.disabled = currentPage === 1
  nextBtn.disabled = currentPage === totalPages || totalPages === 0
}

function updateTopicCount() {
  topicCount.textContent = filteredTopics.length
}

function changePage(direction) {
  const totalPages = Math.ceil(filteredTopics.length / topicsPerPage)
  const newPage = currentPage + direction
  if (newPage >= 1 && newPage <= totalPages) {
    currentPage = newPage
    renderTopics()
    window.scrollTo({ top: 0, behavior: "smooth" })
  }
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


function viewTopic(topicSlug) {
  window.location.href = `/topics/${topicSlug}`
}
