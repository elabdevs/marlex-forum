<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banlanan Kullanıcılar | <?= htmlspecialchars($siteTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.4/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
        <h2>Yönetici Paneli - Banlanan Kullanıcılar</h2>
        <div class="alert alert-info" role="alert">
            Banlanan kullanıcıların bilgilerini buradan görebilirsiniz.
        </div>
        <div class="table-responsive">
            <table class="table table-striped display" id="bannedUsersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kullanıcı ID</th>
                        <th>Banlayan Yetkili</th>
                        <th>Ban Tarihi</th>
                        <th>Banın Sona Ereceği Tarih</th>
                        <th>Ban Sebebi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bannedUsers as $user): ?>
                    <tr data-user-id="<?= htmlspecialchars($user['id']) ?>">
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['banned_by']) ?></td>
                        <td><?= htmlspecialchars($user['banned_at']) ?></td>
                        <td><?= htmlspecialchars($user['ban_expiration']) ?></td>
                        <td><?= htmlspecialchars($user['reason']) ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm actions-btn">İşlemler</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include("./src/Views/Partials/footer.php"); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.4/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#bannedUsersTable', {
                responsive: true,
                language: {
                    info: '_PAGES_ Sayfa Arasından _PAGE_. Sayfayı Görüntülüyorsunuz.',
                    infoEmpty: 'Kayıt Bulunamadı.',
                    infoFiltered: '(_MAX_ Kayıt Arasından Filtreleme Yapıldı.)',
                    lengthMenu: 'Bir Sayfada _MENU_ Kayıt Görüntüle',
                    zeroRecords: 'Veri Bulunamadı.'
                }
            });

            $('#bannedUsersTable').on('click', '.actions-btn', function() {
                let userId = $(this).closest('tr').data('user-id');
                
                Swal.fire({
                    title: 'İşlem Seçin',
                    text: 'Lütfen yapmak istediğiniz işlemi seçin:',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Banı Kaldır',
                    cancelButtonText: 'Banı Uzat',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'remove_ban.php',
                            type: 'POST',
                            data: { user_id: userId },
                            success: function(response) {
                                Swal.fire('Başarılı!', 'Kullanıcının banı kaldırıldı.', 'success');
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Hata!', 'Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: 'Ban Süresini Uzat',
                            input: 'text',
                            inputLabel: 'Yeni Bitiş Tarihini Girin (YYYY-MM-DD)',
                            inputPlaceholder: 'YYYY-MM-DD',
                            showCancelButton: true,
                            confirmButtonText: 'Uzatma',
                            cancelButtonText: 'İptal',
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Lütfen geçerli bir tarih girin!';
                                }
                                return null;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: 'extend_ban.php',
                                    type: 'POST',
                                    data: { user_id: userId, new_expiration: result.value },
                                    success: function(response) {
                                        Swal.fire('Başarılı!', 'Kullanıcının ban süresi uzatıldı.', 'success');
                                        table.ajax.reload();
                                    },
                                    error: function() {
                                        Swal.fire('Hata!', 'Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
