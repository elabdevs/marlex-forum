<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raporlanan Postlar | <?= htmlspecialchars($siteTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <section class="container my-5">
        <h2>Yönetici Paneli - Raporlanan Postlar</h2>
        <div class="alert alert-info" role="alert">
            Buradan kullanıcılar tarafından raporlanan postları görebilir ve gerekli işlemleri yapabilirsiniz.
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kullanıcı</th>
                        <th>Post Başlığı</th>
                        <th>Rapor Nedeni</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reportedPosts as $report): ?>
                    <tr>
                        <td><?= htmlspecialchars($report['id']) ?></td>
                        <td><?= htmlspecialchars($report['reported_by']) ?></td>
                        <td><?= htmlspecialchars($report['post_id']) ?></td>
                        <td><?= htmlspecialchars($report['topic_id']) ?></td>
                        <td><?= htmlspecialchars($report['reported_at']) ?></td>
                        <td><?= htmlspecialchars($report['status']) ?></td>
                        <td>
                            <a href="/admin/reports/<?= $report['id'] ?>/review" class="btn btn-info btn-sm">İncele</a>
                            <a href="/admin/reports/<?= $report['id'] ?>/delete" class="btn btn-danger btn-sm">Sil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
