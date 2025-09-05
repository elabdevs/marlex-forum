<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoryName) ?> | <?= $siteTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/css.php?file=dashboard.css">
</head>

<body>
    <?php include("./src/Views/Partials/navbar.php"); ?>

    <?= $jumbotron ?>

    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><?= $categoryName ?> Kategorisi</h2>
            <?php if(@$_SESSION['user_id']): ?>
            <a href="/create-topic?category=<?= $slug ?>" class="btn btn-primary">Konu Aç</a>
            <?php endif; ?>
        </div>

        <!-- Konular Listesi -->
        <div class="list-group">
            <?php if (empty($topics)): ?>
                <div class="alert alert-info">Bu kategoride henüz konu bulunmuyor.</div>
            <?php else: ?>
                <?php foreach ($topics as $topic): ?>
                <a href="/topics/<?= $topic['slug'] ?>" class="list-group-item list-group-item-action">
                    <h5 class="mb-1"><?= $topic['title'] ?></h5>
                    <small>Yazar: <?= $topic['username'] ?> | Tarih: <?= $topic['created_at'] ?></small>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
