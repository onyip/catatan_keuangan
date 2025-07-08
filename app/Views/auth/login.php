<?= $this->extend('template/index') ?>
<?= $this->section('content') ?>
<div id="auth-container">
    <div class="auth-card">
        <!-- Form Login -->
        <div id="login-form">
            <div class="card p-4 p-sm-5">
                <div class="text-center mb-4">
                    <i class="bi bi-wallet2 fs-1 text-primary"></i>
                    <h3 class="mt-2">Selamat Datang Di <?= $appName; ?></h3>
                    <p class="text-muted">Silakan login untuk melanjutkan</p>
                </div>
                <form id="loginForm" action="<?= base_url(); ?>auth/login_action" method="POST">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Username</label>
                        <input type="text" class="form-control" id="loginEmail" name="user" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="pass" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                    <p class="text-center mt-4 mb-0">
                        Belum punya akun? <a href="<?= base_url(); ?>auth/register">Register di sini</a>
                    </p>
                </form>
                <div class="text-center">
                    <span>Copyright © <?= $appAuthor; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= base_url(); ?>trasnsaksi';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengirim data.'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>