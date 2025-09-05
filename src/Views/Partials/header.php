<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
	<div class="container">
		<div class="liquid-glass header-content">
			<div class="header-left">
				<div class="logo-icon">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
					</svg>
				</div>
				<h1 class="logo-text"><?= $siteTitle ?></h1>
			</div>
			<nav class="nav">
				<a href="/" class="nav-link">Anasayfa</a>
				<a href="/categories" class="nav-link">Kategoriler</a>
				<a href="/members" class="nav-link">Üyeler</a>
				<a href="/about" class="nav-link">Hakkımızda</a>
				<?php if(@$_SESSION['user_id']){
					if(@$_SESSION['is_admin'] == 1){ ?>
				<a href="/admin" class="nav-link">Admin Paneli</a>
				<a href="/profile" class="nav-link">Profil</a>
				<a href="/chat" class="nav-link">Direkt Mesajlar</a>
				<a href="/logout" class="nav-link">Çıkış Yap</a>
				<?php } else { ?>
				<a href="/profile" class="nav-link">Profil</a>
				<a href="/chat" class="nav-link">Direkt Mesajlar</a>
				<a href="/logout" class="nav-link">Çıkış Yap</a>
				<?php }} else { ?>
				<a href="/login" class="nav-link">Giriş Yap</a>
				<a href="/register" class="nav-link">Kayıt Ol</a>
				<?php } ?>
				<div id="dark-toggle">
					<input id="switch" type="checkbox" <?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'checked' : '' ?>>
					<div class="app">
						<div class="body">
							<div class="phone">
								<div class="menu"></div>
								<div class="content">
									<div class="circle">
										<div class="crescent"></div>
									</div>
									<label for="switch">
										<div class="toggle"></div>
										<div class="names">
											<p class="light"></p>
											<p class="dark"></p>
										</div>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</div>
</header>
<script src="/assets/js/javascript.php?file=wsConnection.js"></script>
<script>
	const currentUserId = "<?= isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : uniqid() ?>";

	const toggleBtn = document.getElementById("switch");
	const body = document.body;
	
	if (localStorage.getItem("theme") === "dark" || body.classList.contains("dark-mode")) {
	  body.classList.add("dark-mode");
	  toggleBtn.checked = true;
	} else {
	  body.classList.remove("dark-mode");
	  toggleBtn.checked = false;
	}
	
	toggleBtn.addEventListener("click", () => {
	  body.classList.toggle("dark-mode");
	  if (body.classList.contains("dark-mode")) {
	    localStorage.setItem("theme", "dark");
	    document.cookie = "theme=dark;path=/";
	    toggleBtn.checked = true;
	  } else {
	    localStorage.setItem("theme", "light");
	    document.cookie = "theme=light;path=/";
	    toggleBtn.checked = false;
	  }
	});
	document.addEventListener("visibilitychange", function() {
	  if (document.hidden) {
	      console.log("Kullanıcı sekmeden çıktı veya Chrome arka plana geçti");
	      fetch("/api/saveAfk");
	  } else {
	      console.log("Kullanıcı sekmeye geri döndü");
	      fetch("/api/removeAfk");
	  }
	});
</script>

<script>
function activateCSS(lite) {
    const mainCSS = document.getElementById('mainCSS');
    const liteCSS = document.getElementById('liteCSS');

    if(lite) {
        if(mainCSS) mainCSS.media = "none";
        if(liteCSS) liteCSS.media = "all";
        localStorage.setItem('liteMode', 'true');
        console.log('Lite CSS aktif edildi');
    } else {
        if(mainCSS) mainCSS.media = "all";
        if(liteCSS) liteCSS.media = "none";
        localStorage.setItem('liteMode', 'false');
        console.log('Normal CSS aktif edildi');
    }
}

function showNotification(message, type = "info") {
    let notif = document.createElement('div');
    notif.textContent = message;
    notif.style.position = 'fixed';
    notif.style.bottom = '20px';
    notif.style.right = '20px';
    notif.style.padding = '12px 20px';
    notif.style.background = type === "warning" ? "#ffc107" : (type === "success" ? "#4caf50" : "#2196f3");
    notif.style.color = "#222";
    notif.style.borderRadius = "6px";
    notif.style.boxShadow = "0 2px 8px rgba(0,0,0,0.15)";
    notif.style.zIndex = 9999;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3500);
}

const liteMode = localStorage.getItem('liteMode') === 'true';
activateCSS(liteMode);

function checkFPS(callback) {
    let frame = 0;
    let start = performance.now();

    function loop() {
        frame++;
        const now = performance.now();
        if(now - start < 1000) {
            requestAnimationFrame(loop);
        } else {
            const fps = frame / ((now - start) / 1000);
            callback(fps);
        }
    }
    loop();
}

window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        checkFPS(fps => {
            console.log('FPS:', Math.round(fps));
            if(fps < 50) {
                if(!liteMode) {
                    activateCSS(true);
                    showNotification("Performans düşük, Lite moda geçildi.", "warning");
                }
            }
        });
    }, 1000);
});
</script>