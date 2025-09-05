<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriler | <?= $siteTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/css.php?file=dashboard.css">
    <style>
                html, body {
            height: 100%;
            margin: 0;
        }
        .content {
            flex: 1;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php include("./src/Views/Partials/navbar.php"); ?>

    <?= $jumbotron ?>

    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategoriler</h2>
            <?php if(@$_SESSION['user_id']): ?>
            <a href="/create-topic" class="btn btn-primary">Konu Aç</a>
            <?php endif; ?>
        </div>
        
        <!-- Kategori Listesi -->
        <div class="row">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://via.placeholder.com/350x150" class="card-img-top" alt="Kategori Resmi">
                    <div class="card-body">
                        <h5 class="card-title"><?= $category['name'] ?></h5>
                        <p class="card-text"><?= $category['description'] ?></p>
                        <a href="/categories/<?= $category['slug'] ?>" class="btn btn-primary">İncele</a>
                        <div class="mt-3">
                            <small>Toplam Konu: <?= $category['topic_count'] ?></small><br>
                            <small>Son Konu: <?= $category['latest_topic_title'] ?> (<?= $category['latest_topic_date'] ?>)</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
