<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol | <?= $siteTitle ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: url('./assets/images/wallpaper.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 1.5rem;
        }

        .card h3, .card label {
            color: #fff;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .card h3 {
            margin-bottom: 1rem;
        }

        .card label {
            display: block; 
            margin-top: 0.5rem; 
        }

        .btn-primary {
            background-color: #0072ff;
            border-color: #0072ff;
        }

        .btn-primary:hover {
            background-color: #005bb5;
            border-color: #004a99;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card p-4">
                    <h3 class="text-center mb-4">Kayıt Ol</h3>
                    <form id="registerForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="username" placeholder="Kullanıcı Adı girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="E-Mail girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="password" placeholder="Şifre girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordConfirm" class="form-label">Şifrenizi Doğrulayın</label>
                            <input type="password" class="form-control" id="passwordConfirm" placeholder="Şifrenizi tekrar girin" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">Kayıt Ol</button>
                        </div>
                        <div class="text-center mt-3">
                            <a href="./login">Giriş Yap</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault(); // Formun varsayılan gönderimini engelle

                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var passwordConfirm = $('#passwordConfirm').val();

                if (password !== passwordConfirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: 'Şifreler uyuşmuyor.',
                    });
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '/api/registerUser',
                    data: {
                        username: username,
                        email: email,
                        password: password,
                        passwordConfirm: passwordConfirm
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı',
                                text: 'Kayıt başarılı, giriş yapabilirsiniz.',
                            }).then(() => {
                                window.location.href = '/login';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                text: response.message || 'Bir hata oluştu.',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata',
                            text: 'Sunucu hatası, lütfen tekrar deneyin.',
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
