<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konu Ekle | <?= htmlspecialchars($siteTitle) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/css.php?file=dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" rel="stylesheet">
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
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.min.css">
</head>

<body>
    <?php include("./src/Views/Partials/navbar.php"); ?>

    <?php include("./src/Views/Partials/jumbotron.php"); ?>

    <div class="content">
        <main class="container my-5">
            <h2>Konu Ekle</h2>
            <form id="createTopicForm" action="/api/createTopic" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Konu Başlığı</label>
                    <input type="text" class="form-control" id="title" name="title" required maxlength="100">
                    <div class="form-text">Konu başlığı 100 karakterden uzun olmamalıdır.</div>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">İçerik</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                    <div class="form-text">İçerik 500 karakterden uzun olmamalıdır.</div>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select" id="category" name="category_id" required>
                        <option value="" disabled selected>Kategori seçin</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Gönder</button>
            </form>
        </main>
    </div>

    <?php include("./src/Views/Partials/footer.php"); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"></script>
    <!-- SweetAlert JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 300,   // Set the height of the editor
                callbacks: {
                    onImageUpload: function(files) {
                        // Handle image upload here
                    }
                }
            });

            $('#createTopicForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Başarılı!',
                                text: 'Konu başarıyla eklendi.',
                                icon: 'success',
                                confirmButtonText: 'Tamam'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/topics/" + response.data.slug;
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Hata!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Bir hata oluştu. Lütfen tekrar deneyin.',
                            icon: 'error',
                            confirmButtonText: 'Tamam'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
