<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Aktiviteleri | <?= htmlspecialchars($siteTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.4/datatables.min.css" rel="stylesheet">
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
        <h2>Yönetici Paneli - Kullanıcı Aktiviteleri</h2>
        <div class="alert alert-info" role="alert">
            Kullanıcıların son işlemlerini burdan görebilirsiniz.
        </div>
        <div class="table-responsive">
            <table class="table table-striped display" id="logsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kullanıcı ID</th>
                        <th>İşlem</th>
                        <th>İşlem Zamanı</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userLogs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['id']) ?></td>
                        <td><?= htmlspecialchars($log['user_id']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['action_time']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include("./src/Views/Partials/footer.php"); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.4/datatables.min.js"></script>
    <script>
        let table = new DataTable('#logsTable',{
            responsive: true,
            language: {
                info: '_PAGES_ Sayfa Arasından _PAGE_. Sayfayı Görüntülüyorsunuz. ',
                infoEmpty: 'Kayıt Bulunamadı.',
                infoFiltered: '(_MAX_ Kayıt Arasından Filtreleme Yapıldı.)',
                lengthMenu: 'Bir Sayfada _MENU_ Kayıt Görüntüle',
                zeroRecords: 'Veri Bulunamadı.'
            }
        });
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
