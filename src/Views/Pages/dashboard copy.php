<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Anasayfa | <?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?></title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="./assets/css/css.php?file=dashboard.css">
		<style>
			.active-admins .list-group-item {
			display: flex;
			align-items: center;
			padding: 0.75rem 1.25rem;
			border-radius: 0.375rem;
			cursor: pointer;
			}
			.active-admins .list-group-item img {
			border-radius: 50%;
			width: 40px;
			height: 40px;
			margin-right: 10px;
			}
			.active-admins .list-group-item .username {
			font-weight: bold;
			color: #007bff;
			font-size: 1rem;
			}
			.active-admins .list-group-item .role {
			font-size: 0.875rem;
			color: #6c757d;
			}
			.chat-container {
			height: 500px;
			display: flex;
			flex-direction: column;
			}
			.chat-messages {
			flex: 1;
			overflow-y: auto;
			padding: 15px;
			border-bottom: 1px solid #dee2e6;
			}
			.chat-message {
			margin-bottom: 10px;
			}
			.chat-message .username {
			font-weight: bold;
			color: #007bff;
			}
			.chat-message .message {
			margin: 5px 0;
			}
			.chat-form {
			display: flex;
			align-items: center;
			padding: 10px;
			background-color: #f8f9fa;
			border-top: 1px solid #dee2e6;
			}
			.chat-form input[type="text"] {
			flex: 1;
			margin-right: 10px;
			}
			.dropdown-menu {
			max-height: 300px;
			overflow-y: auto;
			}
			.dropdown-menu .dropdown-item {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			}
			.dropdown-menu .dropdown-item:hover {
			background-color: #f8f9fa;
			}
			.popover-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			}
			.popover-header .btn-close {
			padding: 0.5rem;
			}
		</style>
	</head>
	<body>
		<?php include("./src/Views/Partials/navbar.php"); ?>
		<?= $jumbotron ?>
		<section class="container my-5">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h2>Kategoriler</h2>
				<?php if (@$_SESSION['user_id']): ?>
				<a href="/create-topic" class="btn btn-primary">Konu Aç</a>
				<?php endif; ?>
			</div>
			<div class="row">
				<?php foreach ($categories as $category): ?>
				<div class="col-md-4 mb-4">
					<div class="card">
						<img src="https://placehold.co/350x150" class="card-img-top" alt="Kategori Resmi">
						<div class="card-body">
							<h5 class="card-title"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></h5>
							<p class="card-text"><?= htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8') ?></p>
							<a href="/categories/<?= htmlspecialchars($category['slug'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary">İncele</a>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</section>
		<section class="container my-5">
			<div class="row">
				<div class="col-md-3 mb-4">
					<div class="card active-admins">
						<div class="card-header">
							<h5 class="mb-0">Aktif Yöneticiler</h5>
						</div>
						<ul class="list-group list-group-flush">
							<?php use App\Controllers\UsersController; foreach ($activeAdmins as $admin): ?>
							<li class="list-group-item" id="<?= $admin['id'] ?>" data-bs-toggle="popover" data-bs-html="true" data-bs-content="Loading...">
								<img src="https://via.placeholder.com/40" alt="Admin Fotoğrafı">
								<div>
									<div class="username"><?= htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8') ?></div>
									<div class="role"><?= htmlspecialchars(UsersController::convertUserRoleIdToStr($admin['userRole'], ENT_QUOTES, 'UTF-8')) ?></div>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<div class="col-md-3 mb-4">
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Aktif Kullanıcılar</h5>
						</div>
						<ul class="list-group list-group-flush" style="display: flex; flex-direction: row;">
							<?php foreach ($activeUsers as $user): ?>
							<small class="p-1" id="<?= $user['id'] ?>" data-bs-toggle="popover" data-bs-html="true"><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>,</small>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6 mb-4">
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Sohbet</h5>
						</div>
						<div class="card-body chat-container">
							<div class="chat-messages" id="chat-messages">
								<?php foreach($messages as $message): ?>
								<div class="chat-message">
									<div class="chatUsername"><?= $message['user']['username'] ?>:</div>
									<div class="chatMessage"><?= $message['message'] ?></div>
								</div>
								<?php endforeach; ?>
							</div>
							<div class="chat-form">
								<input type="text" class="form-control" id="message-input" placeholder="Mesaj yazın...">
								<input type="hidden" name="csrfToken" id="csrfToken" value="<?= bin2hex(random_bytes(32)) ?>">
								<button class="btn btn-primary" id="sendButton">Gönder</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="bg-light py-5">
			<div class="container">
				<h2>Son Konular</h2>
				<div class="list-group">
					<?php foreach ($topics as $topic): ?>
					<a href="/topics/<?= htmlspecialchars($topic['slug'], ENT_QUOTES, 'UTF-8') ?>" class="list-group-item list-group-item-action">
						<h5 class="mb-1"><?= htmlspecialchars($topic['title'], ENT_QUOTES, 'UTF-8') ?></h5>
						<small>Yazar: <?= htmlspecialchars($topic['username'], ENT_QUOTES, 'UTF-8') ?> | Tarih: <?= htmlspecialchars($topic['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<script src="./assets/js/javascript.php?file=dashboard.js"></script>
		<?php include("./src/Views/Partials/footer.php"); ?>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
	</body>
</html>