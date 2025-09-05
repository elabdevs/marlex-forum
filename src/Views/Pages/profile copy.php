<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | <?= $siteTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/css.php?file=profile.css">
</head>

<body>
    <?php include("./src/Views/Partials/navbar.php"); ?>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Profil</h1>
            <p class="lead">Kullanıcı bilgilerinizi ve etkinliklerinizi görün</p>
        </div>
    </header>

    <section class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/350x350" class="card-img-top" alt="Profil Resmi">
                    <div class="card-body text-center">
                        <h5 class="card-title">Kullanıcı Adı</h5>
                        <p class="card-text">Kullanıcı hakkında kısa bir açıklama buraya gelir.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Profil Düzenle</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Kişisel Bilgiler</h5>
                        <p><strong>Email:</strong> <?= $userEmail ?></p>
                        <p><strong>Üyelik Tarihi:</strong> <?= $created_at ?></p>
                        <p><strong>Hakkında:</strong> <?= $bio ?></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Son Etkinlikler</h5>
                        <ul class="list-group">
                            <?php foreach($userLogs as $log): ?>
                            <li class="list-group-item">
                                <h6 class="mb-1"><?= $log['action'] ?></h6>
                                <small>Tarih: <?= $log['action_time'] ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editProfileModalLabel">Profili Düzenle</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editProfileForm">
          <!-- Profil Fotoğrafı -->
          <div class="mb-3">
            <label for="profilePicture" class="form-label">Profil Fotoğrafı</label>
            <input type="file" class="form-control" id="profilePicture" name="profile_picture">
          </div>

          <!-- Kullanıcı Adı -->
          <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adınızı Girin" required>
          </div>

          <!-- E-Posta -->
          <div class="mb-3">
            <label for="email" class="form-label">E-Posta</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="E-Posta Adresinizi Girin" required>
          </div>

          <!-- Şifre -->
          <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Yeni Şifrenizi Girin" required>
          </div>

          <!-- Şifreyi Onayla -->
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Şifreyi Onayla</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Şifrenizi Tekrar Girin" required>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary" onclick="submitProfileForm()">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Sonu -->

<script>
  function submitProfileForm() {
    // Form verilerini al
    var form = document.getElementById('editProfileForm');
    var formData = new FormData(form);

    // Form verilerini gönder (AJAX veya başka bir yöntemle)
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/update-profile', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Başarılı yanıt işleme
        Swal.fire({
          title: 'Başarılı!',
          text: 'Profil başarıyla güncellendi.',
          icon: 'success',
          confirmButtonText: 'Tamam'
        }).then(function() {
          var modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
          modal.hide();
        });
      } else {
        // Hata işleme
        Swal.fire({
          title: 'Hata!',
          text: 'Bir hata oluştu. Lütfen tekrar deneyin.',
          icon: 'error',
          confirmButtonText: 'Tamam'
        });
      }
    };
    xhr.send(formData);
  }
</script>


    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>