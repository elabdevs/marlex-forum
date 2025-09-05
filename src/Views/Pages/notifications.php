<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bildirimler | <?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/css.php?file=dashboard.css">
    <style>
        .notification-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            padding: 1rem;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .notification-title {
            font-weight: bold;
        }
        .notification-time {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .notification-body {
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <?php include("./src/Views/Partials/navbar.php"); ?>

    <section class="container my-5">
        <h2>Bildirim Geçmişi</h2>
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($notifications as $notification): ?>
                <div class="notification-card">
                    <div class="notification-header">
                        <div class="notification-title"><?= htmlspecialchars($notification['title'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="notification-time"><?= htmlspecialchars($notification['timestamp'], ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="notification-body">
                        <?= htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
