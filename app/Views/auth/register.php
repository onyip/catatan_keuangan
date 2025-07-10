<?= $this->extend('template/index') ?>
<?= $this->section('content') ?>
<div id="auth-container">
    <div class="auth-card">
        <div id="register-form">
            <div class="card p-4 p-sm-5">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
                    <h3 class="mt-2">Buat Akun Baru</h3>
                    <p class="text-muted">Isi data diri Anda</p>
                </div>
                <form id="registerForm" action="<?= base_url(); ?>auth/register_action" method="POST">
                    <div class="mb-3">
                        <label for="registerName" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="registerName" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="registerUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="registerPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Re-Password</label>
                        <input type="password" class="form-control" id="re-registerPassword" name="re-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Register</button>
                    <p class="text-center mt-4 mb-0">
                        Sudah punya akun? <a href="<?= base_url(); ?>auth/login">Login di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(e) {
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
                            window.location.href = '<?= base_url(); ?>auth/login';
                        });
                    } else {
                        let errorMessage = "";
                        $.each(response.data, function(key, value) {
                            errorMessage += value + "\n";
                        });
                        Swal.fire({
                            icon: 'error',
                            title: response.message,
                            text: errorMessage
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