<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Detayı | <?= htmlspecialchars($siteTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    html,
    body {
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

    <section class="container my-5">
        <h2>Rapor Detayı - <?= htmlspecialchars($report['id']) ?></h2>
        <div class="alert alert-info" role="alert">
            Burada raporun detaylarını görebilir ve gerekli işlemleri yapabilirsiniz.
        </div>

        <!-- Rapor Detayları -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Rapor Bilgileri</h5>
                <p><strong>Rapor ID:</strong> <?= htmlspecialchars($report['id']) ?></p>
                <p><strong>Kullanıcı ID:</strong> <?= htmlspecialchars($report['reported_by']) ?></p>
                <p><strong>Post ID:</strong> <?= htmlspecialchars($report['post_id']) ?></p>
                <p><strong>Postun olduğu konu:</strong> <?= htmlspecialchars($report['topic_id']) ?></p>
                <p><strong>Tarih:</strong> <?= htmlspecialchars($report['reported_at']) ?></p>
                <p><strong>Durum:</strong> <?= htmlspecialchars($report['status']) ?></p>
            </div>
        </div>

        <!-- Post İçeriği -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Post İçeriği</h5>
                <p><?= htmlspecialchars($postContent) ?></p>
            </div>
        </div>

        <!-- İşlemler -->
        <div class="d-flex justify-content-between">
            <a href="/admin/reports/<?= $report['id'] ?>/approve" class="btn btn-success">Raporu Onayla</a>
            <a href="/admin/reports/<?= $report['id'] ?>/reject" class="btn btn-danger">Raporu Reddet</a>
            <a href="/admin/reports" class="btn btn-secondary">Geri Dön</a>
        </div>
    </section>

    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>